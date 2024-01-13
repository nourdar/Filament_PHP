<nav class=" border-gray-200 bg-slate-900 font-diph w-full "
    @if (isset($settings?->style[0])) style="{{ 'background-color:' . $settings?->style[0]['navbar_bgcolor'] }}" @endif>
    <div class="max-w-screen-xl flex flex-wrap items-center justify-center mx-auto p-4">
        <a aria-label="Link to home page" href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <a aria-label="Link to home page" class="ml-5 flex items-center text-white" href="/">
                <img src="{{ asset('storage/' . $settings?->logo) }}" alt="Logo" class="mr-5"
                    style="max-width: 200px; max-height:80px">
                <span class="hidden md:block text-4xl text-bold" style="font-weight: 600">
                    {{ $settings?->name }}
                </span>
            </a>
        </a>
        {{-- <button data-collapse-toggle="navbar-default" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
            aria-controls="navbar-default" aria-expanded="false">
            <span class="sr-only">Menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button> --}}
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">

        </div>
    </div>
</nav>
