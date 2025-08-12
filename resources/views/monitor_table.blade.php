<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>IP İzleme Tablosu</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        /* Light mode */
        body.light-mode {
            background-color: #f8f9fa;
            color: #212529;
        }
        /* Dark mode */
        body.dark-mode {
            background-color: #121212;
            color: #e4e4e4;
        }
        /* Card renkleri */
        body.light-mode .card {
            background-color: white;
            color: inherit;
        }
        body.dark-mode .card {
            background-color: #1e1e1e;
            color: #e4e4e4;
        }
        /* DataTables başlıkları */
        body.dark-mode table.dataTable thead {
            background-color: #343a40 !important;
            color: white !important;
        }
        body.light-mode table.dataTable thead {
            background-color: #212529 !important;
            color: white !important;
        }
        /* DataTables hücreleri */
        body.dark-mode table.dataTable {
            color: #e4e4e4 !important;
        }
        /* DataTables pagination ve diğer elementler */
        body.dark-mode .dataTables_info,
        body.dark-mode .dataTables_paginate,
        body.dark-mode .dataTables_length,
        body.dark-mode .dataTables_filter label {
            color: #e4e4e4 !important;
        }
        .mode-switch {
            position: absolute;
            top: 15px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
        }
        .mode-switch button {
            min-width: 120px;
        }
    </style>
</head>
<body class="light-mode">

<div class="container-fluid p-0 h-100">
    <!-- Dark/Light Mode Switch, Ana Sayfaya Dön ve Tam Ekran Butonu -->
    <div class="mode-switch">
        <button id="fullscreenBtn" class="btn btn-primary btn-sm">Tam Ekran</button>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="modeToggle">
            <label class="form-check-label" for="modeToggle">🌙</label>
        </div>
        <button id="goHomeBtn" class="btn btn-secondary btn-sm">Ana Sayfaya Dön</button>
    </div>

    <!-- Başlık -->
    <div class="text-center py-3">
        <h1 class="fw-bold">IP İzleme Tablosu</h1>
    </div>

    <!-- Tablo Container -->
    <div id="tableContainer" class="card border-0 shadow-sm mx-3">
        <div class="card-body p-3">
            <table id="ipTable" class="table table-striped table-hover w-100">
                <thead class="table-dark">
                <tr>
                    <th>IP Adresi</th>
                    <th>Durum</th>
                    <th>Gecikme (ms)</th>
                    <th>Güncelleme Tarihi</th>
                </tr>
                </thead>
                <tbody>
                <!-- Veri Ajax ile yüklenecek -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let table = $('#ipTable').DataTable({
            ajax: {
                url: '/monitor/ips',
                dataSrc: ''
            },
            columns: [
                { data: 'ip' },
                {
                    data: 'status',
                    render: function(data) {
                        if(data === 'success') return '<span class="badge bg-success">Başarılı</span>';
                        if(data === 'fail') return '<span class="badge bg-danger">Başarısız</span>';
                        return '<span class="badge bg-secondary">Bilinmiyor</span>';
                    }
                },
                {
                    data: 'latency',
                    render: function(data) {
                        return data !== null ? data.toFixed(2) : '-';
                    }
                },
                {
                    data: 'updated_at',
                    render: function(data) {
                        if (!data) return '-';
                        let date = new Date(data);
                        return date.toLocaleString('tr-TR');
                    }
                }
            ],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/tr.json'
            },
            order: [[0, 'asc']],
            pageLength: 15
        });

        // Her 5 saniyede tabloyu otomatik yenile
        setInterval(function () {
            table.ajax.reload(null, false);
        }, 5000);

        // Dark/Light Mode Switch
        $('#modeToggle').on('change', function() {
            if($(this).is(':checked')) {
                $('body').removeClass('light-mode').addClass('dark-mode');
                $(this).next('label').text('☀️');
            } else {
                $('body').removeClass('dark-mode').addClass('light-mode');
                $(this).next('label').text('🌙');
            }
        });

        // Ana Sayfaya Dön butonu tıklaması
        $('#goHomeBtn').on('click', function() {
            window.location.href = '{{ url('/') }}';
        });

        // Tam Ekran butonu tıklaması
        $('#fullscreenBtn').on('click', function() {
            let elem = document.getElementById('tableContainer');
            if (!document.fullscreenElement) {
                if (elem.requestFullscreen) {
                    elem.requestFullscreen();
                } else if (elem.mozRequestFullScreen) { /* Firefox */
                    elem.mozRequestFullScreen();
                } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
                    elem.webkitRequestFullscreen();
                } else if (elem.msRequestFullscreen) { /* IE/Edge */
                    elem.msRequestFullscreen();
                }
                $(this).text('Ekranı Kapat');
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
                $(this).text('Tam Ekran');
            }
        });

        // Fullscreen çıkış yapıldığında buton metnini resetle
        $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
            if (!document.fullscreenElement) {
                $('#fullscreenBtn').text('Tam Ekran');
            }
        });

    });
</script>

</body>
</html>
