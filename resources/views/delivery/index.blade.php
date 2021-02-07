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
                                    <th class="w-auto ">Donneur d'ordre
                                    </th>
                                    <th class="w-auto ">Nom Fournisseur
                                    </th>
                                    <th class="w-auto "># Produit
                                    </th>
                                    <th class="w-auto ">Description Produit
                                    </th>
                                    <th class="w-auto ">Unité
                                    </th>
                                    <th class="w-auto ">Prix brut
                                    </th>
                                    <th class="w-auto ">Rabais
                                    </th>
                                    <th class="w-auto ">Prix net
                                    </th>
                                    <th class="w-auto ">Prix/Unité
                                    </th>
                                    <th class="w-auto ">Commander des packages</th>
                                    <th class="w-auto ">Total Units Ordered</th>
                                    <th class="w-auto ">Nombre total d'unités commandées</th>
                                    <th class="w-auto ">Product Group</th>
                                    <th class="w-auto ">Groupe de produits
                                    </th>
                                    <th class="w-auto ">Site</th>
                                    <th class="w-auto ">Personne recevante</th>
                                    <th class="w-auto ">Livraison complète</th>
                                    <th style="display: inline-block; width: 100%; ">Livraison partielle</th>

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
<style>
    th{
        padding-left: 5px;
    }
    td {
        padding-left: 5px;
    }
</style>