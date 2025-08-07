<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Client Ping ve Sunucu Ping</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
</head>
<body>
<div class="container my-5">

    <!-- Ping Sonuçları -->
    <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">
            <h1 class="mb-0 h4">Ping Sonuçları</h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle text-center mb-0">
                    <thead class="table-dark">
                    <tr>
                        <th>IP</th>
                        <th>Status</th>
                        <th>Latency (ms)</th>
                    </tr>
                    </thead>
                    <tbody id="pingResultsTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- IP Listesi -->
    <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">
            <h1 class="mb-0 h4">IP Listesine Ping Gönder</h1>
        </div>
        <div class="card-body">
            <textarea id="ipList" class="form-control" rows="4" placeholder="Her satıra bir IP adresi yazın."></textarea>
            <div class="mt-3">
                <button id="pingIpsBtn" class="btn btn-primary">Ping Gönder</button>
                <button id="pingContinuousBtn" class="btn btn-warning">Sürekli Ping Gönder</button>
                <button class="btn btn-secondary float-end" id="goHomeBtn">Ana Sayfaya Dön</button>
            </div>
        </div>
    </div>

    <!-- Client Ping Logları -->
    <div class="card shadow mb-4">
        <div class="card-header bg-dark text-white">
            <h1 class="mb-0 h4">Client Ping Logları</h1>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="myTable" class="table table-bordered table-hover table-striped align-middle text-center mb-0">
                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>IP Adresi</th>
                        <th>Gecikme</th>
                        <th>Oluşturulma</th>
                        <th>İşlem</th>
                    </tr>
                    </thead>
                    <tbody id="client-ping-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- JS -->
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>

<script>
    $(document).ready(function () {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        let isContinuousPingActive = false;
        let dataTableInstance;

        // IP’lere ping gönder
        async function sendIPList() {
            const rawIps = $('#ipList').val().trim();
            if (!rawIps) {
                alert('Lütfen IP adreslerini girin.');
                return false;
            }

            const ips = rawIps.split('\n').map(ip => ip.trim()).filter(ip => ip);
            try {
                const response = await $.ajax({
                    url: '/ping-multiple',
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: JSON.stringify({ ips })
                });

                let allSuccessful = true;
                const $tbody = $('#pingResultsTableBody').empty();

                for (const result of response) {
                    const statusBadge = result.status === 'success'
                        ? '<span class="badge bg-success">Başarılı</span>'
                        : '<span class="badge bg-danger">Başarısız</span>';

                    if (result.status !== 'success') allSuccessful = false;

                    $tbody.append(`
                        <tr>
                            <td>${result.ip}</td>
                            <td>${statusBadge}</td>
                            <td>${result.latency ?? '-'}</td>
                        </tr>
                    `);

                    await $.ajax({
                        url: '/client-ping',
                        method: 'POST',
                        contentType: 'application/json',
                        headers: { 'X-CSRF-TOKEN': csrfToken },
                        data: JSON.stringify({
                            ip: result.ip,
                            latency: result.latency,
                            status: result.status
                        })
                    });
                }

                await loadClientPings();
                return allSuccessful;

            } catch (error) {
                alert('Hata oluştu: ' + error.responseText);
                return false;
            }
        }

        // Sürekli ping
        async function startContinuousPing() {
            while (isContinuousPingActive) {
                const success = await sendIPList();
                if (!success) {
                    alert('Bazı ping işlemleri başarısız oldu. Durduruluyor.');
                    isContinuousPingActive = false;
                    $('#pingContinuousBtn').text('Sürekli Ping Gönder');
                    break;
                }
                await new Promise(resolve => setTimeout(resolve, 1000));
            }
        }

        function toggleContinuousPing() {
            if (isContinuousPingActive) {
                isContinuousPingActive = false;
                $('#pingContinuousBtn').text('Sürekli Ping Gönder');
            } else {
                const rawIps = $('#ipList').val().trim();
                if (!rawIps) {
                    alert('Lütfen IP adreslerini girin.');
                    return;
                }
                isContinuousPingActive = true;
                $('#pingContinuousBtn').text('Durdur');
                startContinuousPing();
            }
        }

        // Client Ping kayıtlarını yükle
        async function loadClientPings() {
            const data = await $.getJSON('/client-pings/json');
            const rows = data.map(ping => [
                ping.id,
                ping.ip,
                ping.latency ?? '-',
                ping.created_at ?? '-',
                `<button class="btn btn-sm btn-danger delete-ping" data-id="${ping.id}">Sil</button>`
            ]);

            if (dataTableInstance) {
                dataTableInstance.clear().rows.add(rows).draw();
            } else {
                dataTableInstance = $('#myTable').DataTable({
                    data: rows,
                    columns: [
                        { title: "ID" },
                        { title: "IP Adresi" },
                        { title: "Gecikme" },
                        { title: "Oluşturulma" },
                        { title: "İşlem" }
                    ],
                    language: {
                        url: "https://cdn.datatables.net/plug-ins/2.3.2/i18n/tr.json"
                    }
                });
            }
        }

        // Silme butonu
        $(document).on('click', '.delete-ping', function () {
            const id = $(this).data('id');
            if (!confirm('Bu kaydı silmek istiyor musunuz?')) return;

            $.ajax({
                url: `/client-pings/${id}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function () {
                    loadClientPings();
                },
                error: function (xhr) {
                    alert('Silme başarısız: ' + (xhr.responseJSON?.message || 'Sunucu hatası.'));
                }
            });
        });

        // Butonlar
        $('#pingIpsBtn').on('click', sendIPList);
        $(`#pingContinuousBtn`).on('click', toggleContinuousPing);
        $('#goHomeBtn').on('click', () => window.location.href = '{{ url('/') }}');

        // İlk yükleme
        loadClientPings();
    });
</script>
</body>
</html>
