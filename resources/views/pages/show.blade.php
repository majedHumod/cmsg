<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $page->meta_title ?? $page->title }} - {{ \App\Models\SiteSetting::get('site_name', config('app.name', 'Laravel')) }}</title>
    
    @if($page->meta_description)
        <meta name="description" content="{{ $page->meta_description }}">
    @endif

    <!-- Favicon -->
    @php
        $siteFavicon = \App\Models\SiteSetting::get('site_favicon');
    @endphp
    @if($siteFavicon)
        <link rel="icon" href="{{ Storage::url($siteFavicon) }}" type="image/x-icon">
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=tajawal:400,500,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Colors -->
    @php
        $primaryColor = \App\Models\SiteSetting::get('primary_color', '#6366f1');
        $secondaryColor = \App\Models\SiteSetting::get('secondary_color', '#10b981');
    @endphp
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
        }
        
        .bg-primary {
            background-color: var(--primary-color);
        }
        
        .text-primary {
            color: var(--primary-color);
        }
        
        .border-primary {
            border-color: var(--primary-color);
        }
        
        .bg-secondary {
            background-color: var(--secondary-color);
        }
        
        .text-secondary {
            color: var(--secondary-color);
        }
        
        .border-secondary {
            border-color: var(--secondary-color);
        }
    </style>
    
    <!-- Styles for rich content -->
    <style>
        .prose {
            max-width: none;
        }
        .prose img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .prose table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        .prose table th,
        .prose table td {
            border: 1px solid #e5e7eb;
            padding: 0.5rem;
            text-align: right;
        }
        .prose table th {
            background-color: #f9fafb;
            font-weight: 600;
        }
        .prose blockquote {
            border-right: 4px solid #6366f1;
            padding-right: 1rem;
            margin: 1rem 0;
            font-style: italic;
            background-color: #f8fafc;
            padding: 1rem;
            border-radius: 4px;
        }
        .prose ul, .prose ol {
            padding-right: 1.5rem;
        }
        .prose h1, .prose h2, .prose h3, .prose h4, .prose h5, .prose h6 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 700;
        }
        .prose h1 { font-size: 2.25rem; }
        .prose h2 { font-size: 1.875rem; }
        .prose h3 { font-size: 1.5rem; }
        .prose h4 { font-size: 1.25rem; }
        .prose h5 { font-size: 1.125rem; }
        .prose h6 { font-size: 1rem; }
        .prose p {
            margin-bottom: 1rem;
            line-height: 1.75;
            text-align: right;
        }
        .prose a {
            color: #6366f1;
            text-decoration: underline;
        }
        .prose a:hover {
            color: #4f46e5;
        }
        .prose code {
            background-color: #f1f5f9;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
        }
        .prose pre {
            background-color: #1e293b;
            color: #f1f5f9;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1rem 0;
        }
        .prose pre code {
            background-color: transparent;
            padding: 0;
            color: inherit;
        }
    </style>
