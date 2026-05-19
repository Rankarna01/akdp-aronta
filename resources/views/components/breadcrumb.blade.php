<div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">@yield('page_title', 'Dashboard')</h1>
    <nav class="flex mt-2 sm:mt-0" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="#" class="inline-flex items-center text-sm font-medium text-secondary hover:text-primary transition">
                    <i class="fa-solid fa-house mr-2"></i> Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1"></i>
                    <span class="text-sm font-medium text-gray-500 ml-1">@yield('page_title', 'Dashboard')</span>
                </div>
            </li>
        </ol>
    </nav>
</div>