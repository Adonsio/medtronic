<x-app-layout>
    @if(Session::has('success'))
        <div class="p-4 bg-green-400 text-center">
            {{ Session::get('success')}}
        </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="table-fixed w-full text-left">
                        <thead>
                        <tr>
                            <th class="w-auto ">First Name</th>
                            <th class="w-auto ">Last Name</th>
                            <th class="w-auto ">Department</th>
                            <th class="w-auto ">Site</th>
                            <th class="w-auto ">Fullname</th>
                            <th class="w-auto ">User Name</th>
                            @role('superuser')
                            <th class="w-auto ">Password</th>
                            @endrole
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr class=" @if(($user->id % 2) == 0) bg-gray-100 @endif">
                                <td>{{$user->firstname}}</td>
                                <td>{{$user->lastname}}</td>
                                <td>{{$user->department}}</td>
                                <td>{{$user->site}}</td>
                                <td>{{$user->fullname}}</td>
                                <td>{{$user->login_identifier}}</td>
                                @role('superuser')
                                <td>{{$user->password_nohash}}</td>
                                @endrole
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

