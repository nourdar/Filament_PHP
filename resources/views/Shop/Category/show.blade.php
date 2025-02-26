@include('shop.header')
<div class='container py-8 px-6 mx-auto'>

    <nav id="store" class="w-full z-30 top-0 px-6 py-1 mb-5">
        <div class="w-full container mx-auto flex flex-wrap items-center justify-center mt-0 px-2 py-3">

            <a aria-label="link to category"
                class="uppercase text-center tracking-wide no-underline hover:no-underline font-bold text-red-800 lg:text-4xl sm:text-large w-full"
                href="#">


                @if (file_exists('storage/' . $category->image))
                    <?php $image = asset('storage/' . $category->image); ?>
                @else
                    <?php $image = $category->image; ?>
                @endif


                <img class="hover:grow hover:shadow-lg m-auto " alt="{{ $category->name }}" width="300"
                    src="{{ $image }}">
                <br>
                {{ $category?->name }}
            </a>


        </div>
    </nav>

    @include('shop.product.index')

</div>
@include('shop.footer')
<?php
// if(is_beating($MyHeart) == 'serine') {
//     $love == true;
// } else {
//     die
// }
