<div>






<div class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">{{__('messages.call_now')}}</h5>
    <p class="mb-5 text-base text-gray-500 sm:text-lg dark:text-gray-400">{{__('messages.call_now_desc')}}</p>
    <div class="items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">
        @if($phones->isNotEmpty())
        @foreach ($phones as $phone)

        <a href="#" class="w-full sm:w-auto bg-gray-800 hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-300 text-white rounded-lg inline-flex items-center justify-center px-4 py-2.5 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700">
            <svg class="w-[30px] h-[30px] text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M5 4c0-1.1.9-2 2-2h10a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4Zm12 12V5H7v11h10Zm-5 1a1 1 0 1 0 0 2 1 1 0 1 0 0-2Z" clip-rule="evenodd"/>
              </svg>
                          <div class="text-left rtl:text-right">
                <div class="mb-1 text-xs"></div>
                <div class="-mt-1 font-sans text-sm font-semibold"> {{ $phone['phone'] }}</div>
            </div>
        </a>
        @endforeach
        @endif
    </div>
</div>


</div>
