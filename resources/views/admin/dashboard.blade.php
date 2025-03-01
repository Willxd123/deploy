<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
    ],
]">
    <!--configuracion de images-->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center">
                <img class="size-16 rounded-full object-cover" src="{{ Auth::user()->profile_photo_path }}"
                    alt="{{ Auth::user()->name }}" />
                <div class="ml-4 flex-1">
                    <h2 class="text-lg font-semibold">
                        Bienvenido {{ auth()->user()->name }}
                    </h2>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="text-sm hover:text-blue-500">
                            Cerrar sesion
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <!--------------------------Nombre de la empresa---------------------------------->
        <div class="bg-white rounded-lg shadow-lg p-6 flex items-center justify-center">
            <h2 class="text-x1 font-semibold">
                Administra tu Ecommerce
            </h2>
        </div>
    </div>
</x-admin-layout>
