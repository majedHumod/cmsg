@extends('layouts.admin')

@section('title', 'خطط الوجبات')

@section('header', 'خطط الوجبات')

@section('header_actions')
<div class="flex space-x-2">
    <a href="{{ route('meal-plans.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        إضافة وجبة جديدة
    </a>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Filters Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <form method="GET" action="{{ route('meal-plans.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">البحث</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="ابحث عن وجبة..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <!-- Meal Type Filter -->
                <div>
                    <label for="meal_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الوجبة</label>
                    <select name="meal_type" id="meal_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">جميع الأنواع</option>
                        <option value="breakfast" {{ request('meal_type') == 'breakfast' ? 'selected' : '' }}>إفطار</option>
                        <option value="lunch" {{ request('meal_type') == 'lunch' ? 'selected' : '' }}>غداء</option>
                        <option value="dinner" {{ request('meal_type') == 'dinner' ? 'selected' : '' }}>عشاء</option>
                        <option value="snack" {{ request('meal_type') == 'snack' ? 'selected' : '' }}>وجبة خفيفة</option>
                    </select>
                </div>

                <!-- Difficulty Filter -->
                <div>
                    <label for="difficulty" class="block text-sm font-medium text-gray-700 mb-2">مستوى الصعوبة</label>
                    <select name="difficulty" id="difficulty" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">جميع المستويات</option>
                        <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>سهل</option>
                        <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>متوسط</option>
                        <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>صعب</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">جميع الحالات</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>متاح</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير متاح</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    بحث
                </button>
                
                @if(request()->hasAny(['search', 'meal_type', 'difficulty', 'status']))
                    <a href="{{ route('meal-plans.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        مسح الفلاتر
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Meal Plans Grid -->
    @if($mealPlans->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($mealPlans as $mealPlan)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Image -->
                    <div class="relative h-48 overflow-hidden">
                        @if($mealPlan->image)
                            <img src="{{ Storage::url($mealPlan->image) }}" alt="{{ $mealPlan->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $mealPlan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $mealPlan->is_active ? 'متاح' : 'غير متاح' }}
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <!-- Title and Type -->
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ $mealPlan->name }}</h3>
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $mealPlan->meal_type_name }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $mealPlan->difficulty_name }}
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        @if($mealPlan->description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">{{ $mealPlan->description }}</p>
                        @endif

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            @if($mealPlan->calories)
                                <div class="text-center">
                                    <div class="text-lg font-bold text-green-600">{{ $mealPlan->calories }}</div>
                                    <div class="text-xs text-gray-500">سعرة</div>
                                </div>
                            @endif
                            
                            @if($mealPlan->total_time > 0)
                                <div class="text-center">
                                    <div class="text-lg font-bold text-blue-600">{{ $mealPlan->total_time }}</div>
                                    <div class="text-xs text-gray-500">دقيقة</div>
                                </div>
                            @endif
                            
                            <div class="text-center">
                                <div class="text-lg font-bold text-purple-600">{{ $mealPlan->servings }}</div>
                                <div class="text-xs text-gray-500">حصة</div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between">
                            <a href="{{ route('meal-plans.show', $mealPlan) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                عرض التفاصيل
                            </a>
                            
                            @if(auth()->user()->hasRole('admin') || $mealPlan->user_id === auth()->id())
                                <div class="flex items-center space-x-2 space-x-reverse">
                                    <a href="{{ route('meal-plans.edit', $mealPlan) }}" class="text-yellow-600 hover:text-yellow-700 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    
                                    <form action="{{ route('meal-plans.destroy', $mealPlan) }}" method="POST" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الوجبة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $mealPlans->appends(request()->query())->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد وجبات</h3>
            <p class="text-gray-600 mb-6">لم يتم العثور على أي وجبات تطابق معايير البحث الخاصة بك.</p>
            <a href="{{ route('meal-plans.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة وجبة جديدة
            </a>
        </div>
    @endif
</div>
@endsection