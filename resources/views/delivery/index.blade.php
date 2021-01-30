<?php

?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Outstanding Deliverys') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <outstanding-delivery></outstanding-delivery>
                    <br>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200 product-table">
                            <table class="table-auto w-full text-left overflow-hidden overflow-x-auto" style="white-space: nowrap;" >
                                <thead>
                                <tr>
                                    <th class="w-auto ">Ordering Person</th>
                                    <th class="w-auto ">Supplier Name</th>
                                    <th class="w-auto ">Product #</th>
                                    <th class="w-auto ">Product Description</th>
                                    <th class="w-auto ">Unit/Packiging</th>
                                    <th class="w-auto ">Gross Price Package</th>
                                    <th class="w-auto ">Applicable Rebate</th>
                                    <th class="w-auto ">Net Price Package</th>
                                    <th class="w-auto ">Price Unit</th>
                                    <th class="w-auto ">Order Packages</th>
                                    <th class="w-auto ">Total Units Ordered</th>
                                    <th class="w-auto ">Total Price</th>
                                    <th class="w-auto ">Product Group</th>
                                    <th class="w-auto ">Department</th>
                                    <th class="w-auto ">Site</th>
                                    <th class="w-auto ">Reciving Person</th>
                                    <th class="w-auto ">Complete Delivery</th>
                                    <th style="display: inline-block; width: 100%; ">Partial Delivery</th>

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deliveries as $delivery)
                                        <tr>
                                            @php
                                                $user = \App\Models\User::where('id', $delivery->user_id)->first();
                                            @endphp
                                            <td>
                                                {{ $user->fullname }}
                                            </td>
                                            <td>{{$delivery->supplier_name}}</td>
                                            <td>{{$delivery->product_id}}</td>


                                            <td>{{$delivery->desc}}</td>
                                            <td>{{$delivery->unit}}</td>
                                            <td>{{$delivery->price}}</td>
                                            <td>{{number_format((float)$delivery->rabatt, 2, '.', '')*100 }}%
                                            </td>
                                            <td>{{number_format((float)$delivery->net_price, 2, '.', '') }}€</td>
                                            <td>{{number_format((float)$delivery->price_unit, 2, '.', '') }}€</td>
                                            <td>{{ $delivery->quantity }}</td>
                                            <td>{{ ($delivery->unit * $delivery->quantity) }}</td>
                                            <td>{{ number_format((float)$delivery->total_price, 2, '.', '') }}€</td>
                                            <td>{{$delivery->group}}</td>
                                            <td>{{ $delivery->department}}</td>
                                            <td>{{$delivery->site}}</td>
                                            <td>{{$delivery->reciving_person ? $user->fullname : ''}}</td>
                                            <td>@if($delivery->c_date) {{$delivery->c_date}} @else <a href="{{url('/delivery/complete/'.$delivery->id)}}" class="font-bold">Set Complete</a> @endif</td>
                                            <td style="display: inline-block;">@if($delivery->p_date)
                                                <a href="{{url('/delivery/partial/'.$delivery->id)}}" class="font-bold ">Add Partial</a>

                                                @else <a href="{{url('/delivery/partial/'.$delivery->id)}}" class="font-bold ">Set Partial</a>@endif
                                            </td>
                                        </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
