@include('shop.header')

<style>
    html,
    body {
        position: relative;
        height: 100%;
    }

    body {
        background: #eee;
        font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
        font-size: 14px;
        color: #000;
        margin: 0;
        padding: 0;
    }

    .swiper {
        width: 100%;
        height: 100%;
    }

    .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .markdown p {
        margin: 1em !important;
    }






    .productSwiper2 {
        height: 80%;
        width: 100%;
    }

    .productSwiper {
        height: 20%;
        box-sizing: border-box;
        padding: 10px 0;
    }

    .productSwiper .swiper-slide {
        width: 25%;
        height: 100%;
        opacity: 0.4;
    }

    .productSwiper .swiper-slide-thumb-active {
        opacity: 1;
    }


    @media screen and (max-width: 500px) {
        .swiper {
            width: 100%;
            height: 50%;
        }

        .swiper-slide img {

            object-fit: contain;
        }
    }
</style>


@if (Session::has('message'))
    {{-- <p class="alert text-center text-xl text-bold {{ Session::get('alert-class', 'alert-info') }}">
        {{ Session::get('message') }}
    </p> --}}
    @if (Session::get('alert-class') == 'alert-success')
        {{-- <div class="flex items-center justify-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium"> {{ Session::get('message') }}
            </div>
        </div> --}}




        <div class="flex justify-center items-center h-5 fixed bottom-12 right-5" style="z-index: 99999">
            <div x-data="{ open: true }">
                <!-- Open modal button -->
                {{-- <button x-on:click="open = true" class="px-4 py-2  text-white ">


                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="50px"
                        width="50px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve">
                        <path style="fill:#EB86BF;"
                            d="M512,256.006C512,397.402,397.394,512.004,256.004,512C114.606,512.004,0,397.402,0,256.006  C-0.007,114.61,114.606,0,256.004,0C397.394,0,512,114.614,512,256.006z" />
                        <g>
                            <path style="fill:#D670AD;"
                                d="M511.86,250.507c-0.066-0.066-0.133-0.134-0.201-0.201c-0.53-0.538-123.677-123.683-124.212-124.212   c-31.615-32.022-75.471-51.93-123.919-51.93c-4.185,0-7.578,3.388-7.578,7.577c0,2.507,1.297,4.621,3.172,6   c0.453,0.618,0.974,1.137,1.591,1.589c0.454,0.62,24.166,24.38,24.358,24.589c-7.035-1.125-14.196-1.872-21.543-1.872   c-4.185,0-7.578,3.388-7.578,7.575c0,2.507,1.297,4.62,3.169,5.999c0.455,0.619,26.453,26.619,27.073,27.073   c0.012,0.017,0.021,0.035,0.034,0.051c-7.3-1.729-14.875-2.742-22.698-2.742c-4.185,0-7.578,3.389-7.578,7.577   c0,2.507,4.144,7.135,4.762,7.589c0.453,0.618,27.876,28.067,28.385,28.477c-7.744-3.629-16.349-5.716-25.452-5.716   c-4.183,0-7.575,3.388-7.575,7.577c0,2.507,1.297,4.621,3.169,5.999c0.455,0.619,203.58,203.68,203.886,203.911   c0.122,0.161,0.241,0.318,0.35,0.484C493.971,363.762,512,312.005,512,256.005C512,254.161,511.9,252.341,511.86,250.507z" />
                            <path style="fill:#D670AD;"
                                d="M459.417,411.378c-0.415-0.43-56.426-56.526-57.005-57.005c-0.544-0.513-1.013-1.111-1.588-1.588   c-0.547-0.515-238.287-238.385-238.86-238.86c-5.769-5.455-13.203-8.891-21.223-9.388c-9.753-0.591-19.331,3.026-26.247,9.958   l-28.305,28.361c-15.675,15.715-38.349,66.071,89.331,194.036c0.247,0.247,0.477,0.46,0.722,0.706   c0.299,0.301,0.567,0.585,0.868,0.886c0.267,0.268,0.515,0.497,0.781,0.763c0.279,0.282,158.956,158.958,159.214,159.214   c0.089,0.089,0.172,0.177,0.258,0.263C386.329,482.319,428.685,451.552,459.417,411.378z" />
                        </g>
                        <g>
                            <path style="fill:#F6F6F6;"
                                d="M394.322,346.911l-44.313-34.56c-12.442-9.715-29.77-9.633-42.137,0.148l-9.847,7.806   c-7.543,5.994-18.375,5.342-25.174-1.472l-79.253-79.427c-6.818-6.829-7.451-17.698-1.472-25.275l7.78-9.877   c9.763-12.379,9.826-29.729,0.151-42.181l-34.482-44.401c-6.012-7.74-15.065-12.526-24.834-13.133   c-9.751-0.592-19.33,3.026-26.247,9.959l-28.307,28.357c-15.675,15.715-38.349,66.073,89.331,194.036   c83.252,83.43,134.028,100.943,161.966,100.943c16.784,0,26.507-6.208,31.705-11.417l28.298-28.367   c6.914-6.926,10.537-16.499,9.93-26.281C406.812,361.989,402.039,352.933,394.322,346.911z M386.761,387.353l-28.301,28.367   c-3.17,3.174-9.386,6.962-20.976,6.962c-20.525,0-67.459-12.533-151.237-96.49C89.713,229.444,77.79,172.723,96.915,153.553   l28.305-28.361c3.566-3.573,8.401-5.571,13.414-5.571c0.388,0,0.777,0.015,1.169,0.037c5.427,0.34,10.459,2.996,13.803,7.302   l34.482,44.401c5.394,6.94,5.357,16.61-0.085,23.506l-7.78,9.877c-10.724,13.599-9.589,33.103,2.645,45.356l79.253,79.427   c12.245,12.283,31.734,13.414,45.311,2.656l9.852-7.806c6.866-5.445,16.496-5.475,23.407-0.089l44.308,34.56   c4.299,3.351,6.955,8.405,7.292,13.851C392.628,378.156,390.611,383.491,386.761,387.353z" />
                            <path style="fill:#F6F6F6;"
                                d="M263.527,74.164c-4.185,0-7.577,3.389-7.577,7.577c0,4.188,3.392,7.577,7.577,7.577   c87.732,0,159.106,71.377,159.106,159.106c0,4.188,3.392,7.577,7.577,7.577s7.577-3.389,7.577-7.577   C437.787,152.334,359.614,74.164,263.527,74.164z" />
                            <path style="fill:#F6F6F6;"
                                d="M263.527,127.2c66.842,0,121.223,54.382,121.223,121.223c0,4.188,3.392,7.577,7.577,7.577   s7.577-3.389,7.577-7.577c0-75.195-61.178-136.377-136.377-136.377c-4.185,0-7.577,3.389-7.577,7.577S259.344,127.2,263.527,127.2z   " />
                            <path style="fill:#F6F6F6;"
                                d="M263.527,165.156c45.955,0,83.342,37.334,83.342,83.231c0,4.188,3.392,7.577,7.577,7.577   s7.577-3.389,7.577-7.577c0-54.249-44.182-98.383-98.494-98.383c-4.185,0-7.577,3.389-7.577,7.577   C255.951,161.767,259.344,165.156,263.527,165.156z" />
                            <path style="fill:#F6F6F6;"
                                d="M263.646,187.93c-4.185,0-7.577,3.389-7.577,7.577s3.392,7.577,7.577,7.577   c24.875,0,45.111,20.236,45.111,45.111c0,4.188,3.392,7.577,7.577,7.577s7.577-3.389,7.577-7.577   C323.91,214.965,296.875,187.93,263.646,187.93z" />
                        </g>
                    </svg>

                </button> --}}
                <!-- Modal Overlay -->
                <div x-show="open" class="fixed inset-0 px-2 z-10 overflow-hidden flex items-center justify-center ">
                    <div x-show="open" x-transition:enter="transition-opacity ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-in duration-300"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <!-- Modal Content -->
                    <div x-show="open" x-transition:enter="transition-transform ease-out duration-300"
                        x-transition:enter-start="transform scale-75" x-transition:enter-end="transform scale-100"
                        x-transition:leave="transition-transform ease-in duration-300"
                        x-transition:leave-start="transform scale-100" x-transition:leave-end="transform scale-75"
                        class="bg-white rounded-md shadow-xl overflow-hidden max-w-md w-full sm:w-96 md:w-1/2 lg:w-2/3 xl:w-1/3 z-50">
                        <!-- Modal Header -->
                        <div class="bg-green-500 text-white px-4 py-2 flex justify-center">
                            <h2 class="text-lg font-semibold text-center"></h2>
                        </div>
                        <!-- Modal Body -->
                        <div class="p-4 text-center">
                            {{ Session::get('message') }}
                        </div>
                        <!-- Modal Footer -->
                        <div class="border-t px-4 py-2 flex justify-end">
                            <button x-on:click="open = false"
                                class="px-3 py-1 bg-indigo-500 text-white  rounded-md w-full sm:w-auto"> اغلاق </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 text-center"
            role="alert">
            <span class="font-medium">{{ Session::get('message') }} </span>
        </div> --}}





        <div class="flex justify-center items-center h-5 fixed bottom-12 right-5" style="z-index: 99999">
            <div x-data="{ open: true }">
                <!-- Open modal button -->
                {{-- <button x-on:click="open = true" class="px-4 py-2  text-white ">


                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="50px"
                        width="50px" version="1.1" id="Layer_1" viewBox="0 0 512 512" xml:space="preserve">
                        <path style="fill:#EB86BF;"
                            d="M512,256.006C512,397.402,397.394,512.004,256.004,512C114.606,512.004,0,397.402,0,256.006  C-0.007,114.61,114.606,0,256.004,0C397.394,0,512,114.614,512,256.006z" />
                        <g>
                            <path style="fill:#D670AD;"
                                d="M511.86,250.507c-0.066-0.066-0.133-0.134-0.201-0.201c-0.53-0.538-123.677-123.683-124.212-124.212   c-31.615-32.022-75.471-51.93-123.919-51.93c-4.185,0-7.578,3.388-7.578,7.577c0,2.507,1.297,4.621,3.172,6   c0.453,0.618,0.974,1.137,1.591,1.589c0.454,0.62,24.166,24.38,24.358,24.589c-7.035-1.125-14.196-1.872-21.543-1.872   c-4.185,0-7.578,3.388-7.578,7.575c0,2.507,1.297,4.62,3.169,5.999c0.455,0.619,26.453,26.619,27.073,27.073   c0.012,0.017,0.021,0.035,0.034,0.051c-7.3-1.729-14.875-2.742-22.698-2.742c-4.185,0-7.578,3.389-7.578,7.577   c0,2.507,4.144,7.135,4.762,7.589c0.453,0.618,27.876,28.067,28.385,28.477c-7.744-3.629-16.349-5.716-25.452-5.716   c-4.183,0-7.575,3.388-7.575,7.577c0,2.507,1.297,4.621,3.169,5.999c0.455,0.619,203.58,203.68,203.886,203.911   c0.122,0.161,0.241,0.318,0.35,0.484C493.971,363.762,512,312.005,512,256.005C512,254.161,511.9,252.341,511.86,250.507z" />
                            <path style="fill:#D670AD;"
                                d="M459.417,411.378c-0.415-0.43-56.426-56.526-57.005-57.005c-0.544-0.513-1.013-1.111-1.588-1.588   c-0.547-0.515-238.287-238.385-238.86-238.86c-5.769-5.455-13.203-8.891-21.223-9.388c-9.753-0.591-19.331,3.026-26.247,9.958   l-28.305,28.361c-15.675,15.715-38.349,66.071,89.331,194.036c0.247,0.247,0.477,0.46,0.722,0.706   c0.299,0.301,0.567,0.585,0.868,0.886c0.267,0.268,0.515,0.497,0.781,0.763c0.279,0.282,158.956,158.958,159.214,159.214   c0.089,0.089,0.172,0.177,0.258,0.263C386.329,482.319,428.685,451.552,459.417,411.378z" />
                        </g>
                        <g>
                            <path style="fill:#F6F6F6;"
                                d="M394.322,346.911l-44.313-34.56c-12.442-9.715-29.77-9.633-42.137,0.148l-9.847,7.806   c-7.543,5.994-18.375,5.342-25.174-1.472l-79.253-79.427c-6.818-6.829-7.451-17.698-1.472-25.275l7.78-9.877   c9.763-12.379,9.826-29.729,0.151-42.181l-34.482-44.401c-6.012-7.74-15.065-12.526-24.834-13.133   c-9.751-0.592-19.33,3.026-26.247,9.959l-28.307,28.357c-15.675,15.715-38.349,66.073,89.331,194.036   c83.252,83.43,134.028,100.943,161.966,100.943c16.784,0,26.507-6.208,31.705-11.417l28.298-28.367   c6.914-6.926,10.537-16.499,9.93-26.281C406.812,361.989,402.039,352.933,394.322,346.911z M386.761,387.353l-28.301,28.367   c-3.17,3.174-9.386,6.962-20.976,6.962c-20.525,0-67.459-12.533-151.237-96.49C89.713,229.444,77.79,172.723,96.915,153.553   l28.305-28.361c3.566-3.573,8.401-5.571,13.414-5.571c0.388,0,0.777,0.015,1.169,0.037c5.427,0.34,10.459,2.996,13.803,7.302   l34.482,44.401c5.394,6.94,5.357,16.61-0.085,23.506l-7.78,9.877c-10.724,13.599-9.589,33.103,2.645,45.356l79.253,79.427   c12.245,12.283,31.734,13.414,45.311,2.656l9.852-7.806c6.866-5.445,16.496-5.475,23.407-0.089l44.308,34.56   c4.299,3.351,6.955,8.405,7.292,13.851C392.628,378.156,390.611,383.491,386.761,387.353z" />
                            <path style="fill:#F6F6F6;"
                                d="M263.527,74.164c-4.185,0-7.577,3.389-7.577,7.577c0,4.188,3.392,7.577,7.577,7.577   c87.732,0,159.106,71.377,159.106,159.106c0,4.188,3.392,7.577,7.577,7.577s7.577-3.389,7.577-7.577   C437.787,152.334,359.614,74.164,263.527,74.164z" />
                            <path style="fill:#F6F6F6;"
                                d="M263.527,127.2c66.842,0,121.223,54.382,121.223,121.223c0,4.188,3.392,7.577,7.577,7.577   s7.577-3.389,7.577-7.577c0-75.195-61.178-136.377-136.377-136.377c-4.185,0-7.577,3.389-7.577,7.577S259.344,127.2,263.527,127.2z   " />
                            <path style="fill:#F6F6F6;"
                                d="M263.527,165.156c45.955,0,83.342,37.334,83.342,83.231c0,4.188,3.392,7.577,7.577,7.577   s7.577-3.389,7.577-7.577c0-54.249-44.182-98.383-98.494-98.383c-4.185,0-7.577,3.389-7.577,7.577   C255.951,161.767,259.344,165.156,263.527,165.156z" />
                            <path style="fill:#F6F6F6;"
                                d="M263.646,187.93c-4.185,0-7.577,3.389-7.577,7.577s3.392,7.577,7.577,7.577   c24.875,0,45.111,20.236,45.111,45.111c0,4.188,3.392,7.577,7.577,7.577s7.577-3.389,7.577-7.577   C323.91,214.965,296.875,187.93,263.646,187.93z" />
                        </g>
                    </svg>

                </button> --}}
                <!-- Modal Overlay -->
                <div x-show="open" class="fixed inset-0 px-2 z-10 overflow-hidden flex items-center justify-center ">
                    <div x-show="open" x-transition:enter="transition-opacity ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-in duration-300"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <!-- Modal Content -->
                    <div x-show="open" x-transition:enter="transition-transform ease-out duration-300"
                        x-transition:enter-start="transform scale-75" x-transition:enter-end="transform scale-100"
                        x-transition:leave="transition-transform ease-in duration-300"
                        x-transition:leave-start="transform scale-100" x-transition:leave-end="transform scale-75"
                        class="bg-white rounded-md shadow-xl overflow-hidden max-w-md w-full sm:w-96 md:w-1/2 lg:w-2/3 xl:w-1/3 z-50">
                        <!-- Modal Header -->
                        <div class="bg-red-500 text-white px-4 py-2 flex justify-center">
                            <h2 class="text-lg font-semibold text-center"></h2>
                        </div>
                        <!-- Modal Body -->
                        <div class="p-4 text-center">
                            {{ Session::get('message') }}
                        </div>
                        <!-- Modal Footer -->
                        <div class="border-t px-4 py-2 flex justify-end">
                            <button x-on:click="open = false"
                                class="px-3 py-1 bg-indigo-500 text-white  rounded-md w-full sm:w-auto"> اغلاق </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif

