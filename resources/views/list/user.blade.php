<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Utilisateurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="product-table w-full text-left">
                        <thead>
                        <tr>
                            <th class="w-auto pl-2">Prénom</th>
                            <th class="w-auto ">Nom de Famille</th>
                            <th class="w-auto ">Département</th>
                            <th class="w-auto ">Site</th>
                            <th class="w-auto ">Nom et prénom</th>
                            <th class="w-auto ">Nom d'utilisateur</th>
                            <th class="w-auto ">Mot de passe</th>
                            <th class="w-auto ">Rôle</th>
                            <th class="w-auto "></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr class=" @if(($user->id % 2) == 0) bg-gray-100 @endif">
                                <td class="pl-4">{{$user->firstname}}</td>
                                <td>{{$user->lastname}}</td>
                                <td>{{$user->department}}</td>
                                <td>{{$user->site}}</td>
                                <td>{{$user->fullname}}</td>
                                <td>{{$user->login_identifier}}</td>
                                <td>{{$user->password_nohash}} </td>

                                <td class="flex flex-row">
                                    @foreach($user->roles as $role)
                                        {{ $role->name }}
                                        @endforeach
                                    </td>
                                <td><a href="{{url('/user/edit/'.$user->id)}}" class="bg-blue-500 p-2 m-2 font-bold text-white">Edit</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<style>
    form, .form-fields {
        display: flex;
    }
</style>

