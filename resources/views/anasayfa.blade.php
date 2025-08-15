<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"/>

    <style>
        body { background-color: #f8f9fa; }
        .card-hover:hover {
            transform: translateY(-5px);
            transition: 0.3s;
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.25);
        }
        .navbar-brand { font-weight: bold; }
        .avatar-img { width: 35px; height: 35px; object-fit: cover; }
        .card-clickable { cursor: pointer; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ url('/') }}">IP Monitor</a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Auth::user()->avatar
                            ? asset('storage/avatars/' . Auth::user()->avatar)
                            : asset('storage/avatars/default-avatar.png') }}"
                             alt="avatar" class="rounded-circle avatar-img">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="userDropdown" style="min-width: 220px;">
                        <li class="text-center mb-2"><strong>{{ Auth::user()->name }}</strong></li>
                        <li><a class="dropdown-item text-center" href="{{ route('profile') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-center text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>Çıkış
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>
</nav>

<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-dark">Ana Sayfa</h1>
        <p class="text-muted">IP İzleme ve Yönetim Paneline Hoş Geldiniz</p>
    </div>

    <div class="row g-4">
        <!-- Monitor Kartı -->
        <div class="col-md-4">
            <div class="card card-hover card-clickable shadow-sm h-100" style="min-height: 250px;" onclick="window.location.href='{{ url('/monitor') }}'">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="bi bi-activity fs-1 mb-3 text-dark"></i>
                    <h5 class="card-title fw-bold">Monitor</h5>
                    <p class="card-text text-muted">Sunucu ve IP’leri ekleyip yönetebileceğiniz, test edebileceğiniz monitor ekranına geçin.</p>
                </div>
            </div>
        </div>

        <!-- IP Tablosu Kartı -->
        <div class="col-md-4">
            <div class="card card-hover card-clickable shadow-sm h-100" style="min-height: 250px;" onclick="window.location.href='{{ url('/monitor/table') }}'">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="bi bi-table fs-1 mb-3 text-dark"></i>
                    <h5 class="card-title fw-bold">IP Tablosu</h5>
                    <p class="card-text text-muted">Tüm IP kayıtlarını görüntüleyin ve tablo halinde izleyin.</p>
                </div>
            </div>
        </div>

        <!-- Client Ping Kartı -->
        <div class="col-md-4">
            <div class="card card-hover card-clickable shadow-sm h-100" style="min-height: 250px;" onclick="window.location.href='{{ url('/client-pings') }}'">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <i class="bi bi-hdd-network fs-1 mb-3 text-dark"></i>
                    <h5 class="card-title fw-bold">Client Test</h5>
                    <p class="card-text text-muted">Seçtiğiniz IP’lerin bağlantılarını hızlıca test edin ve sonuçları görün.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Çıkış onayı
    function confirmLogout() {
        if(confirm('Çıkış yapmak istediğinize emin misiniz?')) {
            document.getElementById('logout-form').submit();
        }
    }
</script>

</body>
</html>
