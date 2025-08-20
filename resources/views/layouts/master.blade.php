<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ana Sayfa')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>
    @stack('styles')

    <style>
        body { background: #f5f6fa; }

        /* Card hover */
        .card-hover:hover {
            transform: translateY(-4px);
            transition: .25s;
            box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.1);
        }

        /* Navbar modern */
        .navbar {
            background: linear-gradient(90deg, #343a40 0%, #212529 100%);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border-bottom-left-radius: 0.75rem;  /* Alt köşeler düz */
            border-bottom-right-radius: 0.75rem;
            margin-top: 0;   /* Üst boşluğu kaldır */
        }

        /* Navbar brand hover animasyon */
        .navbar-brand {
            font-weight: bold;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .navbar-brand i {
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover {
            text-shadow: 1px 1px 6px rgba(0,0,0,0.4);
        }

        .navbar-brand:hover i {
            transform: rotate(-15deg) scale(1.1);
        }

        /* Nav link hover animasyon */
        .navbar .nav-link {
            transition: all 0.25s ease-in-out;
            border-radius: 0.5rem;
        }

        .navbar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            transform: scale(1.05);
        }

        /* Dropdown menu */
        .dropdown-menu {
            border-radius: 0.75rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            transition: all 0.25s ease;
        }

        .dropdown-item {
            transition: all 0.25s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(0,123,255,0.1);
            color: #0d6efd;
            transform: scale(1.02);
        }

        /* Avatar hover */
        .avatar-img {
            width: 35px;
            height: 35px;
            object-fit: cover;
            transition: transform 0.25s ease;
        }

        .avatar-img:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow mb-4">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ url('/') }}">
            <i class="bi bi-speedometer2 me-1"></i> IP Ping Monitor
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
            <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar
                            ? asset('storage/avatars/' . Auth::user()->avatar)
                            : asset('images/default-avatar.png') }}"
                             alt="avatar" class="rounded-circle avatar-img">
                        <span class="ms-2 d-none d-lg-inline">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2">
                        <li class="text-center mb-2"><strong>{{ Auth::user()->name }}</strong></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center justify-content-center" href="{{ route('profile') }}">
                                <i class="bi bi-person me-2"></i> Profil
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-center text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> Çıkış
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>
</nav>

<!-- İçerik -->
<div class="container my-5">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
