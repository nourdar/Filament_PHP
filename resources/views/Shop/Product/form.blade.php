<div dir="rtl">
<style type="text/css">

    .animate__shakeX , .animate__shakeY{
        animation-duration: 6s;

    }

    .animated {
        animation-delay: 1s;
    }
</style>
@if (Session::has('message'))
    {{-- <p class="alert text-center text-xl text-bold {{ Session::get('alert-class', 'alert-info') }}">
        {{ Session::get('message') }}
    </p> --}}
    @if (Session::get('alert-class') == 'alert-success')
        <div class="flex items-center justify-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium"> {{ Session::get('message') }}
            </div>
        </div>
    @else
        <div class="p-4 mb-4 text-sm text-center text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <span class="font-medium">{{ Session::get('message') }} </span>
        </div>
    @endif
@endif






<div
      class="flex flex-col items-center justify-center w-full p-4 checkout-form"
    >
      <!-- Form - Start -->
      <div class="flex flex-col items-center justify-center full-form">
        <form
        wire:submit="save"
          action=""
          class="flex flex-col items-center justify-center w-full gap-1 bg-white form"
        >
          <!-- Name and Phone - Start -->

          <div class="flex gap-1 name-number">

            <div class="w-1/2 full_name fields">
              <label for="name" class="label">الاسم الكامل</label>
              <input type="text" id="name_input" name="name" class="mt-0.5" dir="rtl"  wire:model="name"   />
            </div>

            <div class="w-1/2 phone_number fields">
                <label class="label" for="phone">رقم الهاتف</label>
                <input
                  type="text"
                  id="phone_input"
                  name="phone"
                  class="mt-0.5"
                  maxlength="10"
                  wire:model="phone"

                  oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                  dir="rtl"
                />
              </div>

          </div>

          <!-- Name and Phone - End -->

          <!-- Selectors - Start -->

          <div class="flex gap-1 selectors">

            <div class="w-1/2 wilaya_selector fields ">


              <label for="wilaya" class="label">الولاية</label>

              <select name="wilaya" id="select_wilaya" wire:model="wilaya" wire:change='wilayaChanged'    class="mt-0.5 p-3"" dir="rtl">
                @foreach ($wilayasList as $wilayaCode => $wilayaName )

                    <option value="{{$wilayaCode}}"

                    >{{$wilayaName}}</option>
                @endforeach
              </select>
            </div>

            <div class="w-1/2 commune_selector fields">
                @if(!empty($communesList))
              <label for="commune" class="label">البلدية</label>

              <select
                name="commune"
                wire:model="commune"
                id="select_commune"
                class="mt-0.5"
                dir="rtl"
              >

              @foreach ($communesList as $communeName => $communeName )
              @if(!empty($communeName))
                    <option value="{{$communeName}}">{{$communeName}}</option>
                    @endif
                @endforeach

              </select>
              @endif
            </div>

          </div>

          <!-- Selectors - End -->

          <!-- Livraison tarifs - Start -->

          <div class="flex items-center gap-1 livraison_tarif fields">
            <div class="flex items-center w-1/3 gap-1">
                <h3 >شركة التوصيل :  {{ $deliveryCompany }}</h3>
            </div>
            <div class="flex items-center w-1/3 gap-1 liv-domicile livraison">
              <input type="checkbox" id="liv_domicile_checkbox" wire:model='desk' wire:change='deskChecked'   />

              <p>التوصيل للمكتب</p>
            </div>
            <div class="flex items-center w-1/3 gap-1 liv-bureau livraison">
              <input type="checkbox" id="liv_bureau_checkbox" wire:model="home" wire:change='homeChecked'   />
              <p>التوصيل للمنزل</p>
            </div>
          </div>

          <!-- Livraison tarifs - End -->

          <!-- Quantity - Start -->

          <div class="flex w-full gap-2 quantity-submit">
            <input
              type="submit"
              id="submit"
              value="أنقر هنا لتأكيد الطلب"
              class="font-semibold text-white bg-green-700 hover:bg-green-600"
            />
            <div
              class="flex items-center justify-between w-full h-full text-white quantity"
            >
              <button class="h-full text-black sub_quantity" wire:click="decrementQuantity" type="button">
                <span><svg xmlns="http://www.w3.org/2000/svg" fill="#388E3C" viewBox="0 0 20 20"><path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm5-11H5v2h10V9z"/></svg></span>
              </button>
              <div class="text-xl text-black quantity_number text-semibold">
                {{ $quantity }}
              </div>
              <button class="h-full text-black add_quantity" wire:click="incrementQuantity"  type="button">
                <span id="add" class="w-full h-full ">
                    <svg xmlns="http://www.w3.org/2000/svg"  fill="#388E3C" class="bi bi-plus-circle-fill" viewBox="0 0 16 16"> <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/> </svg>
                </span>
              </button>
            </div>
          </div>
          <!-- Quantity - End -->
        </form>
        <div class="order-summary">
          <div class="flex items-center justify-between rounded summary">
            <div class="px-4 text-lg arrow">
              <i class="fa-solid fa-chevron-down"></i>
            </div>
            <div class="px-4 font-semibold summary-title">
              <span>ملخص الطلب</span>
              <span class="cart-shopping"
                ><i class="fa-solid fa-cart-shopping"></i
              ></span>
            </div>
          </div>
          <div class="order-details" >
            <div
              style="
                width: 100%;
                height: 50px;
                border-bottom: 2px solid #3232325a;
              "
              class="flex flex-col"
            >
              <div class="px-4 details-row" dir="rtl">
                <h3 class="product-title">{{ $product->name }}</h3>
                <h3>
                    <span dir="ltr" class="total_price">{{ $price }}
                        دج</span>
                    <span class="pl-2 pr-2 mr-2 text-white bg-green-700 rounded text-bold"> X <span class="quantity_number" >  {{ $quantity }}</span></span>
                </h3>

              </div>
            </div>
            <div
              style="
                width: 100%;
                height: 50px;
                border-bottom: 1px solid #3232325a;
              "
              class="flex flex-col"
            >
              <div class="px-4 details-row" dir="rtl">
                <h3>سعر التوصيل</h3>
                <h3 dir="ltr">{{ $deliveryPrice }} دج</h3>
              </div>
            </div>
            <div style="width: 100%; height: 50px" class="flex flex-col">
              <div class="px-4 details-row" dir="rtl">
                <h3>السعر الكلي</h3>
                <h3 dir="ltr">{{ $totalPrice }} دج</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Form - End -->
    </div>


