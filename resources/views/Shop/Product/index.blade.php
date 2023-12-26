<!--  dernier produits section -->
<section class="bg-white py-8">

    <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">


        <nav id="store" class="w-full z-30 top-0 px-6 py-1 mb-5">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-center mt-0 px-2 py-3">

                <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-5xl "
                    href="#">
                    Produits
                </a>


            </div>
        </nav>


        @if ($products)
            @foreach ($products as $product)
                <div class="w-full md:w-1/3 xl:w-1/4 p-6 flex flex-col">
                    <a href="/product/{{ $product->id }}">
                        <img class="hover:grow hover:shadow-lg" src="{{ asset('storage/' . $product->image) }}">
                        <div class="pt-3 flex items-center justify-between">
                            <p class="">{{ $product->name }}</p>

                        </div>
                        <p class="pt-1 text-gray-900">{{ $product->price }} DZD</p>
                    </a>
                </div>
            @endforeach


        @endif
        {{ $products->links() }}

    </div>

</section>

<!-- End  dernier produits section -->
