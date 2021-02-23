
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tableau de commande Résumé') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 flex">
                    <div class=" align-items-center justify-content-center mx-auto space-4">
                    <x-nav-link :href="route('user-list')" :active="request()->routeIs('user-list')" class="text-xl flex pt-2 pb-2 bg-blue-100 rounded mx-6 ">
                        {{ __('Utilisateurs') }}
                    </x-nav-link>
                    <x-nav-link :href="route('orga-list')" :active="request()->routeIs('orga-list')" class="text-xl flex pt-2 pb-2 bg-blue-100 rounded mx-6 ">
                        {{ __('Organisation') }}
                    </x-nav-link>
                    <x-nav-link :href="route('supplier-list')" :active="request()->routeIs('supplier-list')" class="text-xl flex pt-2 pb-2 bg-blue-100 rounded mx-6 ">
                        {{ __('Fournisseurs') }}
                    </x-nav-link>
                    <x-nav-link :href="route('product-list')" :active="request()->routeIs('product-list')" class="text-xl flex pt-2 pb-2 bg-blue-100 rounded mx-6 ">
                        {{ __('Catalogue de produits') }}
                    </x-nav-link>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

