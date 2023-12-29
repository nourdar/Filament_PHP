<style type="text/css">
    .form-style-9 {
        max-width: 97%;
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

    .form-style-9 ul li .field-style {
        box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        padding: 8px;
        outline: none;
        border: 1px solid #B0CFE0;
        -webkit-transition: all 0.30s ease-in-out;
        -moz-transition: all 0.30s ease-in-out;
        -ms-transition: all 0.30s ease-in-out;
        -o-transition: all 0.30s ease-in-out;

    }

    .form-style-9 ul li .field-style:focus {
        box-shadow: 0 0 5px #B0CFE0;
        border: 1px solid #B0CFE0;
    }

    .form-style-9 ul li .field-split {
        width: 49%;
    }

    .form-style-9 ul li .field-full {
        width: 100%;
    }

    .form-style-9 ul li input.align-left {
        float: left;
    }

    .form-style-9 ul li input.align-right {
        float: right;
    }

    .form-style-9 ul li textarea {
        width: 100%;
        height: 100px;
    }

    .form-style-9 ul li input[type="button"],
    .form-style-9 ul li input[type="submit"] {
        -moz-box-shadow: inset 0px 1px 0px 0px #3985B1;
        -webkit-box-shadow: inset 0px 1px 0px 0px #3985B1;
        box-shadow: inset 0px 1px 0px 0px #3985B1;
        background-color: #216288;
        border: 1px solid #17445E;
        display: inline-block;
        cursor: pointer;
        color: #FFFFFF;
        padding: 8px 18px;
        text-decoration: none;
        font: 16px Arial, Helvetica, sans-serif;
        margin: 0px auto;
    }

    .form-style-9 ul li input[type="button"]:hover,
    .form-style-9 ul li input[type="submit"]:hover {
        background: linear-gradient(to bottom, #2D77A2 5%, #337DA8 100%);
        background-color: #28739E;
    }

    .delivery-selected {
        border: green 3px solid;
        padding: 2px 10px 2px 10px;



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
            <input type="text" name="name" class="field-style w-full  align-left" placeholder="الاسم كامل"
                value="{{ old('name') }}" />

        </li>
        <li>
            <input type="tel" name="phone" class="field-style w-full align-left" placeholder="رقم الهاتف"
                value="{{ old('phone') }}" />
        </li>
        {{-- <li>
            <input type="text" name="second_phone" class="field-style w-full align-left"
                placeholder="Autre Numero de telephone" />
        </li> --}}



        <li>
            <select type="text" id="wilaya" name="wilaya" class="field-style field-full  align-none">


                <option disabled selected> الولاية </option>
                @foreach ($wilayas as $wilayaCode => $wilayaName)
                    {{-- <option value="{{ $wilayaCode }}" {{ old('wilaya') == $wilayaCode ? 'selected' : '' }}>
                        {{ $wilayaName }}</option> --}}
                    <option value="{{ $wilayaCode }}"> {{ $wilayaName }} </option>
                @endforeach
            </select>

            <select type="text" id="commune" name="commune" class="field-style field-full  align-none">
                <option disabled selected> البلدية </option>
            </select>
        </li>


        <li class="mt-2">
            @if ($product->mesures)
                @foreach ($product->mesures as $mesure => $options)
                    <select type="text" name="{{ $mesure }}" class="field-style field-full align-none">
                        <option disabled selected> {{ $mesure }} </option>
                        @foreach ($options as $option)
                            <option value="{{ $option }}"> {{ $option }} </option>
                        @endforeach
                    </select>
                @endforeach
            @endif

        </li>

        <li class="text-left">
            <label for="quantity" class="w-full text-xl text-left ml-5 "> الكمية :</label>
            <input type="number" name="quantity" id="quantity" class="field-style field-split align-left"
                placeholder="الكمية" value="{{ old('quantity', 1) }}" />

        </li>



        <li>
            <div>
                <div style="line-height: 3; padding : 5px ">التوصيل ( YALIDINE ) :<br>
                    <span id="homeDelivery">
                        <input type="checkbox" checked id="homeCheck" class="hidden">
                        الى المنزل : <span id="HomeDeliveryFees"> - </span> دج<br>
                    </span>
                    <span id="deskDelivery" class="delivery-selected">
                        <input type="checkbox" checked id="deskCheck" class="d-block">
                        الى المكتب : <span id="DeskDeliveryFees"> - </span> دج<br>
                    </span>

                    <input type="hidden" name="delivery_type" id="deliveryType" value="">
                    <input type="hidden" name="delivery_fees" id="deliveryFees" value="">

                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="unit_price" value="{{ $product->price }}">


                </div>
                <h2 class="text-xl"><strong> المجموع : </strong> <span id="totalPrice">{{ $product?->price }}</span> دج
                </h2>
            </div>
        </li>
        <li>

            <textarea class="field-style " name="note" cols="30" rows="10" placeholder="ملاحظة"></textarea>

        </li>


        <li>
            <input type="submit" class="font-cairo" value="تأكيد الطلب" />
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
