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
</style>

<div class='container py-8 px-6 mx-auto'>

    <nav id="store" class="w-full z-30 top-0 mb-5">
        <div class="w-full container mx-auto flex flex-wrap items-center justify-center mt-0 ">

            <a class="w-full uppercase text-center tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-2xl "
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


    <div class="flex flex-wrap">

        <div class="w-full lg:w-1/2  md:w-full">
            <!-- Swiper -->

            <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff" class="swiper productSwiper2">
                <div class="swiper-wrapper">

                    @if ($product->image)
                        <div class="swiper-slide">

                            @if (file_exists('storage/' . $product->image))
                                <?php $mainImage = asset('storage/' . $product->image); ?>;
                            @else
                                <?php $mainImage = $product->image; ?>
                            @endif

                            <img src="{{ $mainImage }}" />
                        </div>
                    @endif

                    @if ($product->images)

                        @foreach ($product->images as $image)
                            <div class="swiper-slide">

                                @if (file_exists('storage/' . $image))
                                    <?php $photo = asset('storage/' . $image); ?>;

                                    <img src="{{ $photo }}" />
                                @else
                                    <img src="{{ $image }}" />
                                @endif

                            </div>
                        @endforeach
                    @endif


                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
            <div thumbsSlider="" class="swiper productSwiper">
                <div class="swiper-wrapper">

                    @if ($product->image)
                        <div class="swiper-slide">
                            <img src="{{ $mainImage }}" />
                        </div>
                    @endif

                    @if ($product->images)
                        @foreach ($product->images as $image)
                            <div class="swiper-slide">

                                @if (file_exists('storage/' . $image))
                                    <?php $photo = asset('storage/' . $image); ?>;

                                    <img src="{{ $photo }}" />
                                @else
                                    <img src="{{ $image }}" />
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
