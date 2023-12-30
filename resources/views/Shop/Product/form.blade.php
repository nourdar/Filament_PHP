<style type="text/css">
    .form-style-9 {
        max-width: 100%;
        background: #FAFAFA;
        padding: 30px;
        margin: 50px auto;
        box-shadow: 1px 1px 25px rgba(0, 0, 0, 0.35);
        border-radius: 10px;
        border: 6px solid #305A72;
    }

    .form-style-9 ul {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .form-style-9 ul li {
        display: block;
        margin-bottom: 10px;
        min-height: 35px;
    }




    .delivery-selected {
        border: #425970 3px solid;
        padding: 0px 5px 0px 5px;



    }

    form,
    form ul,
    form ul li,
    form ul li input,
    form ul li label,
    form ul li select,
    form ul li select option {
        direction: rtl;
    }

    form ul li select {
        padding-right: 40px !important;
    }

    .animate__shakeX {
        animation-duration: 4s;

    }

    .animated {
        animation-delay: 7s;
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
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400 text-center"
            role="alert">
            <span class="font-medium">{{ Session::get('message') }} </span>
        </div>
    @endif
@endif

<form class="form-style-9 font-cairo" method="POST" action="/place-order" dir="rtl">

    @csrf

    <ul>
        <li>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                type="text" name="name" class="field-style w-full  align-left" placeholder="الاسم كامل"
                value="{{ old('name') }}" />

        </li>
        <li>
            <input
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                type="tel" name="phone" class="field-style w-full align-left" placeholder="رقم الهاتف"
                value="{{ old('phone') }}" />
        </li>
        {{-- <li>
            <input type="text" name="second_phone" class="field-style w-full align-left"
                placeholder="Autre Numero de telephone" />
        </li> --}}



        <li>



            <div class="flex  gap-x-3  w-full flex-wrap ">
                <select name="wilaya" id="wilaya"
                    class="flex md:w-1/3 sm:w-full  appearance-none  bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">

                    <option disabled selected> الولاية </option>
                    @foreach ($wilayas as $wilayaCode => $wilayaName)
                        <option value="{{ $wilayaCode }}"> {{ $wilayaName }} </option>
                    @endforeach
                </select>
                <select name="commune" id="commune"
                    class="flex md:w-1/3 sm:w-full   appearance-none  bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">

                    <option disabled selected> البلدية </option>
                </select>

                @if ($product->mesures)
                    @foreach ($product->mesures as $mesure => $options)
                        <select type="text" name="{{ $mesure }}"
                            class="flex md:w-1/3 sm:w-full   appearance-none  bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline">


                            <option disabled selected> {{ $mesure }} </option>
                            @foreach ($options as $option)
                                <option value="{{ $option }}"> {{ $option }} </option>
                            @endforeach
                        </select>
                    @endforeach
                @endif

            </div>




        </li>








        <li>
            <div>
                <div class="flex w-full lg:gap-x-2 lg:flex-nowrap flex-wrap">




                    <span class="lg:w-1/3 w-full">
                        التوصيل ( YALIDINE ) :
                    </span>
                    <span id="homeDelivery" class="w-1/2 lg:w-1/3">
                        <input type="checkbox" checked id="homeCheck" class="hidden">
                        الى المنزل : <span id="HomeDeliveryFees"> - </span> دج
                    </span>
                    <span id="deskDelivery" class="delivery-selected w-1/2 lg:w-1/3">
                        <input type="checkbox" checked id="deskCheck" class="d-block">
                        الى المكتب : <span id="DeskDeliveryFees"> - </span> دج<br>
                    </span>



                </div>
                <input type="hidden" name="delivery_type" id="deliveryType" value="">
                <input type="hidden" name="delivery_fees" id="deliveryFees" value="">

                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="unit_price" value="{{ $product->price }}">

            </div>
        </li>
        <li>

            <textarea
                class="flex w-full   appearance-none  bg-white border border-gray-400 hover:border-gray-500 px-4 py-2 pr-8 rounded shadow leading-tight focus:outline-none focus:shadow-outline"
                name="note" cols="30" rows="3" placeholder="ملاحظة"></textarea>

        </li>


        <li>
            <div class="dark:bg-slate-900 p-4 flex justify-between">
                <!-- Input Number -->
                <div class="md:w-1/5 lg:w-1/4 sm:w-full py-2 px-2 h-25 items-center justify-center bg-white border border-gray-200 rounded-lg dark:bg-slate-900 dark:border-gray-700"
                    data-hs-input-number>
                    <div class=" flex justify-center items-center gap-x-3">

                        <div class="flex items-center gap-x-1.5">
                            <button type="button" id="quantityDecrement"
                                class="w-6 h-6 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-md border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                data-hs-input-number-decrement>
                                <svg class="flex-shrink-0 w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                </svg>
                            </button>
                            <input
                                class="p-0 w-6 bg-transparent border-0 text-gray-800 text-center focus:ring-0 dark:text-white"
                                type="text" name="quantity" id="quantity" value="{{ old('quantity', 1) }}"
                                data-hs-input-number-input>
                            <button type="button" id="quantityIncrement"
                                class="w-6 h-6 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-md border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                data-hs-input-number-increment>
                                <svg class="flex-shrink-0 w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                    <path d="M12 5v14" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- End Input Number -->
                <button type="submit"
                    class="animated animate__animated animate__shakeX animate__infinite  font-cairo w-1/2  text-xl  p-0  bg-transparent border-0 text-gray-800 text-center focus:ring-0 dark:text-white">
                    تأكيد الطلب <span id="totalPrice">{{ $product?->price }}</span> دج
                </button>
            </div>
        </li>
    </ul>
</form>



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
            alert('danger');
        }




    });

    function calculateDeliveryFees() {

        var wilaya = $('#wilaya').val();

        $.ajax({
            url: "{{ url('/yalidine-delivery-fees/') }}/" + wilaya,
            type: "GET",
            dataType: "json",
            success: function(data) {

                homeDelivery = data.data[0].home_fee
                deskDelivery = data.data[0].desk_fee

                $('#HomeDeliveryFees').text((homeDelivery).toLocaleString('fr'))

                $('#DeskDeliveryFees').text((deskDelivery).toLocaleString('fr'))

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
            $('#totalPrice').text(((productPrice * quantity) + deskDelivery).toLocaleString('fr'))
        } else {
            $('input[name="delivery_fees"]').val(homeDelivery)
            $('input[name="delivery_type"]').val('home')
            $('#totalPrice').text(((productPrice * quantity) + homeDelivery).toLocaleString('fr'))
        }
    }
</script>
