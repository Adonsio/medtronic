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
                                        <tr class=" @if(($delivery->id % 2) == 0) bg-gray-100 @endif">
                                            @php
                                                $user = \App\Models\User::where('id', $delivery->user_id)->first();
                                            @endphp
                                            <td>
                                                {{ $user->fullname }}
                                            </td>
                                            <td>{{$delivery['product']['supplier']->name}}</td>
                                            <td>{{$delivery['product']->product_id}}</td>


                                            <td>{{$delivery['product']->desc}}</td>
                                            <td>{{$delivery['product']->unit}}</td>
                                            <td>{{$delivery['product']->price}}</td>
                                            <td>{{number_format((float)$delivery['product']->rabatt, 2, '.', '')*100 }}%
                                            </td>
                                            <td>{{number_format((float)$delivery['product']->price * $delivery['product']->rabatt, 2, '.', '') }}€</td>
                                            <td>{{number_format((float)($delivery['product']->price * $delivery['product']->rabatt)/$delivery['product']->unit, 2, '.', '') }}€</td>
                                            <td>{{ $delivery->quantity }}</td>
                                            <td>{{ ($delivery['product']->unit * $delivery->quantity) }}</td>
                                            <td>{{ (number_format((float)$delivery['product']->price * $delivery['product']->rabatt, 2, '.', '') * $delivery->quantity) }}€</td>
                                            <td>{{$delivery['product']->group}}</td>
                                            <td>{{ $user->department}}</td>
                                            <td>{{$user->site}}</td>
                                            <td>{{$delivery->reciving_person ? $user->fullname : ''}}</td>
                                            <td>@if($delivery->c_date) {{$delivery->c_date}} @else <a href="{{url('/delivery/complete/'.$delivery->id)}}" class="font-bold">Set Complete</a> @endif</td>
                                            <td style="display: inline-block;">@if($delivery->p_date)
                                                <a href="{{url('/delivery/partial/'.$delivery->id)}}" class="font-bold ">Add Partial</a>
                                                @foreach($delivery->Partial as $item)
                                                        <span class="bg-blue-200 rounded-full py-1 px-3 m-2"> {{$item->date}}  /  {{ $item->person }}</span>

                                                @endforeach
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
