@if (isset($showAll) && $showAll)
    @include('shop.header')
@endif
<!--  Categories -->
<section class="bg-white py-8">

    <div class="container mx-auto flex items-center flex-wrap pt-4 pb-12">

        <nav id="store" class="w-full z-30 top-0 px-6 py-1 mb-5">
            <div class="w-full container mx-auto flex flex-wrap items-center justify-center mt-0 px-2 py-3">

                <a class="uppercase tracking-wide no-underline hover:no-underline font-bold text-gray-800 text-4xl font-diph "
                    href="#">
                    Categories
                </a>


            </div>
        </nav>
        @if ($categories)
            <div class="flex flex-row space-x-2 space-y-2 w-full p-6   justify-center   flex-wrap">


                @foreach ($categories as $category)
                    <div
                        class="w-full md:w-1/3 xl:w-1/4 p-6 text-center border border-gray-200 rounded-lg shadow hover:bg-gray-100  dark:border-gray-700 dark:hover:bg-gray-700 dark:hover:text-white">
                        <a href="/category/{{ $category->id }}" class="text-center ">

                            @if (file_exists('storage/' . $category->image))
                                <?php $image = asset('storage/' . $category->image); ?>;
                            @else
                                <?php $image = $category->image; ?>
                            @endif

                            <img class="hover:grow hover:shadow-lg " width="200" height="200"
                                style="display: inline; max-height:200px; height:200px" src="{{ $image }}">


                            <div class="pt-3 flex items-center justify-center justify-center font-cairo">
                                <p class="text-center " style="font-size: 25px; font-weight:bold">{{ $category->name }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach

            </div>
        @endif


    </div>
    @if (isset($showAll) && $showAll)
        <div class="container w-full flex justify-center">
            <br>
            {{ $categories->links() }}
        </div>
    @else
        <a href="all-categories" class="flex justify-center">

            <button
                class=" font-cairo bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                اظهار الكل
            </button>
        </a>
    @endif
</section>

<!-- End  categories -->
@if (isset($showAll) && $showAll)
    @include('shop.footer')
@endif
