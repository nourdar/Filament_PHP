

@if (isset($showAll) && $showAll)
    @include('shop.header')
@endif

@if ($brands)




<section class="py-12 bg-black md:pt-20 md:pb-32"
@if (isset($settings?->style[0])) style="{{ 'background-color:' . $settings?->style[0]['navbar_bgcolor'] }}"> @endif
>
    <div class="container px-4 mx-auto">
      {{-- <h1 class="mb-16 text-4xl font-bold text-center text-white font-heading">Shop by category</h1> --}}
      <div class="flex flex-wrap mb-20 -mx-4">

        @if(isset( $brands[0]))
        <div class="w-1/2 px-4 md:w-1/3 lg:w-1/5">
          <a class="block text-center group" href="brands/{{ $brands[0]['id'] }}">
            <img class="block object-cover w-full h-40 mb-5" src="{{ asset('storage/'.$brands[0]['image']) }}" alt="">
            <h6 class="font-bold text-white group-hover:text-yellow-500">{{ $brands[0]['name'] }}</h6>
          </a>
        </div>
        @endif

        @if(isset( $brands[1]))
        <div class="w-1/2 px-4 md:w-1/3 lg:w-1/5">
          <a class="block text-center group" href="brands/{{ $brands[1]['id'] }}">
            <img class="block object-cover w-full h-40 mb-5" src="{{ asset('storage/'.$brands[1]['image']) }}" alt="">
            <h6 class="font-bold text-white group-hover:text-yellow-500">{{ $brands[1]['name'] }}</h6>
          </a>
        </div>
        @endif



        @if(isset( $brands[2]))
        <div class="w-1/2 px-4 md:w-1/3 lg:w-1/5">
          <a class="block text-center group" href="brands/{{ $brands[2]['id'] }}">
            <img class="block object-cover w-full h-40 mb-5" src="{{ asset('storage/'.$brands[2]['image']) }}" alt="">
            <h6 class="font-bold text-white group-hover:text-yellow-500">{{ $brands[2]['name'] }}</h6>
          </a>
        </div>
        @endif

        @if(isset( $brands[3]))
        <div class="w-1/2 px-4 md:w-1/3 lg:w-1/5">
          <a class="block text-center group" href="brands/{{ $brands[3]['id'] }}">
            <img class="block object-cover w-full h-40 mb-5" src="{{ asset('storage/'.$brands[3]['image']) }}" alt="">
            <h6 class="font-bold text-white group-hover:text-yellow-500">{{ $brands[3]['name'] }}</h6>
          </a>
        </div>
        @endif


        @if(isset( $brands[4]))
        <div class="w-1/2 px-4 md:w-1/3 lg:w-1/5">
          <a class="block text-center group" href="brands/{{ $brands[4]['id'] }}">
            <img class="block object-cover w-full h-40 mb-5" src="{{ asset('storage/'.$brands[4]['image']) }}" alt="">
            <h6 class="font-bold text-white group-hover:text-yellow-500">{{ $brands[4]['name'] }}</h6>
          </a>
        </div>
        @endif

      </div>

      @if (isset($showAll) && $showAll)
      <div class="container flex justify-center w-full mt-5">
          <br>
          {{ $brands->links('pagination::tailwind') }}
      </div>




      @else
          <a aria-label="show more brands" href="all-brands" class="flex justify-center mt-5">

              <button aria-label="show all brands button"
                  class="px-4 py-2 font-semibold bg-transparent border rounded font-cairo bg-btn-primary hover:bg-btn-primary-hover btn-primary-text-color hover:btn-primary-text-color-hover hover:border-transparent">
                  اظهار الكل
              </button>
          </a>
      @endif

      <div class="h-1 rounded-full bg-blueGray-800">
        <div class="w-1/3 h-full bg-yellow-500 rounded-full"></div>
      </div>
    </div>
  </section>







  @endif
<!-- End  brands -->
@if (isset($showAll) && $showAll)
    @include('shop.footer')
@endif
