<style>
    .product-table { overflow-x: scroll; }
    th, td { min-width: auto; }

    tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .09);
    }

    .product-table td{
        padding: 4px;
    }
    .product-table {
        width: 100vw;
    }
    .product-table th {
        text-align: left;
    }
</style>

<div style="position: relative;">
<div class="flex p-4">
    <div class="w-full">Julian Selz<span class="text-right" style="position: absolute; right: 4px">{{$supplier->name}}</span></div>
</div>
<div class="flex p-4">
    <div class="w-full">Haldenstrasse 28<span class="text-right" style="position: absolute; right: 4px">{{ $supplier->street }}</span></div>
</div>
<div class="flex p-4">
    <div class="w-full">8425 Oberembrach<span class="text-right" style="position: absolute; right: 4px">{{ $supplier->zip_code }} / {{$supplier->city}}</span></div>
</div>
    <br>
    <br>
<div class="flex p-4">
    <div class="w-full">TVA XXX<span class="text-right" style="position: absolute; right: 4px">Date {{ \Carbon\Carbon::now('GMT+1')->format('d.m.Y')}}</span></div>
</div>
    <br>
    <br>
<div class="flex p-4">
    <div class="w-1/2 " style="font-weight: bold">Bon de Commande			</div>
    <div class="w-1/2 text-right"></div>
</div>

<div class="flex p-4">
    <div class="w-1/2">Adresse de livraison : {{$data['site']}}		</div>
    <div class="w-1/2 text-right"></div>
</div>

<div class="flex p-4">
    <div class="w-1/2">Personne de référence CIC : {{$data['fullname']}}		</div>
</div>

<div class="flex p-4">
    <div class="w-1/2">Montant Total HT : {{ $data['price'] }}	</div>
</div>
    <br><br>
<table style="border: 1px solid black;" class="product-table">
    <thead>
    <tr>
        <th>No Article</th>
        <th>Description</th>
        <th>Unité</th>
        <th>Prix net/Unité</th>
        <th>Quantité commandé</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>#{{$order->product_id}}</td>
            <td>{{$order->desc}}</td>
            <td>{{$order->unit}}</td>
            <td>{{$order->net_price}}</td>
            <td>{{$order->quantity}}</td>
            <td>{{(number_format((float)$order->price * $order->rabatt, 2, '.', '') * $order->quantity)}} €</td>
        </tr>
    @endforeach

    </tbody>

</table>

</div>