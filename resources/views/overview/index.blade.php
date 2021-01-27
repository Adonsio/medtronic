<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supplay Chain Management ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <h2 class="text-center">You're logged in as <span class="font-bold">{{Auth::user()->fullname}}</span></h2>
                    <div class="p-4 text-center text-white">
                        <a href="/order"><button class="bg-blue-500 px-3 py-6 m-5 rounded">Add to Bulk Order</button></a>
                        <a href="/order/individual"><button class="bg-blue-500 px-3 py-6 m-5 rounded">Place Individual Order</button></a>
                        <a href="/delivery"><button class="bg-blue-500 px-3 py-6 m-5 rounded">Outstanding Deliveries</button></a>
                        @role('superuser')
                        <a href="/invoice"><button class="bg-blue-500 px-3 py-6 m-5 rounded">Approve Invoice</button></a>
                        <a href="/summary"><button class="bg-blue-500 px-3 py-6 m-5 rounded">SCM Summary</button></a>
                        @endrole()
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

