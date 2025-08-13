<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Monitor IP Ping Sayfası</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" />
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div class="container my-5">
    <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Kayıtlı IP'leri İzle</h4>
            <button class="btn btn-secondary" id="goHomeBtn">Ana Sayfaya Dön</button>
        </div>
        <div class="card-body">
            <div class="mb-3 d-flex gap-2 flex-wrap align-items-center justify-content-between">
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <input type="text" id="newIpInput" class="form-control" placeholder="IP adresi girin" style="max-width: 250px;">
                    <input type="text" id="newNameInput" class="form-control" placeholder="IP ismi girin" style="max-width: 250px;">
                    <button id="addIpBtn" class="btn btn-success">Ekle</button>
                    <button id="startMonitorBtn" class="btn btn-warning" style="white-space: nowrap;">Sürekli Ping Gönder</button>
                    <button id="queuePingBtn" class="btn btn-primary" style="white-space: nowrap;">Ping Queue</button>
                </div>

                <button class="btn btn-outline-danger" id="toggleFailedIpsBtn" type="button" style="white-space: nowrap;">
                    Başarısız IP'ler
                </button>
            </div>

            <div id="failedIpsContainer" class="mb-3" style="display:none;">
                <ul id="failedIpList" class="list-group"></ul>
            </div>

            <div class="table-responsive">
                <table id="monitorTable" class="table table-bordered table-hover align-middle text-center" style="width:100%">
                    <thead class="table-dark">
                    <tr>
                        <th>IP</th>
                        <th>İsim</th>
                        <th>Durum</th>
                        <th>Gecikme (ms)</th>
                        <th>Sil</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    let isMonitoring = false;
    let pingInterval;
    let monitorTable;

    function initDataTable() {
        monitorTable = $('#monitorTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/tr.json'
            },
            columnDefs: [
                { orderable: false, targets: 4 }
            ],
            order: [[0, 'asc']],
            pageLength: 10,
            lengthChange: false,
            searching: false,
        });
    }

    function updateTable(data) {
        monitorTable.clear();

        data.forEach(ip => {
            const statusBadge = ip.status === 'success'
                ? '<span class="badge bg-success">Başarılı</span>'
                : ip.status === 'waiting'
                    ? '<span class="badge bg-warning">Bekliyor...</span>'
                    : '<span class="badge bg-danger">Başarısız</span>';

            const latencyText = ip.latency !== null ? ip.latency.toFixed(2) : '-';
            const deleteBtn = `<button class="btn btn-sm btn-danger deleteBtn" data-ip="${ip.ip}">Sil</button>`;

            monitorTable.row.add([
                ip.ip,
                ip.name ?? '',
                statusBadge,
                latencyText,
                deleteBtn
            ]);
        });

        monitorTable.draw(false);
        showUpdateAnimation();
    }

    function showUpdateAnimation() {
        const table = $('#monitorTable');
        if (table.length === 0) return;

        $('#updateAnimation').remove();

        const anim = $('<div id="updateAnimation">')
            .text('🔄 Tablo güncellendi')
            .css({
                position: 'absolute',
                background: '#28a745',
                color: '#fff',
                padding: '10px 25px',
                borderRadius: '25px',
                fontWeight: '700',
                boxShadow: '0 3px 8px rgba(0,0,0,0.3)',
                zIndex: 9999,
                cursor: 'default',
                userSelect: 'none',
                fontSize: '16px',
                left: table.offset().left + (table.outerWidth() / 2) - 100,
                top: table.offset().top - 50,
                opacity: 0
            })
            .appendTo('body');

        anim.fadeTo(400, 0.95).delay(2000).fadeOut(600, function() {
            $(this).remove();
        });
    }

    function loadIPs() {
        $.getJSON('/monitor/ips', function(data) {
            updateTable(data);
            loadFailedIPs();
        });
    }

    function loadFailedIPs() {
        const failedIpList = $('#failedIpList');
        failedIpList.empty();

        $.getJSON('/monitor/failed-ips', function(data) {
            if (data.length === 0) {
                failedIpList.append('<li class="list-group-item text-muted">Başarısız IP yok</li>');
            } else {
                data.forEach(ip => {
                    failedIpList.append(`
                        <li class="list-group-item list-group-item-danger">
                            <strong>${ip.ip}</strong><br>
                            <small class="text-muted">${ip.updated_at} itibariyle başarısız</small>
                        </li>
                    `);
                });
            }
        });
    }

    function monitorLoop() {
        $.getJSON('/ping/status', function(status) {
            if (!status.running) {
                $.ajax({
                    url: '/ping/queue',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        waitForPingResult(0);
                    },
                    error: function(xhr) {
                        console.error('Ping kuyruk işlemi başlatılamadı:', xhr.responseJSON?.message || 'Bilinmeyen hata');
                    }
                });
            } else {
                waitForPingResult(0);
            }
        });
    }

    function startMonitoring() {
        if (isMonitoring) {
            isMonitoring = false;
            $('#startMonitorBtn').text('Sürekli Ping Gönder').removeClass('btn-danger').addClass('btn-warning');
            clearInterval(pingInterval);
        } else {
            isMonitoring = true;
            $('#startMonitorBtn').text('Durdur').removeClass('btn-warning').addClass('btn-danger');
            monitorLoop();
            pingInterval = setInterval(monitorLoop, 60000);
        }
    }

    function queuePing() {
        $.ajax({
            url: '/ping/queue',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert(response.message);
                waitForPingResult();
            },
            error: function(xhr) {
                alert('Ping kuyruğa alınamadı: ' + (xhr.responseJSON?.message || 'Bilinmeyen hata'));
            }
        });
    }

    function waitForPingResult(attempt = 0, maxAttempts = 40) {
        if (attempt >= maxAttempts) {
            loadIPs();
            showUpdateAnimation();
            return;
        }

        setTimeout(() => {
            $.getJSON('/ping/status', function(status) {
                if (!status.running) {
                    loadIPs();
                    showUpdateAnimation();
                } else {
                    waitForPingResult(attempt + 1, maxAttempts);
                }
            });
        }, 3000);
    }

    $(document).ready(function() {
        initDataTable();
        loadIPs();

        $('#addIpBtn').on('click', function() {
            const ip = $('#newIpInput').val().trim();
            const name = $('#newNameInput').val().trim();

            if (!ip) {
                alert('IP adresi boş olamaz.');
                return;
            }

            if (!name) {
                alert('İsim boş olamaz.');
                return;
            }

            $.ajax({
                url: '/monitor/ips',
                method: 'POST',
                data: { ip, name },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    $('#newIpInput').val('');
                    $('#newNameInput').val('');
                    loadIPs();
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'IP eklenemedi.');
                }
            });
        });

        $('#newIpInput, #newNameInput').on('keypress', function(e) {
            if (e.which === 13) {
                $('#addIpBtn').click();
            }
        });

        $('#monitorTable tbody').on('click', '.deleteBtn', function() {
            const ip = $(this).data('ip');
            if (!confirm(`${ip} adresini silmek istiyor musunuz?`)) return;

            $.ajax({
                url: `/monitor/ips/${ip}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    loadIPs();
                },
                error: function(xhr) {
                    alert('Silinemedi: ' + (xhr.responseJSON?.message || 'Hata oluştu.'));
                }
            });
        });

        $('#startMonitorBtn').on('click', startMonitoring);
        $('#queuePingBtn').on('click', queuePing);
        $('#goHomeBtn').on('click', function() {
            window.location.href = '{{ url('/') }}';
        });

        $('#toggleFailedIpsBtn').on('click', function() {
            $('#failedIpsContainer').toggle();
        });
    });
</script>
</body>
</html>
