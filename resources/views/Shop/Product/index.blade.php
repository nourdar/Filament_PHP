@if (isset($showAll) && $showAll)
    @include('shop.header')
@endif


<!--  dernier produits section -->
<section class="bg-white py-8">

    <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">


        <nav id="store" class="w-full z-30 top-0 px-6 py-1 mb-5">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-center mt-0 px-2 py-3">

                <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-4xl font-diph"
                    href="#">
                    Produits
                </a>


            </div>
        </nav>


        @if ($products)
            <div class="flex flex-row space-x-2 space-y-2 w-full  p-6  justify-center   flex-wrap">


                @foreach ($products as $product)
                    <div
                        class="w-full md:w-1/3 xl:w-1/4 p-6 text-center border border-gray-200 rounded-lg shadow hover:bg-gray-100  dark:border-gray-700 dark:hover:bg-gray-700 dark:hover:text-white">
                        <a href="/product/{{ $product->id }}" class="text-center">

                            @if (file_exists('storage/' . $product->image))
                                <?php $image = asset('storage/' . $product->image); ?>
                            @else
                                <?php $image = $product->image; ?>
                            @endif

                            <img class="hover:grow hover:shadow-lg " width="200" height="200"
                                style="display: inline; max-height:200px; height:200px" src="{{ $image }}">


                            <div class="pt-3 flex items-center justify-between font-cairo text-center">
                                <p class="">{{ $product->name }}</p>

                            </div>
                            @if (isset($product?->old_price))
                                <span class="text-red-700 hover:text-white line-through ">
                                    {{ number_format($product?->old_price, 0, '.') }}
                                    DZD
                                </span>
                            @endif
                            <p class="pt-1 text-gray-900 ">{{ number_format($product?->price, 0, '.') }} DZD</p>


                        </a>
                    </div>
                @endforeach
            </div>

        @endif


    </div>

    @if (isset($showAll) && $showAll)
        <div class="container w-full flex justify-center">
            <br>
            {{ $products->links() }}
        </div>
    @else
        <a href="all-products" class="flex justify-center">

            <button
                class=" font-cairo bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                اظهار الكل
            </button>
        </a>
    @endif
</section>

<!-- End  dernier produits section -->

@if (isset($showAll) && $showAll)
    @include('shop.footer')
@endif
