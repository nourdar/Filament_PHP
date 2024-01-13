@if (isset($showAll) && $showAll)
    @include('shop.header')
@endif


<!--  dernier produits section -->
<section class="">

    <div class="container flex flex-wrap items-center pt-4 pb-12 mx-auto">


        <nav id="store" class="top-0 z-30 w-full px-6 py-1 mb-5">
            <div class="container flex flex-wrap items-center justify-center w-full px-2 py-3 mx-auto mt-0">

                <a aria-label="show  products"
                    class="text-4xl font-bold tracking-wide text-gray-800 no-underline uppercase hover:no-underline font-diph"
                    href="#">
                    Produits
                </a>


            </div>
        </nav>


        @if ($products)
            <div class="flex flex-row flex-wrap justify-center w-full p-6 space-x-2 space-y-2">


                @foreach ($products as $product)
                    @if (file_exists('storage/' . $product->image))
                        <?php $image = asset('storage/' . $product->image); ?>
                    @else
                        <?php $image = $product->image; ?>
                    @endif
                    <div
                        class="relative flex flex-col w-full max-w-xs overflow-hidden bg-white border border-gray-100 rounded-lg shadow-md gap-y-12">
                        <a class="relative flex mx-3 mt-3 overflow-hidden h-60 rounded-xl" aria-label="link to product"
                            href="/product/{{ $product->id }}">
                            <img class="object-cover w-full" src="{{ $image }}" width="300" height="300"
                                alt="product image" />
                            @if ($product->old_price)
                                <span
                                    class="absolute top-0 left-0 px-2 m-2 text-sm font-medium text-center text-white bg-black rounded-full">{{ number_format((($product->price - $product->old_price ) / $product->price) * 100, 2) }}%
                                    OFF</span>
                            @endif
                        </a>
                        <div class="px-5 pb-5 mt-4">
                            <a aria-label="link to product" href="/product/{{ $product->id }}">
                                <h5 class="text-xl tracking-tight text-slate-900">{{ $product->name }}</h5>
                            </a>
                            <div class="flex items-center justify-between mt-2 mb-5 ">
                                <p>
                                    <span
                                        class="text-xl font-bold text-slate-900">{{ number_format($product->price, 0) }}
                                        DA</span>
                                    @if ($product->old_price)
                                        <span
                                            class="text-sm line-through text-slate-900">{{ number_format($product->old_price, 0) }}
                                            DA</span>
                                    @endif
                                </p>
                                <div class="flex items-center">
                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    <svg aria-hidden="true" class="w-5 h-5 text-yellow-300" fill="currentColor"
                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                        </path>
                                    </svg>
                                    <span
                                        class="mr-2 ml-3 rounded bg-yellow-200 px-2.5 py-0.5 text-xs font-semibold">5.0</span>
                                </div>
                            </div>
                            <a aria-label="link to product" href="/product/{{ $product->id }}"
                                class="flex items-center justify-center rounded-md bg-slate-900 bg-btn-primary px-5 py-2.5 text-center text-sm font-medium text-white hover:btn-primary-text-color-hover hover:bg-btn-primary-hover focus:outline-none focus:ring-4 focus:ring-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                اطلب الان</a>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif


    </div>

    @if (isset($showAll) && $showAll)
        <div class="container flex justify-center w-full">
            <br>
            {{ $products->links() }}
        </div>
    @else
        <a aria-label="show more products" href="all-products" class="flex justify-center">

            <button aria-label="show all brands button"
                class="px-4 py-2 font-semibold bg-transparent border rounded  font-cairo bg-btn-primary hover:bg-btn-primary-hover btn-primary-text-color hover:btn-primary-text-color-hover hover:border-transparent">
                اظهار الكل
            </button>
        </a>
    @endif
</section>

<!-- End  dernier produits section -->

@if (isset($showAll) && $showAll)
    @include('shop.footer')
@endif
