<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Récapitulatif des commandes groupées') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <a href="{{ url('/coupon/bulk/create')}}"><button class="bg-blue-500 p-4 rounded text-white font-bold text-xl my-5">Créer Bons de Commende</button></a>
                        @foreach($summaries as $summary)
                                @php

                                @endphp
                                <edit-order supplier="{{$summary->supplier_id}}"></edit-order>

                        @endforeach

                </div>
            </div>
        </div>
    </div>

</x-app-layout>

