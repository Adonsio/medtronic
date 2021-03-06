<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Récapitulatif de la commande individuelle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">

                    @foreach($summaries as $summary)
                        <edit-individual-order supplier="{{$summary->supplier_id}} " sum="{{$summary->totalSum}}"></edit-individual-order>

                    @endforeach

                </div>
            </div>
        </div>
    </div>

</x-app-layout>

