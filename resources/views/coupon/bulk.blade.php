<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Bulk Order Coupon') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">

                    <br>

                    <p class="font-bold">Ordering Person</p>
                    <form method="POST" action="{{url('/coupon/bulk/print/')}}">
                        @csrf

                        <select name="user" id="user">
                            @foreach($users as $user)
                                <option @if($user->id == Auth::user()->id) selected @endif value="{{$user->id}}">{{$user->fullname}}</option>
                            @endforeach
                        </select>
                        <br>
                        <div class="my-4">

                            <button type="submit" class="bg-blue-500 py-3 px-2 my-8 rounded text-white">Create All</button>
                        </div>
                    </form>
                    <div class="border border-1 border-gray">

                        @foreach($orders as $key => $order)
                            <b>{{$key}}:</b>
                            @for ($i = 0; $i < count($order); $i++)
                                {{$order[$i]}}
                                <hr>
                            @endfor
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>

