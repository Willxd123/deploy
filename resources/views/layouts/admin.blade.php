@props(['breadcrumbs' => []])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="/path/to/tailwind.css" rel="stylesheet">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!--enlace para los estilo de iconos -->
    <script src="https://kit.fontawesome.com/e8744251ae.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<!-- declaracion de la variable sidebar-->

<body class="font-sans antialiased" x-data="{
    sidebarOpen: false
}" :class="{
    'overflow-y-hidden': sidebarOpen
}">

    <!--configuracion para dinamica del menu y el sidebar-->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-20 sm:hidden" style="display: none" x-show="sidebarOpen"
        x-on:click="sidebarOpen = false">


    </div>
    <div>
        @livewire('admin.navegation')
    </div>

{{--     @include('layouts.particiones.admin.navegation') --}}
    @include('layouts.particiones.admin.sidebar')



    <div class="p-4 sm:ml-64">
        <div class="mt-[100px]">

            <div class="flex justify-between items-center">
                @include('layouts.particiones.admin.breadcrumb')

                @isset($action)
                    <div class="space-x-2">
                        {{ $action }}

                    </div>

                @endisset


            </div>

            <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 ">


                {{ $slot }}

            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @livewireScripts

        @stack('js')

        @if (session('swal'))
            <script>
                Swal.fire({!! json_encode(session('swal')) !!});
            </script>
        @endif
</body>

</html>
