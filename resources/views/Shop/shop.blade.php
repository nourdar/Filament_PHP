@include('shop.header')



<div class="swiper mySwiper">
    <div class="swiper-wrapper">

        @if ($settings?->slides)

            @foreach ($settings?->slides as $slide)
                <?php $i = 1;
                $img = asset('storage/' . $slide['slide']); ?>

                <div class="swiper-slide"><img src="{{ $img }}" alt="shop swiper element" /></div>

                <?php $i++; ?>
            @endforeach
        @else
        @endif


    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
</div>
<!-- Slider main container -->



<!--Slide 2-->
<!-- <input class="carousel-open" type="radio" id="carousel-2" name="carousel" aria-hidden="true" hidden=""> -->


<!-- Add additional indicators for each slide-->
<!-- <ol class="carousel-indicators">
                <li class="inline-block mr-3">
                    <label for="carousel-1" class="carousel-bullet cursor-pointer block text-4xl text-gray-400 hover:text-gray-900">•</label>
                </li>
                <li class="inline-block mr-3">
                    <label for="carousel-2" class="carousel-bullet cursor-pointer block text-4xl text-gray-400 hover:text-gray-900">•</label>
                </li>
                <li class="inline-block mr-3">
                    <label for="carousel-3" class="carousel-bullet cursor-pointer block text-4xl text-gray-400 hover:text-gray-900">•</label>
                </li>
            </ol> -->






@include('shop.brand.index')
@include('shop.category.index')
@include('shop.product.index')

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