</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script type="text/javascript">
    var productPrice = {{ $product->price }}
    var homeDelivery = 0
    var deskDelivery = 0
    var deliveryType = 'desk'

    var quantity = $('#quantity').val();
    var quantityIncrement = $('#quantityIncrement');
    var quantityDecrement = $('#quantityDecrement');



    $(document).on('change', '#wilaya', function() {

        var WilayaID = $(this).val();
        if (WilayaID) {
            $.ajax({
                url: "{{ url('/communs/') }}/" + WilayaID,
                type: "GET",
                dataType: "json",
                success: function(data) {
                    $('select[name="commune"]').html('');
                    var d = $('select[name="commune"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="commune"]').append(
                            '<option value="' + value + '">' + value +
                            '</option>');
                    });


                    calculateDeliveryFees()
                },
            });
        } else {
            alert('Problem whie loading communs');
        }




    });

    function calculateDeliveryFees() {

        var wilaya = $('#wilaya').val();

        $.ajax({
            url: "{{ url('/calculate-delivery-fees/') }}/" + wilaya,
            type: "GET",
            dataType: "json",
            success: function(data) {

                homeDelivery = data.home_fee
                deskDelivery = data.desk_fee

                $('#HomeDeliveryFees').text((homeDelivery).toLocaleString('fr'))

                $('#DeskDeliveryFees').text((deskDelivery).toLocaleString('fr'))
                $('#DeliveryProvider').text(data.provider)

                calculateTotalPrice()

            },
        });

    }


    $('#homeDelivery').click(function() {

        if ($('#deskDelivery').hasClass('delivery-selected')) {

            $('#deskDelivery').removeClass('delivery-selected');
            $('#deskCheck').hide();
            $('#homeCheck').show();

            $(this).addClass('delivery-selected')

            deliveryType = 'home'

            calculateTotalPrice()

        }
    })


    $('#deskDelivery').click(function() {

        if ($('#homeDelivery').hasClass('delivery-selected')) {

            $('#homeDelivery').removeClass('delivery-selected')

            $(this).addClass('delivery-selected')

            $('#deskCheck').show();

            $('#homeCheck').hide();

            deliveryType = 'desk'

            calculateTotalPrice()
        }
    })

    quantityIncrement.on('click', function() {
        quantity++
        $('#quantity').val(quantity);
        calculateTotalPrice()
    })

    quantityDecrement.on('click', function() {
        if (quantity > 1) {
            quantity--
            $('#quantity').val(quantity);

            calculateTotalPrice()
        }
    })

    $('#quantity').on('change', function() {
        quantity = $(this).val();
        calculateTotalPrice()
    })

    function calculateTotalPrice() {
        if (deliveryType == 'desk') {
            $('input[name="delivery_fees"]').val(deskDelivery)
            $('input[name="delivery_type"]').val('desk')
            $('.totalPrice').text(((productPrice * quantity) + deskDelivery).toLocaleString('fr'))
        } else {
            $('input[name="delivery_fees"]').val(homeDelivery)
            $('input[name="delivery_type"]').val('home')
            $('.totalPrice').text(((productPrice * quantity) + homeDelivery).toLocaleString('fr'))
        }
    }
</script>
