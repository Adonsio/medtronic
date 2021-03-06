<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Organisation
') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table-fixed w-full text-left  ">
                        <thead>
                        <tr>
                            <th class="w-auto ">Nom
                            </th>
                            <th class="w-auto ">Site</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orgas as $orga)
                            <tr class=" @if(($orga->id % 2) == 0) bg-gray-100 @endif">
                                <td>{{$orga->name}}</td>
                                <td>{{$orga->site}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

