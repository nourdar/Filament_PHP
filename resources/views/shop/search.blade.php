@include('shop.header')
<div class="container">

    @if ($searchResults)

        @foreach ($searchResults->groupByType() as $type => $modelSearchResults)
            @if ($type == 'products')
            @endif


            @if ($type == 'brands')
            @endif


            @if ($type == 'categories')
            @endif

            <h2>{{ $type }}</h2>

            @foreach ($modelSearchResults as $searchResult)
                <ul>
                    <a href="{{ $searchResult->url }}">{{ $searchResult->title }}</a>
                </ul>
            @endforeach
        @endforeach
    @else
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 text-center"
            role="alert">
            <span class="font-medium">لا يوجد اي نتجة</span>
        </div>



</div>





@include('shop.footer')
