<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Invoices') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <table>
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Show</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($files as $file)
                        <tr>
                            <td>{{$file->name}}</td>
                            <td class="px-4"><a href="{{'/invoice/'.$file->name}}" target="_blank">Show</a></td>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

