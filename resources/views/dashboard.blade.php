<?php

?>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Super User Dashboard
                    <br>
                    <br>
                    <div class="p-4 text-center text-white">
                    @role('superuser')
                        <div class="mx-auto flex align-items-center justify-content-center">
                            <a href="/summary/analyse"><button class="bg-blue-500 px-3 py-6 m-2 rounded">Analyse</button></a>
                            <a href="/summary/bulk"><button class="bg-blue-500 px-3 py-6 m-2 rounded">Résumé Commandes groupées
                                </button></a>
                            <a href="/summary/individual"><button class="bg-blue-500 px-3 py-6 m-2 rounded">Résumé Commendes individuelle
                                </button></a>
                            <a href="/invoices"><button class="bg-blue-500 px-3 py-6 m-2 rounded">Factures à approuver</button></a>
                        </div>
                    <div class="w-full flex align-items-center justify-content-center">
                        <a href="/file/upload"><button class="bg-blue-500 px-3 py-6 m-2 rounded">Upload / Import File</button></a>
                        <a href="/list/bulk-order"><button class="bg-blue-500 px-3 py-6 m-2 rounded">Tableau Commendes groupées
                            </button></a>
                        <a href="/meta"><button class="bg-blue-500 px-3 py-6 m-2 rounded">Metadata</button></a>
                        <a href="/invoice/list"><button class="bg-blue-500 px-3 py-6 m-2 rounded">Liste Bons de Commende</button></a>


                    </div>


                    @endrole()
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
