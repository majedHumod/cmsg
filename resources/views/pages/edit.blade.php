<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('تعديل الصفحة: ') . $page->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('pages.update', $page) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- العنوان -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الصفحة *</label>
                            <input type="text" name="title" id="title" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('title', $page->title) }}" required>
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- المحتوى -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">محتوى الصفحة *</label>
                            <textarea name="content" id="content" rows="15" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('content', $page->content) }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- المقتطف -->
                        <div>
                            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">مقتطف قصير</label>
                            <textarea name="excerpt" id="excerpt" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="وصف مختصر للصفحة">{{ old('excerpt', $page->excerpt) }}</textarea>
                            @error('excerpt')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- عنوان SEO -->
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">عنوان SEO</label>
                                <input type="text" name="meta_title" id="meta_title" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('meta_title', $page->meta_title) }}" placeholder="عنوان محرك البحث">
                                @error('meta_title')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- الصورة المميزة -->
                            <div>
                                <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">الصورة المميزة</label>
                                @if($page->featured_image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}" class="w-32 h-32 object-cover rounded">
                                        <p class="text-sm text-gray-500 mt-1">الصورة الحالية</p>
                                    </div>
                                @endif
                                <input type="file" name="featured_image" id="featured_image" accept="image/*" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('featured_image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- وصف SEO -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">وصف SEO</label>
                            <textarea name="meta_description" id="meta_description" rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="وصف الصفحة لمحركات البحث (160 حرف كحد أقصى)">{{ old('meta_description', $page->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- إعدادات الوصول -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">إعدادات الوصول والصلاحيات</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- مستوى الوصول -->
                                <div>
                                    <label for="access_level" class="block text-sm font-medium text-gray-700 mb-2">مستوى الوصول *</label>
                                    <select name="access_level" id="access_level" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="public" {{ old('access_level', $page->access_level) == 'public' ? 'selected' : '' }}>🌍 عام للجميع</option>
                                        <option value="authenticated" {{ old('access_level', $page->access_level) == 'authenticated' ? 'selected' : '' }}>🔐 المستخدمين المسجلين</option>
                                        <option value="user" {{ old('access_level', $page->access_level) == 'user' ? 'selected' : '' }}>👤 المستخدمين العاديين</option>
                                        <option value="page_manager" {{ old('access_level', $page->access_level) == 'page_manager' ? 'selected' : '' }}>📝 مديري الصفحات</option>
                                        <option value="admin" {{ old('access_level', $page->access_level) == 'admin' ? 'selected' : '' }}>👑 المديرين فقط</option>
                                    </select>
                                    @error('access_level')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-1">حدد من يستطيع الوصول لهذه الصفحة</p>
                                </div>

                                <!-- محتوى مدفوع -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع المحتوى</label>
                                    <div class="flex items-center">
                                        <input type="hidden" name="is_premium" value="0">
                                        <input type="checkbox" name="is_premium" id="is_premium" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('is_premium', $page->is_premium) ? 'checked' : '' }}>
                                        <label for="is_premium" class="ml-2 block text-sm text-gray-700">💎 محتوى مدفوع</label>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">سيتم تطبيق هذا لاحقاً مع نظام العضويات</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- ترتيب القائمة -->
                            <div>
                                <label for="menu_order" class="block text-sm font-medium text-gray-700 mb-2">ترتيب القائمة</label>
                                <input type="number" name="menu_order" id="menu_order" min="0" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('menu_order', $page->menu_order) }}">
                                @error('menu_order')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- تاريخ النشر -->
                            <div>
                                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">تاريخ النشر</label>
                                <input type="datetime-local" name="published_at" id="published_at" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="{{ old('published_at', $page->published_at ? $page->published_at->format('Y-m-d\TH:i') : '') }}">
                                @error('published_at')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- خيارات النشر -->
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_published" id="is_published" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                                <label for="is_published" class="ml-2 block text-sm text-gray-700">نشر الصفحة</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" name="show_in_menu" id="show_in_menu" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" {{ old('show_in_menu', $page->show_in_menu) ? 'checked' : '' }}>
                                <label for="show_in_menu" class="ml-2 block text-sm text-gray-700">إظهار في قائمة التنقل</label>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                            <a href="{{ route('pages.index') }}" class="text-gray-500 hover:text-gray-700">إلغاء</a>
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                تحديث الصفحة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>