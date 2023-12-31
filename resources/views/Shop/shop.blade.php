@include('shop.header')

@if (!isset($isSearch))

    @if ($settings?->slides)
        @if (isset($settings?->slides[0]['slide']))
            <div class="swiper mySwiper mb-5">
                <div class="swiper-wrapper">

                    @foreach ($settings?->slides as $slide)
                        <?php $i = 1;
                        $img = asset('storage/' . $slide['slide']);
                        $link = $slide['link']; ?>
                        <a aria-label="swiper link" class="swiper-slide" href="{{ $link }}">
                            {{-- <div class="swiper-slide"> --}}

                            <img src="{{ $img }}" alt="shop swiper element" />

                            {{-- </div> --}}
                        </a>
                        <?php $i++; ?>
                    @endforeach

                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
            <!-- Slider main container -->
        @endif
    @endif
@endif
@if (isset($isSearch) && $isSearch)

    @if ($products->count() != 0)
        @include('shop.product.index')
    @endif

    @if ($brands->count() != 0)
        @include('shop.brand.index')
    @endif

    @if ($categories->count() != 0)
        @include('shop.category.index')
    @endif
@else
    @if ($brands->count() != 0)
        @include('shop.brand.index')
    @endif

    @if ($categories->count() != 0)
        @include('shop.category.index')
    @endif
    @if ($products->count() != 0)
        @include('shop.product.index')
    @endif


@endif



@if ($settings?->description)
    <!-- <section class="bg-white py-8">

    <div class="container py-8 px-6 mx-auto text-center">

        <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-5xl mb-8" href="#">
Description de la boutique
</a>

        <p class="mt-8 mb-8">
        {{ $settings->description }}
        </p>
    </div>

</section> -->
@endif


@include('shop.footer')
