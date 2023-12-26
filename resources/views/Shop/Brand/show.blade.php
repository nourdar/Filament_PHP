@include('shop.header')
<div class='container py-8 px-6 mx-auto'>

    <nav id="store" class="w-full z-30 top-0 px-6 py-1 mb-5">
        <div class="w-full container mx-auto flex flex-wrap items-center justify-center mt-0 px-2 py-3">

            <a class="uppercase text-center tracking-wide no-underline hover:no-underline font-bold text-red-800 text-8xl "
                href="#">
                <img class="hover:grow hover:shadow-lg m-auto " width="200"
                    src="{{ asset('storage/' . $brand?->image) }}">
                <p>
                    {{ $brand?->name }}
                </p>
            </a>


        </div>
    </nav>

    @include('shop.product.index')

</div>
@include('shop.footer')
