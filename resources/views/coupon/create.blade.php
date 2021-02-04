<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Order Coupon') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <h2 class="text-lg">Create Coupon for Order <strong>{{ $order[0]->identifier }}</strong></h2>
                    <br>

                    <p class="font-bold">Ordering Person</p>
                    @if(isset($bulk))
                        <form method="POST" action="{{url('/coupon/bulk/print/'. $order[0]->identifier)}}">
                            @csrf
                    @else
                        <form method="POST" action="{{url('/coupon/individual/print/'. $order[0]->identifier)}}">
                            @csrf
                    @endif

                    <select name="user" id="user">
                        @foreach($users as $user)
                            <option @if($user->id == Auth::user()->id) selected @endif value="{{$user->id}}">{{$user->fullname}}</option>
                        @endforeach
                    </select>
                    <br>
                    <div class="my-4">

                        <button type="submit" class="bg-blue-500 py-3 px-2 my-8 rounded text-white">Create</button>
                    </div>
                    </form>
                        </form>
                    <div class="border border-1 border-gray">
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>

