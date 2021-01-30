<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Invoices') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 product-table">
                    <table class="table-auto w-full text-left overflow-hidden overflow-x-auto">
                        <thead>
                        <tr>
                            <th class="w-auto ">#Bon de Commande</th>
                            <th class="w-auto ">Montant HT</th>
                            <th class="w-auto ">Montant TTC</th>
                            <th class="w-auto ">Fournisseur</th>
                            <th class="w-auto ">Date de la commande</th>
                            <th class="w-auto ">Complete / Pending</th>
                            <th class="w-auto "># Facture</th>
                            <th class="w-auto ">Montant Facture</th>
                            <th class="w-auto ">Date</th>
                            <th class="w-auto "># Facture</th>
                            <th class="w-auto ">Montant</th>
                            <th class="w-auto ">Date</th>

                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

