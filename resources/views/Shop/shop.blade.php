@include('shop.header')

@if (!isset($isSearch))

    @if ($settings?->slides)
        @if (isset($settings?->slides[0]['slide']))
            @include('shop.components.swiper')
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
    <!-- <section class="">

    <div class="container px-6 py-8 mx-auto text-center">

        <a class="mb-8 text-5xl font-bold tracking-wide text-gray-800 no-underline uppercase hover:no-underline" href="#">
Description de la boutique
</a>

        <p class="mt-8 mb-8">
        {{ $settings->description }}
        </p>
    </div>

</section> -->
@endif


@include('shop.footer')
