<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Fournisseurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table-fixed w-full text-left  ">
                        <thead>
                        <tr>
                            <th class="w-auto ">ID</th>
                            <th class="w-auto ">Nom
                            </th>
                            <th class="w-auto ">Rue
                            </th>
                            <th class="w-auto ">Code postale
                            </th>
                            <th class="w-auto ">Lieu
                            </th>
                            <th class="w-auto ">Téléphone
                            </th>
                            <th class="w-auto ">Fax</th>
                            <th class="w-auto ">Contact</th>
                            <th class="w-auto ">E-mail</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($suppliers as $supplier)
                            <tr class=" @if(($supplier->id % 2) == 0) bg-gray-100 @endif">
                                <td>{{$supplier->supplier_id}}</td>
                                <td>{{$supplier->name}}</td>
                                <td>{{$supplier->street}}</td>
                                <td>{{$supplier->zip_code}}</td>
                                <td>{{$supplier->phone}}</td>
                                <td>{{$supplier->fax}}</td>
                                <td>{{$supplier->contact_person}}</td>
                                <td>{{$supplier->email}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

