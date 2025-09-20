@extends('layouts.admin')

@section('title', 'تفاصيل الوجبة')

@section('header', 'تفاصيل الوجبة: ' . $mealPlan->name)

@section('header_actions')
<div class="flex space-x-2">
    @if(auth()->user()->hasRole('admin') || $mealPlan->user_id === auth()->id())
        <a href="{{ route('meal-plans.edit', $mealPlan) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            تعديل
        </a>
    @endif
    <a href="{{ route('meal-plans.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
        <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        العودة للقائمة
    </a>
</div>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 mb-6">
    <article>
        <!-- Meal Plan Image -->
        @if($mealPlan->image)
            <div class="w-full h-64 md:h-80 mb-8 rounded-lg overflow-hidden">
                <img src="{{ Storage::url($mealPlan->image) }}" alt="{{ $mealPlan->name }}" class="w-full h-full object-cover" loading="lazy" decoding="async">
            </div>
        @endif

        <header class="mb-8">
            <div class="flex justify-between items-start mb-4">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $mealPlan->name }}</h1>
            </div>
            
            <!-- Status and Type Badges -->
            <div class="flex items-center space-x-4 space-x-reverse mb-6">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    {{ $mealPlan->meal_type_name }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                    {{ $mealPlan->difficulty_name }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $mealPlan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $mealPlan->is_active ? 'نشط' : 'غير نشط' }}
                </span>
            </div>
            
            @if($mealPlan->description)
                <p class="text-xl text-gray-600 leading-relaxed mb-6">{{ $mealPlan->description }}</p>
            @endif

            <!-- Meal Plan Details -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                @if($mealPlan->calories)
                    <div class="bg-green-50 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $mealPlan->calories }}</div>
                        <div class="text-sm text-gray-600">سعرة حرارية</div>
                    </div>
                @endif
                
                @if($mealPlan->total_time > 0)
                    <div class="bg-blue-50 p-4 rounded-lg text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $mealPlan->total_time }}</div>
                        <div class="text-sm text-gray-600">دقيقة</div>
                    </div>
                @endif
                
                <div class="bg-purple-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $mealPlan->servings }}</div>
                    <div class="text-sm text-gray-600">حصة</div>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-yellow-600">{{ $mealPlan->difficulty_name }}</div>
                    <div class="text-sm text-gray-600">مستوى الصعوبة</div>
                </div>
            </div>

            <!-- Nutrition Information -->
            @if($mealPlan->protein || $mealPlan->carbs || $mealPlan->fats)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">المعلومات الغذائية</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @if($mealPlan->protein)
                            <div class="bg-red-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-red-600">{{ $mealPlan->protein }}ج</div>
                                <div class="text-sm text-gray-600">بروتين</div>
                            </div>
                        @endif
                        
                        @if($mealPlan->carbs)
                            <div class="bg-orange-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ $mealPlan->carbs }}ج</div>
                                <div class="text-sm text-gray-600">كربوهيدرات</div>
                            </div>
                        @endif
                        
                        @if($mealPlan->fats)
                            <div class="bg-yellow-50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ $mealPlan->fats }}ج</div>
                                <div class="text-sm text-gray-600">دهون</div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Macro Percentages -->
                    @if($mealPlan->calories && $mealPlan->calories > 0)
                        <div class="mt-6 bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-sm font-medium text-gray-900 mb-3">توزيع المغذيات الكبرى</h3>
                            <div class="space-y-2">
                                @if($mealPlan->protein)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">البروتين</span>
                                        <div class="flex items-center">
                                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ round(($mealPlan->protein * 4 / $mealPlan->calories) * 100) }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium">{{ round(($mealPlan->protein * 4 / $mealPlan->calories) * 100) }}%</span>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($mealPlan->carbs)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">الكربوهيدرات</span>
                                        <div class="flex items-center">
                                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-orange-500 h-2 rounded-full" style="width: {{ round(($mealPlan->carbs * 4 / $mealPlan->calories) * 100) }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium">{{ round(($mealPlan->carbs * 4 / $mealPlan->calories) * 100) }}%</span>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($mealPlan->fats)
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">الدهون</span>
                                        <div class="flex items-center">
                                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                                                <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ round(($mealPlan->fats * 9 / $mealPlan->calories) * 100) }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium">{{ round(($mealPlan->fats * 9 / $mealPlan->calories) * 100) }}%</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </header>

    </article>
</div>

<!-- Ingredients Section -->
@if($mealPlan->ingredients)
    <section class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">المكونات</h2>
            <div class="bg-gray-50 p-6 rounded-lg">
                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! nl2br(e($mealPlan->ingredients)) !!}
                </div>
            </div>
        </div>
    </section>
@endif

<!-- Instructions Section -->
@if($mealPlan->instructions)
    <section class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">طريقة التحضير</h2>
            <div class="bg-gray-50 p-6 rounded-lg">
                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! nl2br(e($mealPlan->instructions)) !!}
                </div>
            </div>
        </div>
    </section>
@else
    <section class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="text-center py-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">لا توجد تعليمات تحضير</h3>
                <p class="text-sm text-gray-500 mb-6">لم يتم إضافة تعليمات تحضير لهذه الوجبة بعد.</p>
                
                @if(auth()->user()->hasRole('admin') || $mealPlan->user_id === auth()->id())
                    <a href="{{ route('meal-plans.edit', $mealPlan) }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors duration-200">
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        إضافة تعليمات التحضير
                    </a>
                @endif
            </div>
        </div>
    </section>
@endif

<!-- Additional Details Section -->
<section class="mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">معلومات إضافية</h2>
        <div class="bg-gray-50 p-6 rounded-lg">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">المؤلف:</dt>
                    <dd class="text-sm text-gray-900">{{ $mealPlan->user->name }}</dd>
                </div>
                @if($mealPlan->prep_time)
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">وقت التحضير:</dt>
                        <dd class="text-sm text-gray-900">{{ $mealPlan->prep_time }} دقيقة</dd>
                    </div>
                @endif
                @if($mealPlan->cook_time)
                    <div class="flex justify-between">
                        <dt class="text-sm font-medium text-gray-500">وقت الطبخ:</dt>
                        <dd class="text-sm text-gray-900">{{ $mealPlan->cook_time }} دقيقة</dd>
                    </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">تاريخ الإنشاء:</dt>
                    <dd class="text-sm text-gray-900">{{ $mealPlan->created_at ? $mealPlan->created_at->format('d/m/Y H:i') : '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">آخر تحديث:</dt>
                    <dd class="text-sm text-gray-900">{{ $mealPlan->updated_at ? $mealPlan->updated_at->format('d/m/Y H:i') : '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">يمكن تعديلها:</dt>
                    <dd class="text-sm text-gray-900">{{ (auth()->user()->hasRole('admin') || $mealPlan->user_id === auth()->id()) ? '✅ نعم' : '❌ لا' }}</dd>
                </div>
            </dl>
        </div>
    </div>
</section>
@endsection