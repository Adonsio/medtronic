<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('File Upload / Import') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mt-4 mb-4 bg-blue-100 p-4 w-1/3 font-bold rounded-xl">Important: Import Suppliers before Products</div>
                    <form method="POST" action="{{ route('upload') }}" aria-label="{{ __('Upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-label for="name" :value="__('File Name')" />

                            <x-input id="name" class="block mt-1 w-auto" type="text" name="name" :value="old('name')" required autofocus />
                        </div>
                        <div class="py-4">
                            <x-label for="name" :value="__('File Name')" />

                            <x-input id="import" class="block py-2 mt-1" type="file" name="import" required accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                        </div>
                        <div class="py-4">
                            <x-label for="type" :value="__('Import Type')"  class="py-2"/>

                            <select name="type" id="type" >
                                <option value="users">Users</option>
                                <option value="orgas">Organisation</option>
                                <option value="supplier">Suppliers</option>
                                <option value="products">Products</option>
                            </select>
                        </div>


                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" name="upload" value="import" class="rounded-xl button bg-blue-500 hover:bg-blue-200 p-4 my-8 font-bold text-white">
                                    {{ __('Upload') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr class="py-2">
                    <div class="w-full py-2">
                        <h2 class="font-bold text-xl">Files</h2>
                    </div>
                    <div class="flex space-x-4">


                    @foreach($files as $file)
                        <div class="rounded-lg shadow-lg bg-gray-100 w-1/4 p-2">
                            <strong>Name: </strong>{{ $file->name }} <br>
                            <strong>Imported: </strong> {{ $file->imported ? 'Yes' : 'No' }} <br>
                            @if(!$file->imported)
                                <a href="/import/{{$file->type}}/{{$file->id}}">
                                    <button class="rounded-xl button rounded bg-blue-500 hover:bg-blue-200 p-2 my-2 font-bold text-white">
                                        Import now
                                    </button>
                                </a>
                            @endif
                        </div>

                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


