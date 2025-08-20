@extends('layouts.master')

@section('content')
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
                        <p class="card-text text-muted">Sunucu ve IP’leri ekleyip yönetebileceğiniz, test edebileceğiniz monitör ekranına geçin.</p>
                    </div>
                </div>
            </div>

            <!-- IP Tablosu Kartı -->
            <div class="col-md-4">
                <div class="card card-hover card-clickable shadow-sm h-100" style="min-height: 250px;" onclick="window.location.href='{{ url('/monitor/table') }}'">
                    <div class="card-body text-center d-flex flex-column justify-content-center">
                        <i class="bi bi-table fs-1 mb-3 text-dark"></i>
                        <h5 class="card-title fw-bold">IP Table</h5>
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
@endsection

@push('styles')
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            transition: 0.3s;
            box-shadow: 0 1rem 2rem rgba(0,0,0,0.25);
        }
        .card-clickable { cursor: pointer; }
    </style>
@endpush

@push('scripts')
    <script>
        // Çıkış onayı
        function confirmLogout() {
            if(confirm('Çıkış yapmak istediğinize emin misiniz?')) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>
@endpush
