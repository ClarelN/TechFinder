<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        .navbar-brand {
            color: #0dcaf0 !important;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="http://127.0.0.1:8000/">TechFinder</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white-50 active" href="/Web/competences">Compétences</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="/Web/utilisateurs">Utilisateurs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">Intervention</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="#">User Compétence</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-2">
                    @isset($authUser)
                        <span class="text-white-50 small">
                            <span class="badge bg-info text-dark">{{ ucfirst($authUser->role_user) }}</span>
                            {{ $authUser->prenom_user }} {{ $authUser->nom_user }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">Déconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-info btn-sm">Connexion</a>
                    @endisset
                </div>
            </div>
        </div>
    </nav>

    @yield('main')

    <footer class="py-3 bg-white border-top mt-auto">
        <div class="container text-center">
            <span class="text-muted">© 2026 <strong>TechFinder</strong> | Partenaire 3iL</span>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            "positionClass"  : "toast-top-right",
            "timeOut"        : "4000",
            "progressBar"    : true,
            "closeButton"    : true,
            "newestOnTop"    : true,
            "showDuration"   : "300",
            "hideDuration"   : "1000",
        };

        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if (session('warning'))
            toastr.warning("{{ session('warning') }}");
        @endif

        @if (session('info'))
            toastr.info("{{ session('info') }}");
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        @endif
    </script>
</body>
</html>