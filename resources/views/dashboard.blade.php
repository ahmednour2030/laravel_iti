<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in!

                    <a class="text-center border-gray-100 text-red-600 block" href="/users">list of phones</a>
                    <a class="text-center border-gray-100 text-red-600 block mt-2" href="/users/create">New phones</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
