
@extends('welcome')
@section('content')
<div class="flex justify-center w-full p-10">
{{-- <h1>{{__('message.create_shop')}}</h1> --}}
    <div class="w-full max-w-xs">
        <form class="px-8 pt-6 pb-8 mb-4 bg-white rounded shadow-md" method="post" action="{{route('create-shop')}}">
@csrf
            <div class="mb-4">
                <label class="block mb-2 text-sm font-bold text-gray-700" for="shopName">
                Shop Name
              </label>
              <input class="w-full px-3 py-2 mb-3 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="shopName" name="shop_name" type="text" placeholder="Shop Name">
              {{-- <p class="text-xs italic text-red-500">Please choose a name for your shop.</p> --}}
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-bold text-gray-700" for="email">
                    E-mail
                </label>
                <input class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="email" type="text" name="email" placeholder="Email">
            </div>
      <div class="mb-4">
          <label class="block mb-2 text-sm font-bold text-gray-700" for="password">
          Password
        </label>
        <input class="w-full px-3 py-2 mb-3 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline" id="password" type="password" name="password" placeholder="******************">
        {{-- <p class="text-xs italic text-red-500">Please choose a password.</p> --}}
    </div>




    <div class="flex items-center justify-between">
        <button class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline" type="submit">
            Create Shop
        </button>
        {{-- <a class="inline-block text-sm font-bold text-blue-500 align-baseline hover:text-blue-800" href="#">
            Forgot Password?
        </a> --}}
    </div>
</form>
    <p class="text-xs text-center text-gray-500">
      &copy;2020 Acme Corp. All rights reserved.
    </p>
  </div>

</div>
  @endsection
