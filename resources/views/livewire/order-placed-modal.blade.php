<div>
    @if($success)
    <div role="success">
        <div class="px-4 py-2 text-xl font-bold text-center text-white bg-green-500 rounded-t">
          {{__('messages.order_placed_success_title')}}
        </div>
        <div class="p-10 text-lg text-green-700 border border-t-0 border-green-400 rounded-b">
          <p>{{__('messages.order_placed_success_message')}}</p>
        </div>
      </div>
      @else
      <div role="alert">
        <div class="px-4 py-2 text-xl font-bold text-center text-white bg-red-500 rounded-t">
          {{__('messages.order_placed_error_title')}}
        </div>
        <div class="p-10 text-lg text-red-700 bg-red-100 border border-t-0 border-red-400 rounded-b">
          <p>{{__('messages.order_placed_error_message')}}</p>
        </div>
      </div>
      @endif
</div>
