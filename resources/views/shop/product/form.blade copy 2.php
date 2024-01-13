<style type="text/css">
    .form-style-9 {
        max-width: 100%;
        background: #FAFAFA;
        padding: 30px;
        margin: 50px auto;
        box-shadow: 1px 1px 25px rgba(0, 0, 0, 0.35);
        border-radius: 10px;
        border: 6px solid rgb(15 23 42 / var(--tw-bg-opacity));


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

<form class="mt-2 form-style-9 font-cairo" method="POST" action="/place-order" dir="rtl" id="form">

    @csrf

    <ul>
        <li>
            <input
                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                type="text" name="name" class="w-full field-style align-left" placeholder="الاسم كامل"
                value="{{ old('name') }}" />

        </li>
        <li>
            <input
                class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                type="tel" name="phone" class="w-full field-style align-left" placeholder="رقم الهاتف"
                value="{{ old('phone') }}" />
        </li>
        {{-- <li>
            <input type="text" name="second_phone" class="w-full field-style align-left"
                placeholder="Autre Numero de telephone" />
        </li> --}}



        <li>



            <div class="flex flex-wrap w-full lg:gap-x-3 ">
                <select name="wilaya" id="wilaya"
                    class="flex px-4 py-2 pr-8 mt-2 leading-tight bg-white border border-gray-400 rounded shadow appearance-none md:w-1/3 sm:w-1/2 hover:border-gray-500 focus:outline-none focus:shadow-outline">

                    <option disabled selected> الولاية </option>
                    @foreach ($wilayas as $wilayaCode => $wilayaName)
                        <option value="{{ $wilayaCode }}"> {{ $wilayaName }} </option>
                    @endforeach
                </select>
                <select name="commune" id="commune"
                    class="flex px-4 py-2 pr-8 mt-2 leading-tight bg-white border border-gray-400 rounded shadow appearance-none md:w-1/3 sm:w-1/2 hover:border-gray-500 focus:outline-none focus:shadow-outline">

                    <option disabled selected> البلدية </option>
                </select>


            </div>



            @if (isset($product->mesures[0]))


                <div class="flex flex-wrap w-full mt-3 mb-2 gap-x-3">

                    @foreach ($product->mesures[0] as $mesure => $options)
                        @if (count($options) > 0)
                            <select type="text" name="{{ $mesure }}"
                                class="flex px-4 py-2 pr-8 leading-tight bg-white border border-gray-400 rounded shadow appearance-none md:w-1/3 sm:w-full hover:border-gray-500 focus:outline-none focus:shadow-outline">


                                <option disabled selected> {{ $mesure }} </option>
                                @foreach ($options as $option)
                                    <option value="{{ $option }}"> {{ $option }} </option>
                                @endforeach
                            </select>
                        @endif
                    @endforeach

                </div>
            @endif




        </li>








        <li>
            <div>
                <div class="flex flex-wrap w-full lg:gap-x-2 lg:flex-nowrap">




                    <span class="w-full mb-2 lg:w-1/3">
                        التوصيل : <span id="DeliveryProvider"></span>
                    </span>
                    <span id="homeDelivery" class="w-1/2 mb-2 lg:w-1/3">
                        <input type="checkbox" checked id="homeCheck" class="hidden">
                        الى المنزل : <span id="HomeDeliveryFees"> - </span> دج
                    </span>
                    <span id="deskDelivery" class="w-1/2 mb-2 delivery-selected lg:w-1/3">
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
                class="flex w-full px-4 py-2 pr-8 leading-tight bg-white border border-gray-400 rounded shadow appearance-none hover:border-gray-500 focus:outline-none focus:shadow-outline"
                name="note" cols="30" rows="3" placeholder="ملاحظة"></textarea>

        </li>


        <li>
            <div class="flex p-4 dark:bg-slate-900 gap-x-4">
                <!-- Input Number -->
                <div class="flex items-center justify-center px-2 py-2 bg-white border border-gray-200 rounded-lg md:w-1/5 lg:w-1/4 sm:w-full sm:h-10 lg:h-25 dark:bg-slate-900 dark:border-gray-700"
                    data-hs-input-number>
                    <div class="flex items-center justify-center gap-x-3">

                        <div class="flex items-center gap-x-1.5">
                            <button type="button" id="quantityDecrement" aria-label="decrement quantity button"
                                class="inline-flex items-center justify-center w-6 h-6 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-md shadow-sm gap-x-2 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
                                data-hs-input-number-decrement>
                                <svg class="flex-shrink-0 w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14" />
                                </svg>
                            </button>
                            <input
                                class="w-6 p-0 text-center text-gray-800 bg-transparent border-0 focus:ring-0 dark:text-white"
                                type="text" name="quantity" id="quantity" value="{{ old('quantity', 1) }}"
                                data-hs-input-number-input>
                            <button type="button" id="quantityIncrement" aria-label="increment quantity button"
                                class="inline-flex items-center justify-center w-6 h-6 text-sm font-medium text-gray-800 bg-white border border-gray-200 rounded-md shadow-sm gap-x-2 hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600"
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
                    class="w-1/2 p-2 text-xl text-center text-white border-0 rounded animated animate__animated animate__shakeX animate__infinite outline font-cairo bg-slate-900 focus:ring-0">
                    تأكيد الطلب <span class="totalPrice">{{ $product?->price }}</span> دج
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
