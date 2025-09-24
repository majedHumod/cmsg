@extends('layouts.app')

@section('title', $mealPlan->name)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Content Container -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" dir="rtl">
            <!-- Hero Section with Image -->
            @if($mealPlan->image)
                <div class="relative h-64 md:h-80 overflow-hidden">
                    <img src="{{ Storage::url($mealPlan->image) }}" alt="{{ $mealPlan->name }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute bottom-6 right-6 text-white">
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ $mealPlan->name }}</h1>
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white border border-white/30">
                                {{ $mealPlan->meal_type_name }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white border border-white/30">
                                {{ $mealPlan->difficulty_name }}
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <!-- Header without image -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-12">
                    <div class="text-center text-white">
                        <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ $mealPlan->name }}</h1>
                        <div class="flex items-center justify-center space-x-4 space-x-reverse">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white border border-white/30">
                                {{ $mealPlan->meal_type_name }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm text-white border border-white/30">
                                {{ $mealPlan->difficulty_name }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Content Section -->
            <div class="p-8">
                <!-- Back Button and Description -->
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <a href="{{ route('meal-plans.public') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            العودة للقائمة
                        </a>
                        
                        @auth
                            <a href="{{ route('meal-plans.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                إدارة الوجبات
                            </a>
                        @endauth
                    </div>
                    
                    @if($mealPlan->description)
                        <p class="text-xl text-gray-600 leading-relaxed">{{ $mealPlan->description }}</p>
                    @endif
                </div>

                <!-- Quick Stats Grid -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                    @if($mealPlan->calories)
                        <div class="bg-green-50 p-6 rounded-xl text-center border border-green-100">
                            <div class="text-3xl font-bold text-green-600 mb-2">{{ $mealPlan->calories }}</div>
                            <div class="text-sm font-medium text-gray-600">سعرة حرارية</div>
                        </div>
                    @endif
                    
                    @if($mealPlan->total_time > 0)
                        <div class="bg-blue-50 p-6 rounded-xl text-center border border-blue-100">
                            <div class="text-3xl font-bold text-blue-600 mb-2">{{ $mealPlan->total_time }}</div>
                            <div class="text-sm font-medium text-gray-600">دقيقة</div>
                        </div>
                    @endif
                    
                    <div class="bg-purple-50 p-6 rounded-xl text-center border border-purple-100">
                        <div class="text-3xl font-bold text-purple-600 mb-2">{{ $mealPlan->servings }}</div>
                        <div class="text-sm font-medium text-gray-600">حصة</div>
                    </div>
                    
                    <div class="bg-yellow-50 p-6 rounded-xl text-center border border-yellow-100">
                        <div class="text-2xl font-bold text-yellow-600 mb-2">{{ $mealPlan->difficulty_name }}</div>
                        <div class="text-sm font-medium text-gray-600">مستوى الصعوبة</div>
                    </div>
                </div>

                <!-- Nutrition Information -->
                @if($mealPlan->protein || $mealPlan->carbs || $mealPlan->fats)
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">المعلومات الغذائية</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @if($mealPlan->protein)
                                <div class="bg-red-50 p-6 rounded-xl text-center border border-red-100">
                                    <div class="text-3xl font-bold text-red-600 mb-2">{{ $mealPlan->protein }}ج</div>
                                    <div class="text-sm font-medium text-gray-600">بروتين</div>
                                </div>
                            @endif
                            
                            @if($mealPlan->carbs)
                                <div class="bg-orange-50 p-6 rounded-xl text-center border border-orange-100">
                                    <div class="text-3xl font-bold text-orange-600 mb-2">{{ $mealPlan->carbs }}ج</div>
                                    <div class="text-sm font-medium text-gray-600">كربوهيدرات</div>
                                </div>
                            @endif
                            
                            @if($mealPlan->fats)
                                <div class="bg-indigo-50 p-6 rounded-xl text-center border border-indigo-100">
                                    <div class="text-3xl font-bold text-indigo-600 mb-2">{{ $mealPlan->fats }}ج</div>
                                    <div class="text-sm font-medium text-gray-600">دهون</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Ingredients Section -->
                @if($mealPlan->ingredients && count($mealPlan->ingredients) > 0)
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">المكونات</h2>
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($mealPlan->ingredients as $ingredient)
                                    <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-100">
                                        <span class="font-medium text-gray-900">{{ $ingredient['name'] }}</span>
                                        <span class="text-gray-600">{{ $ingredient['amount'] }} {{ $ingredient['unit'] ?? '' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Instructions Section -->
                @if($mealPlan->instructions && count($mealPlan->instructions) > 0)
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">طريقة التحضير</h2>
                        <div class="space-y-4">
                            @foreach($mealPlan->instructions as $index => $instruction)
                                <div class="flex items-start space-x-4 space-x-reverse p-6 bg-gray-50 rounded-xl border border-gray-200">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                        {{ $index + 1 }}
                                    </div>
                                    <p class="text-gray-700 leading-relaxed">{{ $instruction }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Tags Section -->
                @if($mealPlan->tags && count($mealPlan->tags) > 0)
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">العلامات</h2>
                        <div class="flex flex-wrap gap-3">
                            @foreach($mealPlan->tags as $tag)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    #{{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Meta Information -->
                <div class="border-t border-gray-200 pt-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600">
                        <div>
                            <p><span class="font-medium">تم الإنشاء:</span> {{ $mealPlan->created_at->format('d/m/Y H:i') }}</p>
                            @if($mealPlan->updated_at != $mealPlan->created_at)
                                <p><span class="font-medium">آخر تحديث:</span> {{ $mealPlan->updated_at->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                        <div>
                            @if($mealPlan->user)
                                <p><span class="font-medium">المنشئ:</span> {{ $mealPlan->user->name }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection