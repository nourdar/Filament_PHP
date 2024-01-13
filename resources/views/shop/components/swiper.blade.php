<style>

    .swiper {
      width: 95%;
      height: 400px;
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
</style>



 <!-- Swiper -->
 <div class="swiper mySwiper">
    <div class="swiper-wrapper">
        @foreach ($settings?->slides as $slide)
            <?php $i = 1;
            $img = asset('storage/' . $slide['slide']);
            $link = $slide['link']; ?>

<a aria-label="swiper-link" class="swiper-slide" href="{{ $link }}">

                    <img src="{{ $img }}" alt="shop swiper element"  />


            </a>
            <?php $i++; ?>
        @endforeach
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-pagination"></div>
  </div>


  <!-- Initialize Swiper -->
  <script>
      window.onload = function() {
    var swiper = new Swiper(".mySwiper", {
      cssMode: true,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      pagination: {
        el: ".swiper-pagination",
      },
      mousewheel: true,
      keyboard: true,
    });
}
  </script>










