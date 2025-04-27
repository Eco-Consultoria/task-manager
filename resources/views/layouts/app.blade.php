<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="bg-black text-white">

   @auth
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container-fluid">
            @include('partials.navbar')
        </div>
    </nav>
    @endauth
    

    <div class="container-fluid mt-5 pt-4">
        @yield('content')
    </div>

    <script src="{{ asset('js/tasks.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
