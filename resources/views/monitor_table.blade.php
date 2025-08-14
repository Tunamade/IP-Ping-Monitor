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
        html, body { height: 100%; margin: 0; }
        body.light-mode { background-color: #f8f9fa; color: #212529; }
        body.dark-mode { background-color: #121212; color: #e4e4e4; }

        body.light-mode .card { background-color: white; color: inherit; }
        body.dark-mode .card { background-color: #1e1e1e; color: #e4e4e4; }

        body.dark-mode table.dataTable thead { background-color: #343a40 !important; color: white !important; }
        body.light-mode table.dataTable thead { background-color: #212529 !important; color: white !important; }

        body.dark-mode table.dataTable tbody tr { background-color: transparent !important; }
        body.dark-mode table.dataTable { color: #e4e4e4 !important; }
        body.dark-mode .dataTables_info,
        body.dark-mode .dataTables_paginate,
        body.dark-mode .dataTables_length,
        body.dark-mode .dataTables_filter label { color: #e4e4e4 !important; }

        .mode-switch {
            position: absolute; top: 15px; right: 20px;
            display: flex; align-items: center; gap: 10px; z-index: 9999;
            transition: right 0.2s ease;
        }
        .mode-switch.fullscreen { right: 230px; }

        /* Buton stili */
        .mode-switch button {
            min-width: 120px;
            padding: 5px 10px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transition: all 0.2s ease-in-out;
        }
        .mode-switch button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.25);
        }
        .mode-switch button:active {
            transform: translateY(0);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .mode-switch .form-check-input { cursor: pointer; }

        #tableContainer.fullscreen {
            position: fixed; top:0; left:0; right:0; bottom:0;
            width: 100%; height: 100%; margin: 0; border-radius: 0; z-index: 9998;
            overflow: hidden;
        }
        #tableContainer.fullscreen .card-body { padding:0; height:100%; overflow:hidden; }
        #tableContainer.fullscreen table.dataTable { height: 100% !important; }
        #tableContainer.fullscreen .dataTables_scrollBody { height: 100% !important; }

        @keyframes flashGreen {
            0% { background-color: #d4edda; }
            50% { background-color: #d4edda; opacity: 0.7; }
            100% { background-color: transparent; }
        }
        @keyframes flashRed {
            0% { background-color: #f8d7da; }
            50% { background-color: #f8d7da; opacity: 0.7; }
            100% { background-color: transparent; }
        }
        .flash-green { animation: flashGreen 2s ease-in-out forwards; background-color: #d4edda !important; }
        .flash-red { animation: flashRed 2s ease-in-out forwards; background-color: #f8d7da !important; }
    </style>
</head>
<body class="light-mode">
<div class="container-fluid p-0 h-100">
    <div class="mode-switch">
        <button id="fullscreenBtn" class="btn btn-primary btn-sm">Tam Ekran</button>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="modeToggle">
            <label class="form-check-label" for="modeToggle">🌙</label>
        </div>
        <button id="goHomeBtn" class="btn btn-secondary btn-sm">Ana Sayfaya Dön</button>
    </div>

    <div class="text-center py-3">
        <h1 class="fw-bold">IP İzleme Tablosu</h1>
    </div>

    <div id="tableContainer" class="card border-0 shadow-sm mx-3">
        <div class="card-body p-3">
            <table id="ipTable" class="table table-striped table-hover w-100">
                <thead class="table-dark">
                <tr>
                    <th>IP Adresi</th>
                    <th>Name</th>
                    <th>Durum</th>
                    <th>Gecikme (ms)</th>
                    <th>Güncelleme Tarihi</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let table = $('#ipTable').DataTable({
            ajax: { url: '/monitor/ips', dataSrc: '' },
            columns: [
                { data: 'ip' },
                { data: 'name' },
                { data: 'status', render: d=>d==='success'?'<span class="badge bg-success">Başarılı</span>':d==='fail'?'<span class="badge bg-danger">Başarısız</span>':'<span class="badge bg-secondary">Bilinmiyor</span>' },
                { data: 'latency', render: d=>d!==null?d.toFixed(2):'-' },
                { data: 'updated_at', render: d=>d?new Date(d).toLocaleString('tr-TR'):'-' }
            ],
            language: { url: 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/tr.json' },
            order: [[2,'desc']],
            pageLength: 15,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Tümü"]],
            scrollY: "calc(100vh - 150px)",
            scrollCollapse: true,
            paging: true,
            drawCallback: function(settings) {
                if($('#tableContainer').hasClass('fullscreen')){
                    let containerHeight = $('#tableContainer').height() - $('#ipTable thead').outerHeight();
                    let rowCount = table.rows({page:'current'}).nodes().length;
                    let rowHeight = containerHeight / (rowCount || 1);
                    $('#ipTable tbody tr').css('height', rowHeight + 'px');
                } else {
                    $('#ipTable tbody tr').css('height', '');
                }

                $('#ipTable tbody tr').each(function() {
                    let row = $(this);
                    let statusCell = row.find('td:eq(2)');
                    row.find('td').each(function() {
                        let cell = $(this);
                        if(statusCell.find('.badge.bg-danger').length) {
                            cell.addClass('flash-red');
                            setTimeout(() => { cell.removeClass('flash-red'); cell.css('background-color',''); }, 2000);
                        } else {
                            cell.addClass('flash-green');
                            setTimeout(() => { cell.removeClass('flash-green'); cell.css('background-color',''); }, 2000);
                        }
                    });
                });
            }
        });

        setInterval(() => {
            table.ajax.reload(function() {
                table.order([2,'desc']).draw(false);
            }, false);
        }, 10000);

        $('#modeToggle').on('change', function(){
            $('body').toggleClass('dark-mode light-mode');
            $(this).next('label').text($(this).is(':checked')?'☀️':'🌙');
        });

        $('#goHomeBtn').click(()=>window.location.href='{{ url("/") }}');

        $('#fullscreenBtn').on('click', async function(){
            let container = $('#tableContainer');

            if (!document.fullscreenElement) {
                try { await document.documentElement.requestFullscreen(); }
                catch(err) { console.error("Fullscreen mode error:", err); }
                container.addClass('fullscreen');
                $('#goHomeBtn').hide();
                $('.mode-switch').addClass('fullscreen');
                this.textContent = 'Ekranı Kapat';
            } else {
                try { await document.exitFullscreen(); }
                catch(err) { console.error("Exit fullscreen error:", err); }
                container.removeClass('fullscreen');
                $('#goHomeBtn').show();
                $('.mode-switch').removeClass('fullscreen');
                this.textContent = 'Tam Ekran';
            }
            table.draw();
        });

        $(document).on('keydown', function(e){
            if(e.key === "Escape" && document.fullscreenElement){
                document.exitFullscreen();
                $('#tableContainer').removeClass('fullscreen');
                $('#fullscreenBtn').text('Tam Ekran');
                $('#goHomeBtn').show();
                $('.mode-switch').removeClass('fullscreen');
                table.draw();
            }
        });

        $(window).on('resize', function(){
            if($('#tableContainer').hasClass('fullscreen')) table.draw();
        });
    });
</script>
</body>
</html>
