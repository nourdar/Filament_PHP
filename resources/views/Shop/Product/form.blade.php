<div dir="rtl">


    <a href="#form"  class="fixed z-10 m-auto bottom-3 left-2 " >

        <button type="submit"
        wire:click='save'
        @if ($loader)
              {{ 'disabled'}}

              @endif
        class="p-4 text-xl text-center text-white bg-green-700 border-0 rounded bg-btn-primary animated animate__animated animate__shakeX animate__infinite outline font-cairo focus:ring-0">
        تأكيد الطلب <span class="totalPrice" dir="ltr">{{   number_format($totalPrice, '0', ',', ' ') }}</span> دج
    </button>
    </a>

<style type="text/css">

    .animate__shakeX , .animate__shakeY{
        animation-duration: 6s;

    }

    .animated {
        animation-delay: 1s;
    }




</style>


    @if ($sucessMessage)
        <div class="flex items-center justify-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
            role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
            </svg>
            <span class="sr-only">Info</span>
            <div>
                <span class="font-medium"> تم تسجيل الطلب بنجاح
            </div>
        </div>
        @endif
    @if($errorMessage)
        <div class="p-4 mb-4 text-sm text-center text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
            role="alert">
            <span class="font-medium">لم تتم العملية بنجاح الرجاء التاكد من المعلومات</span>
        </div>
    @endif





<div
id="form"
      class="flex flex-col items-center justify-center w-full p-4 checkout-form"
>

      <!-- Form - Start -->
      <div class="flex flex-col items-center justify-center full-form">
        <form
        wire:submit="save"

          class="flex flex-col items-center justify-center w-full gap-1 bg-white form"
        >
          <!-- Name and Phone - Start -->

          <div class="flex gap-1 name-number">

                <div class="w-1/2 full_name fields">
              <label for="name" class="label">الاسم الكامل</label>
              <input type="text" id="name_input" name="name" class="mt-0.5" dir="rtl"  wire:model="name"   />
              @error('name') <span class="text-red-500 error">{{ $message }}</span> @enderror
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
                @error('phone') <span class="text-red-500 error">{{ $message }}</span> @enderror
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






          @if($mesuresList)
          <div class="flex gap-1 selectors">
              @foreach ($mesuresList as $mesure => $options )

              @if(isset($options[0]))
            <div class="w-1/3 fields ">
              {{-- <label  class="label">{{$mesure}}</label> --}}
              <select name="{{$mesure}}" id="select_wilaya" wire:model="mesures.{{ $mesure }}" wire:ignore  class="mt-0.5 p-3" dir="rtl" >
                @foreach ($options as $option )
                <option value="{{$option}}"> {{$option}} </option>
                @endforeach
                <option    value="" disabled>{{ $mesure }}</option>
            </select>

        </div>
        @endif
            @endforeach
          </div>

          @endif
          <!-- Selectors - End -->





          <div class="flex gap-1 name-number">



            <div class="w-full fields">
            <label class="label" for="notes">ملاحظة</label>

            <textarea name="notes" id="notes" wire:model='notes' cols="10"  class="w-full"></textarea>
          </div>

      </div>


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
              <input type="checkbox" id="liv_bureau_checkbox" wire:model='home' wire:change='homeChecked'  />
              <p>التوصيل للمنزل</p>
            </div>
          </div>

          <!-- Livraison tarifs - End -->

          <!-- Quantity - Start -->

          <div class="flex w-full gap-2 quantity-submit">
            <input

            {{-- wire:click='save' --}}
              type="submit"
              wire:loading.remove
              id="submit"
              @if ($loader)
              {{ 'disabled'}}

              @endif
              value="أنقر هنا لتأكيد الطلب"
              class="font-semibold text-white bg-green-700 hover:bg-green-600 bg-btn-primary"
            />

            <input wire:loading.block
            class="font-semibold text-white bg-green-700 hover:bg-green-600 bg-btn-primary"
            value="                جاري تسجيل الطلب
            "
            />


            <div
              class="flex items-center justify-between w-full h-full text-white quantity"
            >
              <button class="h-full text-black sub_quantity" wire:click="decrementQuantity" type="button">
                <span><svg xmlns="http://www.w3.org/2000/svg" fill=" {{ $settings->style[0]['bg-btn-primary'] .' !important' ?? ' #388E3C' }} " viewBox="0 0 20 20"><path d="M10 20a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm5-11H5v2h10V9z"/></svg></span>
              </button>
              <div class="text-xl text-black quantity_number text-semibold">
                {{ $quantity }}
              </div>
              <button class="h-full text-black add_quantity" wire:click="incrementQuantity"  type="button">
                <span id="add" class="w-full h-full ">
                    <svg xmlns="http://www.w3.org/2000/svg"  fill="{{ $settings->style[0]['bg-btn-primary'] .' !important' ?? ' #388E3C' }}" class="bi bi-plus-circle-fill" viewBox="0 0 16 16"> <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z"/> </svg>
                </span>
              </button>
            </div>
          </div>
          <!-- Quantity - End -->
        </form>
        <div class="order-summary font-cairo">
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
              <div class="px-4 details-row"  dir="rtl">
                <div class="text-sm product-title text-wrap">سعر المنتج</div>
                <div>
                    <span class="p-1 ml-3 mr-3 text-white bg-green-700 rounded bg-btn-primary text-bold" > X <span class="quantity_number" >  {{ $quantity }}</span>
                </span>

                    <span dir="ltr" class="total_price ">{{ number_format($price, '0', ',', ' ') }}
                        دج</span>
                </div>

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
                <div>سعر التوصيل</div>
                <div dir="ltr">{{ $deliveryPrice }} دج</div>
              </div>
            </div>
            <div style="width: 100%; height: 50px" class="flex flex-col">
              <div class="px-4 details-row" dir="rtl">
                <div>السعر الكلي</div>
                <div dir="ltr">{{ number_format($totalPrice, '0', ',', ' ') }} دج</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Form - End -->


    </div>







</div>




