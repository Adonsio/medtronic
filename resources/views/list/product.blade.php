<?php

?>
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organisations') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 product-table">
                    <table class="table-auto w-full text-left overflow-hidden overflow-x-auto">
                        <thead>
                        <tr>
                            <th class="w-auto ">Supplier ID</th>
                            <th class="w-auto ">Product #</th>
                            <th class="w-auto ">Product Description</th>
                            <th class="w-auto ">Unit/Packiging</th>
                            <th class="w-auto ">Gross Price Package</th>
                            <th class="w-auto ">Applicable Rebate</th>
                            <th class="w-auto ">Net Price Package</th>
                            <th class="w-auto ">Price Unit</th>
                            <th class="w-auto ">Product Group</th>
                            <th class="w-auto ">Supplier Name</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr class=" @if(($product->id % 2) == 0) bg-gray-100 @endif">
                                <td>{{$product->supplier_id}}</td>
                                <td>{{$product->product_id}}</td>
                                <td>{{$product->desc}}</td>
                                <td>{{$product->unit}}</td>
                                <td>{{$product->price}}</td>
                                <td>{{number_format((float)$product->rabatt, 2, '.', '')*100 }}%
                                </td>
                                <td>{{number_format((float)$product->price * $product->rabatt, 2, '.', '') }}€</td>
                                <td>{{number_format((float)($product->price * $product->rabatt)/$product->unit, 2, '.', '') }}€</td>
                                <td>{{$product->group}}</td>
                                <td>{{$product->supplier_name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