</head>
<body class="font-sans antialiased pt-16" dir="rtl">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <header class="bg-white shadow-sm fixed top-0 left-0 right-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16" dir="rtl">
                    <!-- Logo and Site Name -->
                    <div class="flex items-center order-1">
                        @php
                            $siteLogo = \App\Models\SiteSetting::get('site_logo');
                            $siteName = \App\Models\SiteSetting::get('site_name', config('app.name', 'Laravel'));
                        @endphp
                        @if($siteLogo)
                            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                                <img class="h-8 w-auto" src="{{ Storage::url($siteLogo) }}" alt="{{ $siteName }}">
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                                <span class="text-xl font-bold text-indigo-600">{{ $siteName }}</span>
                            </a>
                        @endif
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex md:items-center md:space-x-4 order-2">
                        <a href="{{ route('home') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">الرئيسية</a>
                        <a href="{{ route('faqs.index') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">الأسئلة الشائعة</a>

                        @php
                            try {
                                $allMenuPages = \App\Models\Page::where('show_in_menu', true)
                                    ->where('is_published', true)
                                    ->orderBy('menu_order')
                                    ->get();

                                $user = auth()->user();
                                $menuPages = $allMenuPages->filter(function($page) use ($user) {
                                    if ($page->access_level === 'public') return true;
                                    if (!$user) return false;
                                    if ($page->access_level === 'authenticated') return true;
                                    if ($page->access_level === 'user' && $user->hasRole('user')) return true;
                                    if ($page->access_level === 'page_manager' && $user->hasRole('page_manager')) return true;
                                    if ($page->access_level === 'admin' && $user->hasRole('admin')) return true;
                                    if ($page->access_level === 'membership' && $user->membership_type_id) {
                                        $requiredTypes = $page->required_membership_types;
                                        if (is_string($requiredTypes)) {
                                            $requiredTypes = json_decode($requiredTypes, true) ?: [];
                                        }
                                        return in_array($user->membership_type_id, $requiredTypes);
                                    }
                                    return false;
                                });
                            } catch (\Exception $e) {
                                $menuPages = collect([]);
                            }
                        @endphp

                        @foreach($menuPages as $menuPage)
                            <a href="{{ route('pages.show', $menuPage->slug) }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                                {{ $menuPage->title }}
                            </a>
                        @endforeach

                        @auth
                            <div class="ms-3 relative" x-data="{ open: false }">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                                <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                            </button>
                                        @else
                                            <span class="inline-flex rounded-md">
                                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                    {{ Auth::user()->name }}
                                                    <svg class="ms-2 -me-0.5 size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                                                </button>
                                            </span>
                                        @endif
                                    </x-slot>
                                    <x-slot name="content">
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">لوحة التحكم</a>
                                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">الملف الشخصي</a>
                                        @role('admin')
                                            <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">الإعدادات</a>
                                        @endrole
                                        <div class="border-t border-gray-100"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">تسجيل الخروج</button>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">تسجيل الدخول</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-md text-sm font-medium">إنشاء حساب</a>
                        @endguest
                    </div>

                    <!-- Mobile menu button -->
                    <div class="flex items-center md:hidden order-3">
                        <button type="button" id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" aria-expanded="false">
                            <span class="sr-only">فتح القائمة الرئيسية</span>
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div class="hidden md:hidden" id="mobile-menu">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الرئيسية</a>
                    <a href="{{ route('faqs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الأسئلة الشائعة</a>

                    @foreach($menuPages as $menuPage)
                        <a href="{{ route('pages.show', $menuPage->slug) }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">
                            {{ $menuPage->title }}
                        </a>
                    @endforeach

                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">لوحة التحكم</a>
                        <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">الملف الشخصي</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-right px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">تسجيل الخروج</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-indigo-600 hover:text-indigo-800 hover:bg-gray-50">إنشاء حساب</a>
                    @endauth
                </div>
            </div>

            <script>
                // Mobile menu toggle
                document.addEventListener('DOMContentLoaded', function() {
                    const mobileMenuButton = document.getElementById('mobile-menu-button');
                    const mobileMenu = document.getElementById('mobile-menu');
                    
                    if (mobileMenuButton && mobileMenu) {
                        mobileMenuButton.addEventListener('click', function() {
                            mobileMenu.classList.toggle('hidden');
                        });
                    }
                });
            </script>
        </header>

        <!-- Page Content -->
        <main class="py-12" dir="rtl">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <article class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    @if($page->featured_image)
                        <div class="w-full h-64 md:h-80">
                            <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif

                    <div class="p-6 md:p-8">
                        <header class="mb-8">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $page->title }}</h1>
                            
                            @if($page->access_level === 'membership')
                                <div class="mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                        </svg>
                                        محتوى خاص بالعضويات المدفوعة
                                    </span>
                                </div>
                            @endif
                            
                            @if($page->excerpt)
                                <p class="text-xl text-gray-600 leading-relaxed">{{ $page->excerpt }}</p>
                            @endif

                            <div class="flex items-center text-sm text-gray-500 mt-4">
                                <span>نُشر بواسطة {{ $page->user->name }}</span>
                                @if($page->published_at)
                                    <span class="mx-2">•</span>
                                    <time datetime="{{ $page->published_at->toISOString() }}">
                                        {{ $page->published_at->format('d F Y') }}
                                    </time>
                                @endif
                            </div>
                        </header>

                        <div class="prose prose-lg max-w-none text-right" dir="rtl">
                            {!! $page->content !!}
                        </div>

                        @if($page->updated_at != $page->created_at)
                            <footer class="mt-8 pt-6 border-t border-gray-200 text-right" dir="rtl">
                                <p class="text-sm text-gray-500">
                                    آخر تحديث: {{ $page->updated_at->format('d F Y H:i') }}
                                </p>
                            </footer>
                        @endif
                    </div>
                </article>

                <!-- Navigation Links -->
                <div class="mt-8 flex justify-center space-x-4" dir="rtl">
                    <a href="{{ route('pages.public') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        عرض جميع الصفحات
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            العودة للوحة التحكم
                        </a>
                    @endauth
                </div>
            </div>
        </main>
        
        <!-- Footer -->
        @include('layouts.footer')
    </div>
</body>
</html>