<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                        <div class="w-full flex">
                        <div class="w-1/3"> <form method="POST" action="{{url('/user/edit/'. $user->id)}}">

                            @csrf
                                <div>
                                    <label for="firstname">Firstname</label> <br>
                                    <input type="text" id="firstname" name="firstname" value="{{$user->firstname}}">
                                    <br>
                                    <br>
                                </div>
                                <div>
                                    <label for="lastname">Lastname</label><br>
                                    <input type="text" id="lastname" name="lastname" value="{{$user->lastname}}">
                                    <br>
                                    <br>
                                </div>
                                <div>
                                    <label for="fullname">Fullname</label><br>
                                    <input type="text" id="fullname" name="fullname" value="{{$user->fullname}}">
                                    <br>
                                    <br>
                                </div>
                                <div>
                                    <label for="login_identifier">Username</label><br>
                                    <input type="text" id="login_identifier" name="login_identifier" value="{{$user->login_identifier}}">
                                    <br>
                                    <br>
                                </div>
                                <div>
                                    <label for="department">Department</label><br>
                                    <input type="text" id="department" name="department" value="{{$user->department}}">
                                    <br>
                                    <br>
                                </div>
                                <div>
                                    <label for="department">Email</label><br>
                                    <input type="text" id="email" name="email" value="{{$user->email}}">
                                    <br>
                                    <br>
                                </div>
                                <div>
                                    <label for="site">Site</label><br>
                                    <input type="text" id="site" name="site" value="{{$user->site}}">
                                    <br>
                                    <br>
                                </div>

                                <input type="submit" value="Save" class="m-2 p-2 bg-blue-500 form-fields">


                            </form>
                        </div>
                        <div class="w-1/3">
                            <form action="/user/role/{{$user->id}}" method="POST">
                                @csrf
                                <select name="role" id="role">

                                    @foreach($roles as $role)
                                        <option @if($role->name == $user->roles[0]->name ) selected @endif value="{{$role->name}}">{{$role->name}}</option>
                                    @endforeach
                                </select>
                                <input type="submit" value="Set Role" class="m-2 p-2 bg-blue-500 form-fields">
                            </form>
                        </div>
                        <div class="w-1/3"> <a href="{{url('/user/reset/'. $user->id)}}" class="font-bold bg-red-400 p-2 float-right">Reset Password</a></div>
                        </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<style>

</style>

