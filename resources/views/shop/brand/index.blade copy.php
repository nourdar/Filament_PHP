@if (isset($showAll) && $showAll)
    @include('shop.header')
@endif
<!--  Marques -->
<section class="">

    <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">

        <nav id="store" class="w-full z-30 top-0 px-6 py-1 mb-5">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-center mt-0 px-2 py-3">

                <a aria-label="show more brands"
                    class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-4xl  font-diph"
                    href="#">
                    Marques
                </a>


            </div>
        </nav>
        @if ($brands)
            <div class="flex flex-row space-x-2 space-y-2 w-full p-6  justify-center   flex-wrap">



                @foreach ($brands as $brand)
                    <div
                        class="w-full md:w-1/3 xl:w-1/4 p-6 text-center border border-gray-200 rounded-lg shadow hover:bg-gray-100  dark:border-gray-700 dark:hover:bg-gray-700 dark:hover:text-white">
                        <a aria-label="link to brand" href="/brand/{{ $brand->id }}" class="text-center">


                            @if (file_exists('storage/' . $brand->image))
                                <?php $image = asset('storage/' . $brand->image); ?>
                            @else
                                <?php $image = $brand->image; ?>
                            @endif

                            <img class="hover:grow hover:shadow-lg  " alt="{{ $brand->name }}" width="200"
                                height="200" style="display: inline; max-height:200px; height:200px"
                                src="{{ $image }}">
                            <div class="pt-3 flex items-center justify-center justify-center font-cairo">
                                <p class="text-center " style="font-size: 25px; font-weight:bold">{{ $brand->name }}
                                </p>

                            </div>

                        </a>
                    </div>
                @endforeach
            </div>

        @endif


    </div>

    @if (isset($showAll) && $showAll)
        <div class="container w-full flex justify-center">
            <br>
            {{ $brands->links() }}
        </div>
    @else
        <a aria-label="show more brands" href="all-brands" class="flex justify-center">

            <button aria-label="show all brands button"
                class=" font-cairo bg-transparent bg-btn-primary hover:bg-btn-primary-hover btn-primary-text-color font-semibold hover:btn-primary-text-color-hover py-2 px-4 border hover:border-transparent rounded">
                اظهار الكل
            </button>
        </a>
    @endif
</section>

<!-- End  Marques -->
@if (isset($showAll) && $showAll)
    @include('shop.footer')
@endif
