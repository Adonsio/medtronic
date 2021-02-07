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

                    <br>

                    <div class="w-full flex">
                        <div class="w-1/2">
                            <analyse></analyse>

                            <div class=" flex">
                                <div class="w-1/3">
                                    <p><span class="font-bold">Total No of Orders</span></p>
                                    <p>Pending:           <span class="font-bold text-center pr-5 ">  {{$stats['pending']}}   </span></p>
                                    <p>Received:          <span class="font-bold text-center pr-5 ">  {{ $stats['recived']}}  </span></p>
                                    <p>Invoice Approved:  <span class="font-bold text-center pr-5 ">  {{$stats['complete']}}  </span></p>
                                    <hr>


                                </div>
                                <div class="w-1/3">
                                    <br>
                                    <br>
                                    <p>Bulk: <span class="font-bold text-center ">{{ $stats['bulk'] }}</span></p>
                                    <br>
                                    <hr>
                                    <p>Individual: <span class="font-bold text-center  "> {{ $stats['individual'] }}</span></p>
                                </div>
                                <div class="w-1/3">

                                        <p><span class="font-bold">Delay in Days</span></p>
                                    <br>
                                        <p>Goods Recived: <span class="font-bold text-center ">{{$stats['bulk_complete']}}</span> </p>
                                        <p>Invoice Approved: <span class="font-bold text-center ">{{$stats['complete']}}</span> </p>
                                        <hr>

                                        <p>Goods Recived: <span class="font-bold text-center ">{{ $stats['individual_recived'] }}</span> </p>
                                        <p>Invoice Approved: <span class="font-bold text-centertext-center  ">{{$stats['individual_complete']}}</span> </p>

                                </div>
                            </div>
                            <br>
                            <div class="w-full">

                                <p><span class="font-bold">Total Order Value</span></p>
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



                            </div>
                        </div>
                        <div class="w-1/2 flex">
                            <div class="w-1/3">
                                <div id="chart">
                                    <p class="font-bold text-center">Group</p>
                                    <group-value></group-value>
                                    <group-total></group-total>
                                </div>
                            </div>
                            <div class="w-1/3">
                                <p class="font-bold text-center">Clarens</p>
                                <clarens-value></clarens-value>
                                <clarens-total></clarens-total>
                            </div>
                            <div class="w-1/3">
                                <p class="font-bold text-center">Saxon</p>
                                <saxon-value></saxon-value>
                                <saxon-total></saxon-total>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<!--<div class="grid grid-cols-3 gap-1 w-1/2">
                                <div><p><span class="font-bold text">Total No of Orders</span> </p></div>
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
                            -->