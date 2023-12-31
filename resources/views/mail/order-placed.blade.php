<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@500&display=swap');

    * {
        font-family: 'Cairo', sans-serif;
    }

    .alert {
        padding: 5px;
        border: 1px solid rgb(7, 68, 7);
        background-color: rgb(159, 218, 159);
        color: white
    }

    .customer {
        padding: 5px;
        border: 1px solid rgb(7, 68, 7);
        background-color: rgb(255, 255, 255);
        color: rgba(17, 17, 15, 0.849)
    }
</style>

<div class="alert " role="alert">
    <b>Vous avez une nouvelle commande 😊</b>
</div>
@if ($order->notes)
    <div>
        <h3>Observation client</h3>
        <p>
            {{ $order->notes }}
        </p>
    </div>
@endif
<div class="customer " role="alert">
    <h3>Produit :
    </h3>
    <b>Nom : </b> {{ $productName }}
    <br>
    <b>Qte : </b> {{ $quantity }}
    <br>
    <b>Cout de Livraison : </b>
    {{ $order->shipping_price }}
    <br>
    <b>Prix Total: </b> {{ $totalPrice }}
</div>

<div class="customer " role="alert">
    <h3>Client :
    </h3>
    <b>Nom : </b> {{ $order->customer->name }}
    <br>
    <b>Address : </b> {{ $order->customer->address }} - {{ $order->customer->city }}
    <br>
    <b>contact : </b><a href="tel:{{ $order->customer->phone }}"> {{ $order->customer->phone }}</a>
    <br>

    <b>Livraison : </b>

    @if ($order->shipping_type == 'desk')
        Stop Desk
    @else
        Domicile
    @endif



</div>