<div class='container py-8 px-6 mx-auto'>

    <nav id="store" class="w-full z-30 top-0 mb-5">
        <div class="w-full container mx-auto flex flex-wrap items-center justify-center mt-0 ">

            <a aria-label="link to product"
                class="w-full uppercase text-center tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-2xl "
                href="#">
                {{ $product?->name }}
                <br>
                {{ number_format($product?->price, 0, '.') }}
                DZD

                @if (isset($product?->old_price))
                    <span class="text-red-900 line-through">
                        {{ number_format($product?->old_price, 0, '.') }}
                        DZD
                    </span>
                @endif
            </a>


        </div>
    </nav>


    <div class="flex flex-wrap lg:gap-x-5  lg:flex-nowrap">

        <div class="w-full lg:w-1/2  md:w-full" style="max-height: 500px">
            <!-- Swiper -->

            <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper productSwiper2">
                <div class="swiper-wrapper">

                    @if ($product->image)
                        <div class="swiper-slide">

                            @if (file_exists('storage/' . $product->image))
                                <?php $mainImage = asset('storage/' . $product->image); ?>
                            @else
                                <?php $mainImage = $product->image; ?>
                            @endif

                            <img src="{{ $mainImage }}" alt="{{ $product->name }}" />
                        </div>
                    @endif

                    @if ($product->images)

                        @foreach ($product->images as $image)
                            <div class="swiper-slide">

                                @if (file_exists('storage/' . $image))
                                    <?php $photo = asset('storage/' . $image); ?>

                                    <img src="{{ $photo }}" alt="{{ $product->name }}" />
                                @else
                                    <img src="{{ $image }}" alt="{{ $product->name }}" />
                                @endif

                            </div>
                        @endforeach
                    @endif


                </div>
                <div class="swiper-button-next bg-slate-900 p-8 rounded"></div>
                <div class="swiper-button-prev bg-slate-900 p-8 rounded"></div>
            </div>
            <div thumbsSlider="" class="swiper productSwiper">
                <div class="swiper-wrapper">

                    @if ($product->image)
                        <div class="swiper-slide">
                            <img src="{{ $mainImage }}" alt="{{ $product->name }}" />
                        </div>
                    @endif

                    @if ($product->images)
                        @foreach ($product->images as $image)
                            <div class="swiper-slide">

                                @if (file_exists('storage/' . $image))
                                    <?php $photo = asset('storage/' . $image); ?>

                                    <img src="{{ $photo }}" />
                                @else
                                    <img src="{{ $image }}" alt="{{ $product->name }}" />
                                @endif


                            </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>


        <div class="w-full lg:w-1/2  md:w-full place-order-form">
            {{-- Start Place Order Form  --}}

            @include('shop.product.form')

            {{-- End Place Order form --}}
        </div>

    </div>




    <br>
    <hr>

    <div class="mt-5 markdown font-cairo">


        @if ($product?->description)
            {!! Str::markdown($product?->description) !!}
        @endif
    </div>

</div>

</div>

@include('shop.footer')

<script>
    var swiper = new Swiper(".productSwiper", {
        loop: true,
        spaceBetween: 10,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesProgress: true,
    });
    var swiper2 = new Swiper(".productSwiper2", {
        loop: true,
        spaceBetween: 10,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        thumbs: {
            swiper: swiper,
        },
    });
</script>
