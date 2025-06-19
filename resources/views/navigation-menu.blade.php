<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- Notes Link - Visible to both admin and user roles -->
                    @hasanyrole('admin|user')
                    <x-nav-link href="{{ route('notes.index') }}" :active="request()->routeIs('notes.*')">
                        {{ __('Notes') }}
                    </x-nav-link>
                    @endhasanyrole

                    <!-- Meal Plans Link - Visible to both admin and user roles -->
                    @hasanyrole('admin|user')
                    <x-nav-link href="{{ route('meal-plans.index') }}" :active="request()->routeIs('meal-plans.*')">
                        {{ __('الجداول الغذائية') }}
                    </x-nav-link>
                    @endhasanyrole

                    <!-- Articles Link - Visible only to admin role -->
                    @role('admin')
                    <x-nav-link href="{{ route('articles.index') }}" :active="request()->routeIs('articles.*')">
                        {{ __('Articles') }}
                    </x-nav-link>
                    @endrole

                    <!-- Pages Management Link - Visible to admin and page_manager roles -->
                    @hasanyrole('admin|page_manager')
                    <x-nav-link href="{{ route('pages.index') }}" :active="request()->routeIs('pages.index', 'pages.create', 'pages.edit')">
                        {{ __('إدارة الصفحات') }}
                    </x-nav-link>
                    @endhasanyrole

                    <!-- Membership Types Link - Visible only to admin role -->
                    @role('admin')
                    <x-nav-link href="{{ route('membership-types.index') }}" :active="request()->routeIs('membership-types.*')">
                        {{ __('إدارة العضويات') }}
                    </x-nav-link>
                    @endrole

                    <!-- Dynamic Pages from Database -->
                    @php
                        try {
                            $menuPages = \App\Models\Page::where('show_in_menu', true)
                                ->where('is_published', true)
                                ->where(function($query) {
                                    $user = auth()->user();
                                    
                                    // الصفحات العامة
                                    $query->where('access_level', 'public');
                                    
                                    if ($user) {
                                        // المستخدمين المسجلين
                                        $query->orWhere('access_level', 'authenticated');
                                        
                                        // المستخدمين العاديين
                                        if ($user->hasRole('user')) {
                                            $query->orWhere('access_level', 'user');
                                        }
                                        
                                        // مديري الصفحات
                                        if ($user->hasRole('page_manager')) {
                                            $query->orWhere('access_level', 'page_manager');
                                        }
                                        
                                        // المديرين
                                        if ($user->hasRole('admin')) {
                                            $query->orWhere('access_level', 'admin');
                                        }
                                        
                                        // العضويات المدفوعة
                                        if ($user->membership_type_id) {
                                            $query->orWhere(function($q) use ($user) {
                                                $q->where('access_level', 'membership')
                                                  ->whereRaw('JSON_CONTAINS(required_membership_types, ?)', [json_encode($user->membership_type_id)]);
                                            });
                                        }
                                    }
                                })
                                ->orderBy('menu_order');
                            $menuPages = \App\Models\Page::where('show_in_menu', true)
                                ->where('is_published', true)
                                ->where(function($query) {
                                    $user = auth()->user();
                                    
                                    // الصفحات العامة
                                    $query->where('access_level', 'public');
                                    
                                    if ($user) {
                                        // المستخدمين المسجلين
                                        $query->orWhere('access_level', 'authenticated');
                                        
                                        // المستخدمين العاديين
                                        if ($user->hasRole('user')) {
                                            $query->orWhere('access_level', 'user');
                                        }
                                        
                                        // مديري الصفحات
                                        if ($user->hasRole('page_manager')) {
                                            $query->orWhere('access_level', 'page_manager');
                                        }
                                        
                                        // المديرين
                                        if ($user->hasRole('admin')) {
                                            $query->orWhere('access_level', 'admin');
                                        }
                                        
                                        // العضويات المدفوعة
                                        if ($user->membership_type_id) {
                                            $query->orWhere(function($q) use ($user) {
                                                $q->where('access_level', 'membership')
                                                  ->whereRaw('JSON_CONTAINS(required_membership_types, ?)', [json_encode($user->membership_type_id)]);
                                            });
                                        }
                                    }
                                })
                                ->orderBy('menu_order')
                                ->get();
                        } catch (\Exception $e) {
                            $menuPages = collect();
                        }
                    @endphp
                    
                    @foreach($menuPages as $menuPage)
                        <x-nav-link href="{{ route('pages.show', $menuPage->slug) }}" :active="request()->is('page/' . $menuPage->slug)">
                            {{ $menuPage->access_level_icon }} {{ $menuPage->title }}
                        </x-nav-link>
                    @endforeach

                    <!-- All Pages Link - Visible to all authenticated users -->
                    @auth
                    <x-nav-link href="{{ route('pages.public') }}" :active="request()->routeIs('pages.public')">
                        {{ __('جميع الصفحات') }}
                    </x-nav-link>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user() ? Auth::user()->name : 'Guest' }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- Responsive Notes Link - Visible to both admin and user roles -->
            @hasanyrole('admin|user')
            <x-responsive-nav-link href="{{ route('notes.index') }}" :active="request()->routeIs('notes.*')">
                {{ __('Notes') }}
            </x-responsive-nav-link>
            @endhasanyrole

            <!-- Responsive Meal Plans Link - Visible to both admin and user roles -->
            @hasanyrole('admin|user')
            <x-responsive-nav-link href="{{ route('meal-plans.index') }}" :active="request()->routeIs('meal-plans.*')">
                {{ __('الجداول الغذائية') }}
            </x-responsive-nav-link>
            @endhasanyrole

            <!-- Responsive Articles Link - Visible only to admin role -->
            @role('admin')
            <x-responsive-nav-link href="{{ route('articles.index') }}" :active="request()->routeIs('articles.*')">
                {{ __('Articles') }}
            </x-responsive-nav-link>
            @endrole

            <!-- Responsive Pages Management Link - Visible to admin and page_manager roles -->
            @hasanyrole('admin|page_manager')
            <x-responsive-nav-link href="{{ route('pages.index') }}" :active="request()->routeIs('pages.index', 'pages.create', 'pages.edit')">
                {{ __('إدارة الصفحات') }}
            </x-responsive-nav-link>
            @endhasanyrole

            <!-- Responsive Membership Types Link - Visible only to admin role -->
            @role('admin')
            <x-responsive-nav-link href="{{ route('membership-types.index') }}" :active="request()->routeIs('membership-types.*')">
                {{ __('إدارة العضويات') }}
            </x-responsive-nav-link>
            @endrole

            <!-- Responsive Dynamic Pages from Database -->
            @php
                try {
                    $menuPages = \App\Models\Page::inMenu()->published()->accessibleBy(auth()->user())->get();
                } catch (\Exception $e) {
                    $menuPages = collect();
                }
            @endphp
            
            @foreach($menuPages as $menuPage)
                <x-responsive-nav-link href="{{ route('pages.show', $menuPage->slug) }}" :active="request()->is('page/' . $menuPage->slug)">
                    {{ $menuPage->access_level_icon }} {{ $menuPage->title }}
                </x-responsive-nav-link>
            @endforeach

            <!-- Responsive All Pages Link - Visible to all authenticated users -->
            @auth
            <x-responsive-nav-link href="{{ route('pages.public') }}" :active="request()->routeIs('pages.public')">
                {{ __('جميع الصفحات') }}
            </x-responsive-nav-link>
            @endauth
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user() ? Auth::user()->name : 'Guest' }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user() ? Auth::user()->email : '' }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>