<?php

namespace App\Services;

use App\Models\User;
use App\Models\PermissionGroup;
use App\Models\PermissionCategory;
use App\Models\UserPermissionOverride;
use App\Models\PermissionAuditLog;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdvancedPermissionService
{
    /**
     * التحقق من صلاحية المستخدم مع التجاوزات
     */
    public function userHasPermission(User $user, string $permission): bool
    {
        // التحقق من التجاوزات أولاً
        $override = UserPermissionOverride::where('user_id', $user->id)
            ->whereHas('permission', function ($query) use ($permission) {
                $query->where('name', $permission);
            })
            ->active()
            ->valid()
            ->first();

        if ($override) {
            // تسجيل استخدام التجاوز
            $this->logPermissionUsage($user, $permission, 'override_used', $override->type);
            
            return $override->type === 'grant';
        }

        // التحقق من الصلاحيات العادية
        return $user->hasPermissionTo($permission);
    }

    /**
     * منح تجاوز صلاحية للمستخدم
     */
    public function grantPermissionOverride(
        User $user, 
        string $permission, 
        string $type = 'grant',
        ?string $reason = null,
        ?\DateTime $expiresAt = null
    ): UserPermissionOverride {
        $permissionModel = Permission::where('name', $permission)->firstOrFail();
        
        $override = UserPermissionOverride::updateOrCreate(
            [
                'user_id' => $user->id,
                'permission_id' => $permissionModel->id,
            ],
            [
                'type' => $type,
                'reason' => $reason,
                'expires_at' => $expiresAt,
                'granted_by' => auth()->id(),
                'is_active' => true
            ]
        );

        // تسجيل العملية
        PermissionAuditLog::logPermissionChange(
            $user,
            'override_' . $type,
            $permission,
            null,
            ['type' => $type, 'expires_at' => $expiresAt],
            $reason
        );

        // مسح الكاش
        $this->clearUserPermissionCache($user);

        return $override;
    }

    /**
     * سحب تجاوز الصلاحية
     */
    public function revokePermissionOverride(User $user, string $permission, ?string $reason = null): bool
    {
        $permissionModel = Permission::where('name', $permission)->first();
        
        if (!$permissionModel) {
            return false;
        }

        $override = UserPermissionOverride::where('user_id', $user->id)
            ->where('permission_id', $permissionModel->id)
            ->first();

        if ($override) {
            $oldValues = $override->toArray();
            $override->update(['is_active' => false]);

            // تسجيل العملية
            PermissionAuditLog::logPermissionChange(
                $user,
                'override_revoked',
                $permission,
                $oldValues,
                ['is_active' => false],
                $reason
            );

            // مسح الكاش
            $this->clearUserPermissionCache($user);

            return true;
        }

        return false;
    }

    /**
     * الحصول على جميع صلاحيات المستخدم مع التجاوزات
     */
    public function getUserPermissions(User $user): Collection
    {
        $cacheKey = "user_permissions_{$user->id}";
        
        return Cache::remember($cacheKey, 3600, function () use ($user) {
            // الصلاحيات العادية من الأدوار
            $rolePermissions = $user->getAllPermissions();
            
            // التجاوزات النشطة
            $overrides = UserPermissionOverride::where('user_id', $user->id)
                ->with('permission')
                ->active()
                ->valid()
                ->get();

            $permissions = collect();

            // إضافة الصلاحيات العادية
            foreach ($rolePermissions as $permission) {
                $permissions->put($permission->name, [
                    'permission' => $permission,
                    'source' => 'role',
                    'override' => null
                ]);
            }

            // تطبيق التجاوزات
            foreach ($overrides as $override) {
                if ($override->type === 'grant') {
                    $permissions->put($override->permission->name, [
                        'permission' => $override->permission,
                        'source' => 'override_grant',
                        'override' => $override
                    ]);
                } elseif ($override->type === 'deny') {
                    $permissions->forget($override->permission->name);
                }
            }

            return $permissions;
        });
    }

    /**
     * الحصول على الصلاحيات مجمعة حسب المجموعات
     */
    public function getPermissionsByGroups(): Collection
    {
        return Cache::remember('permissions_by_groups', 3600, function () {
            return PermissionGroup::with([
                'categories.permissions' => function ($query) {
                    $query->where('is_active', true)->orderBy('sort_order');
                }
            ])
            ->active()
            ->ordered()
            ->get();
        });
    }

    /**
     * إنشاء مجموعة صلاحيات جديدة
     */
    public function createPermissionGroup(array $data): PermissionGroup
    {
        $group = PermissionGroup::create($data);
        
        // مسح الكاش
        Cache::forget('permissions_by_groups');
        
        return $group;
    }

    /**
     * إنشاء تصنيف صلاحيات جديد
     */
    public function createPermissionCategory(array $data): PermissionCategory
    {
        $category = PermissionCategory::create($data);
        
        // مسح الكاش
        Cache::forget('permissions_by_groups');
        
        return $category;
    }

    /**
     * إنشاء صلاحية جديدة مع التصنيف
     */
    public function createPermission(array $data): Permission
    {
        $permission = Permission::create($data);
        
        // مسح الكاش
        Cache::forget('permissions_by_groups');
        $this->clearAllUserPermissionCaches();
        
        return $permission;
    }

    /**
     * التحقق من تبعيات الصلاحيات
     */
    public function checkPermissionDependencies(User $user, string $permission): array
    {
        $permissionModel = Permission::where('name', $permission)->first();
        
        if (!$permissionModel) {
            return ['valid' => false, 'missing' => [], 'conflicts' => []];
        }

        // التحقق من التبعيات المطلوبة
        $requiredDependencies = DB::table('permission_dependencies')
            ->join('permissions', 'permission_dependencies.depends_on_permission_id', '=', 'permissions.id')
            ->where('permission_dependencies.permission_id', $permissionModel->id)
            ->where('permission_dependencies.dependency_type', 'required')
            ->pluck('permissions.name');

        $missing = [];
        foreach ($requiredDependencies as $requiredPermission) {
            if (!$this->userHasPermission($user, $requiredPermission)) {
                $missing[] = $requiredPermission;
            }
        }

        // التحقق من التعارضات
        $conflictingDependencies = DB::table('permission_dependencies')
            ->join('permissions', 'permission_dependencies.depends_on_permission_id', '=', 'permissions.id')
            ->where('permission_dependencies.permission_id', $permissionModel->id)
            ->where('permission_dependencies.dependency_type', 'conflicting')
            ->pluck('permissions.name');

        $conflicts = [];
        foreach ($conflictingDependencies as $conflictingPermission) {
            if ($this->userHasPermission($user, $conflictingPermission)) {
                $conflicts[] = $conflictingPermission;
            }
        }

        return [
            'valid' => empty($missing) && empty($conflicts),
            'missing' => $missing,
            'conflicts' => $conflicts
        ];
    }

    /**
     * الحصول على إحصائيات الصلاحيات
     */
    public function getPermissionStatistics(): array
    {
        return Cache::remember('permission_statistics', 1800, function () {
            return [
                'total_permissions' => Permission::count(),
                'active_permissions' => Permission::where('is_active', true)->count(),
                'total_roles' => Role::count(),
                'active_roles' => Role::where('is_active', true)->count(),
                'total_overrides' => UserPermissionOverride::count(),
                'active_overrides' => UserPermissionOverride::active()->valid()->count(),
                'expired_overrides' => UserPermissionOverride::expired()->count(),
                'permission_groups' => PermissionGroup::count(),
                'permission_categories' => PermissionCategory::count(),
                'recent_changes' => PermissionAuditLog::recent(7)->count()
            ];
        });
    }

    /**
     * تنظيف التجاوزات المنتهية الصلاحية
     */
    public function cleanupExpiredOverrides(): int
    {
        $expiredCount = UserPermissionOverride::expired()
            ->where('is_active', true)
            ->count();

        UserPermissionOverride::expired()
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // مسح الكاش
        $this->clearAllUserPermissionCaches();

        return $expiredCount;
    }

    /**
     * تسجيل استخدام الصلاحية
     */
    private function logPermissionUsage(User $user, string $permission, string $action, ?string $details = null): void
    {
        PermissionAuditLog::create([
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'action' => $action,
            'permission_name' => $permission,
            'new_values' => ['details' => $details],
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    /**
     * مسح كاش صلاحيات المستخدم
     */
    private function clearUserPermissionCache(User $user): void
    {
        Cache::forget("user_permissions_{$user->id}");
    }

    /**
     * مسح كاش جميع المستخدمين
     */
    private function clearAllUserPermissionCaches(): void
    {
        $userIds = User::pluck('id');
        foreach ($userIds as $userId) {
            Cache::forget("user_permissions_{$userId}");
        }
    }
}