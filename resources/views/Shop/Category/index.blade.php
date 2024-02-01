@if (isset($showAll) && $showAll)
    @include('shop.header')
@endif

@if ($categories )
<section class="py-12 text-center md:pt-16 md:pb-20">
    <div class="container px-4 mx-auto">
      <div class="flex flex-wrap items-end mb-10 -mx-4">
        <div class="w-full px-4 mb-6 sm:w-1/2 xl:w-3/5 sm:mb-0">
          <h1 class="text-4xl font-bold font-heading font-diph">Categories</h1>
        </div>

      </div>
      <div class="flex flex-wrap items-center -mx-4" >



        <div class="w-full h-full px-4 lg:w-1/2"  >
            @if(isset( $categories[0]))
            <a class="relative block mb-6 group h-1/3" href="#">
              <div class="absolute bottom-0 left-0 z-10 w-full p-8">
                <h4 class="mb-4 text-xl font-bold text-white">{{ $categories[0]['name'] }}</h4>
                <span class="p-3 mt-10 font-medium rounded bg-btn-primary btn-primary-text-color ">اظهار</span>
              </div>
              <img  class="relative z-0 block object-cover h-full transition-transform duration-500 transform group-hover:scale-102" src="{{ $categories[0]['image'] }}" alt="">
            </a>
            @endif
            @if(isset( $categories[1]))
            <a class="relative block h-2/3" href="#">
              <div class="absolute bottom-0 left-0 z-10 w-full p-8">
                <h4 class="mb-4 text-xl font-bold text-white">{{ $categories[1]['name'] }}</h4>
                <span class="p-3 mt-10 font-medium rounded bg-btn-primary btn-primary-text-color ">اظهار</span>
              </div>
              <img  class="relative z-0 block object-cover h-full transition-transform duration-500 transform group-hover:scale-102" src="{{ $categories[1]['image'] }}" alt="">
            </a>
            @endif
          </div>

        <div class="w-full h-full px-4 lg:w-1/2"   >
            @if(isset( $categories[2]))
          <a class="relative block mb-6 group h-2/3" href="#">
            <div class="absolute bottom-0 left-0 z-10 w-full p-8">
              <h4 class="mb-4 text-xl font-bold text-white">{{ $categories[2]['name'] }}</h4>
              <span class="p-3 mt-10 font-medium rounded bg-btn-primary btn-primary-text-color ">اظهار</span>
            </div>
            <img  class="relative z-0 block object-cover h-full transition-transform duration-500 transform group-hover:scale-102" src="{{ $categories[2]['image'] }}" alt="">
          </a>
          @endif
          @if(isset( $categories[3]))
          <a class="relative block group h-1/3" href="#">
            <div class="absolute bottom-0 left-0 z-10 w-full p-8">
              <h4 class="mb-4 text-xl font-bold text-white">{{ $categories[3]['name'] }}</h4>
              <span class="p-3 mt-10 font-medium rounded bg-btn-primary btn-primary-text-color ">اظهار</span>
            </div>
            <img  class="relative z-0 block object-cover h-full transition-transform duration-500 transform group-hover:scale-102" src="{{ $categories[3]['image'] }}" alt="">
          </a>
          @endif
        </div>



      </div>
    </div>

    @if (isset($showAll) && $showAll)
    <div class="container flex justify-center w-full mt-5">
        <br>
        {{ $categories->links() }}
    </div>
@else
    <a aria-label="show more categories" href="all-categories" class="flex justify-center mt-5">

        <button aria-label="show all categoreis button"
            class="px-4 py-2 font-semibold bg-transparent border rounded font-cairo bg-btn-primary hover:bg-btn-primary-hover btn-primary-text-color hover:btn-primary-text-color-hover hover:border-transparent">
            اظهار الكل
        </button>
    </a>
@endif
  </section>
  @endif
<!-- End  categories -->
@if (isset($showAll) && $showAll)
    @include('shop.footer')
@endif
