<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ana Sayfa</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #f8f9fa;
        }
        {
            border-left: 5px solid #343a40;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <!-- Başlık -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-dark">Ana Sayfa</h1>
    </div>

    <!-- Kart -->
    <div class="card shadow ">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Hoş Geldiniz</h4>
        </div>
        <div class="card-body">
            <p class="mb-4">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris lobortis arcu felis, at tristique purus hendrerit sed. Proin non augue at lacus posuere porttitor. Mauris mattis tellus nec ante sodales, at malesuada dui auctor. Cras sed risus nec erat rhoncus efficitur. Sed ac lectus eros. Nam facilisis, nisi nec suscipit lobortis, nisl nulla pulvinar erat, semper iaculis nisl nisl eu turpis. Ut pellentesque mi nunc, ut consectetur ex ullamcorper sit amet. Aenean sed convallis orci. Ut ac tincidunt massa. Morbi non tempus mauris. Morbi luctus blandit nisl, ut euismod felis. Aliquam non lacus tellus. Curabitur mi magna, viverra eu feugiat sodales, laoreet quis mi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum tristique tincidunt eros, at tempus dolor tempus sed.
            </p>

            <div class="d-flex flex-wrap gap-1">
                <button id="goClientPingPage" class="btn btn-dark">
                    <i class="bi bi-hdd-network"></i> Client Ping Sayfasına Git
                </button>
                <button id="goMonitorPage" class="btn btn-dark">
                    <i class="bi bi-activity"></i> Monitor Sayfasına Git
                </button>
                <button id="goMonitorTablePage" class="btn btn-dark">
                    <i class="bi bi-table"></i> IP Tablosu Sayfasına Git
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap ve jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sayfa içi yönlendirme -->
<script>
    $(document).ready(function() {
        $('#goClientPingPage').on('click', function() {
            window.location.href = '{{ url("/client-pings") }}';
        });
        $('#goMonitorPage').on('click', function() {
            window.location.href = '{{ url("/monitor") }}';
        });
        $('#goMonitorTablePage').on('click', function() {
            window.location.href = '{{ url("/monitor/table") }}';
        });
    });
</script>
</body>
</html>
