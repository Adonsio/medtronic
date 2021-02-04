<x-app-layout>
    @if(Session::has('success'))
        <div class="p-4 bg-green-400 text-center">
            {{ Session::get('success')}}
        </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion du matériel - Analyse') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <analyse></analyse>
                    <br>

                    <div class="w-1/2">
                        <div class="grid grid-cols-3 gap-4">
                            <div><p><span class="font-bold">Total No of Orders:</span> {{ count($totalOrders) }}</p></div>
                            <div></div>
                            <div><p><span class="font-bold">Delay in Days</span></p></div>
                            <div><p><span class="font-bold">Pending: {{$stats['pending']}}</span></p></div>
                            <div><p><span class="font-bold ">Bulk: </span><span>{{ $stats['bulk'] }}</span></p></div>
                            <div><p><span class="font-bold">Goods Received: {{$stats['bulk_recived']}} </span></p>                            </div>
                            <div><p><span class="font-bold">Received: {{ $stats['recived'] }}</span></p>                            </div>
                            <div></div>
                            <div><p><span class="font-bold">Invoice Approved: {{$stats['bulk_complete']}}  </span></p></div>
                            <div><p><span class="font-bold">Invoice Approved: {{$stats['complete']}}  </span></p></div>
                            <div></div>
                            <div></div>
                            <div></div>
                            <div><p><span class="font-bold">Individual: {{ $stats['individual'] }}</span></p></div>
                            <div><p><span class="font-bold">Goods Received: {{ $stats['individual_recived'] }} </span></p></div>
                            <div></div>
                            <div></div>
                            <div><p><span class="font-bold">Invoice Approved: {{$stats['individual_complete']}} </span></p></div>
                        </div>
                        <br>
                        <div class="w-full">

                            <p><span class="font-bold">Total Order Value</span> {{ $totalValue }} €</p>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    @foreach($suppliers as $supplier)
                                        <p class="pl-8"><span class="font-bold">{{$supplier->supplier_name}}</span>: {{ $supplier->totalSum }}€</p>
                                    @endforeach
                                </div>
                                <div>
                                    @foreach($sites as $site)
                                        <p class="pl-8"><span class="font-bold">{{$site->site}}</span>: {{ $site->totalSum }}€</p>
                                    @endforeach
                                </div>
                                <div>
                                    @foreach($departments as $department)
                                        <p class="pl-8"><span class="font-bold">{{$department->department}}</span>: {{ $department->totalSum }}€</p>
                                    @endforeach
                                </div>
                            </div>
                            <div class="w-1/3">
                                <chart></chart>
                            </div>
                            <div class="w-1/3">

                            </div>
                            <div class="w-1/3">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

