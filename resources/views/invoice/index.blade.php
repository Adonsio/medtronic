<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Factures à approuver') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 product-table">
                    @role('superuser')
                    <a href="{{url('export/invoice')}}" class="p-3 my-6 inline-block bg-blue-500 hover:bg-blue-400 font-bold text-white">Download CSV</a>
                    @endrole()
                    <invoice-table></invoice-table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

