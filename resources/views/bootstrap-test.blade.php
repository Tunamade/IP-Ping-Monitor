<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Bootstrap Örnekler</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        pre {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            font-size: 0.9rem;
        }
        code {
            color: #d63384;
        }
        hr {
            margin: 3rem 0;
        }
    </style>
</head>
<body class="bg-light">

<div class="container my-5">
    <h1 class="text-center text-primary mb-5">Bootstrap Örnekler</h1>

    {{-- 1. Grid System --}}
    <h4>📐 Responsive Grid Sistemi</h4>
    <p>
      sütunların boyunu ve enini ayarlıyoruz
    </p>  
    <pre><code>
      
&lt;div class="row"&gt;  
  &lt;div class="col-md-4"&gt;Birinci sütun&lt;/div&gt; 
  &lt;div class="col-md-4"&gt;İkinci sütun&lt;/div&gt;
  &lt;div class="col-md-4"&gt;Üçüncü sütun&lt;/div&gt;
&lt;/div&gt;
    </code></pre>
    <div class="row mb-4">
        <div class="col-md-4 border bg-white p-3 text-center">Birinci sütun</div>
        <div class="col-md-4 border bg-white p-3 text-center">İkinci sütun</div>
        <div class="col-md-4 border bg-white p-3 text-center">Üçüncü sütun</div>
    </div>

    <hr>

    {{-- 2. Dropdown --}}
    <h4>⬇️ Dropdown (Açılır Menü)</h4>
    <pre><code>
&lt;div class="dropdown"&gt;
  &lt;button class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown"&gt;Menü&lt;/button&gt;
  &lt;ul class="dropdown-menu"&gt;
    &lt;li&gt;&lt;a class="dropdown-item" href="#"&gt;Öğe 1&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a class="dropdown-item" href="#"&gt;Öğe 2&lt;/a&gt;&lt;/li&gt;
  &lt;/ul&gt;
&lt;/div&gt;
    </code></pre>
    <div class="dropdown mb-4">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">Menü</button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Öğe 1</a></li>
            <li><a class="dropdown-item" href="#">Öğe 2</a></li>
        </ul>
    </div>

    <hr>

    {{-- 3. Buttons --}}
    <h4>🔘 Farklı Buton Stilleri</h4>
    <pre><code>
&lt;button class="btn btn-primary"&gt;Birincil&lt;/button&gt;
&lt;button class="btn btn-success"&gt;Başarılı&lt;/button&gt;
&lt;button class="btn btn-danger"&gt;Tehlike&lt;/button&gt;
    </code></pre>
    <button class="btn btn-primary me-2 mb-3">Birincil</button>
    <button class="btn btn-success me-2 mb-3">Başarılı</button>
    <button class="btn btn-danger mb-3">Tehlike</button>

    <hr>

    {{-- 4. Forms ve Validation --}}
    <h4>📝 Form & Validation</h4>
    <pre><code>
&lt;input type="text" class="form-control is-invalid" placeholder="Hatalı giriş"&gt;
&lt;div class="invalid-feedback"&gt;Hata mesajı&lt;/div&gt;
    </code></pre>
    <input type="text" class="form-control is-invalid mb-1" placeholder="Hatalı giriş">
    <div class="invalid-feedback mb-4">Hata mesajı</div>

    <hr>

    {{-- 5. Alerts --}}
    <h4>⚠️ Uyarı Çeşitleri</h4>
    <pre><code>
&lt;div class="alert alert-success"&gt;Başarılı!&lt;/div&gt;
&lt;div class="alert alert-danger"&gt;Hata!&lt;/div&gt;
    </code></pre>
    <div class="alert alert-success">Başarılı!</div>
    <div class="alert alert-danger mb-4">Hata!</div>

    <hr>

    {{-- 6. Cards --}}
    <h4>🃏 Kartlar</h4>
    <pre><code>
&lt;div class="card" style="width: 18rem;"&gt;
  &lt;div class="card-body"&gt;
    &lt;h5 class="card-title"&gt;Başlık&lt;/h5&gt;
    &lt;p class="card-text"&gt;İçerik buraya.&lt;/p&gt;
  &lt;/div&gt;
&lt;/div&gt;
    </code></pre>
    <div class="card mb-4" style="width: 18rem;">
        <div class="card-body">
            <h5 class="card-title">Başlık</h5>
            <p class="card-text">İçerik buraya.</p>
        </div>
    </div>

    <hr>

    {{-- 7. Navbar --}}
    <h4>🌐 Navbar (Üst Menü)</h4>
    <pre><code>
&lt;nav class="navbar navbar-expand-lg navbar-dark bg-dark"&gt;...&lt;/nav&gt;
    </code></pre>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <a class="navbar-brand" href="#">Site</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="#">Anasayfa</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Hakkında</a></li>
                <li class="nav-item"><a class="nav-link" href="#">İletişim</a></li>
            </ul>
        </div>
    </nav>

    <hr>

    {{-- 8. Modal --}}
    <h4>🪟 Modal Pencere</h4>
    <pre><code>
&lt;button data-bs-toggle="modal" data-bs-target="#exampleModal"&gt;Aç&lt;/button&gt;
    </code></pre>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Aç</button>
    <div class="modal fade" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal Başlığı</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">Modal içeriği buraya gelecek.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                    <button class="btn btn-primary">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

    <hr>

    {{-- 9. Accordion --}}
    <h4>📂 Accordion (Açılır Panel)</h4>
    <pre><code>
&lt;div class="accordion" id="accordionExample"&gt;...&lt;/div&gt;
    </code></pre>
    <div class="accordion mb-4" id="accordionExample">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                    Panel Başlığı
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">Panel içeriği buraya gelir.</div>
            </div>
        </div>
    </div>

    <hr>

    {{-- 10. Tooltip & Popover --}}
    <h4>🧭 Tooltip & Popover</h4>
    <pre><code>
&lt;button data-bs-toggle="tooltip" title="Tooltip açıklaması"&gt;Tooltip&lt;/button&gt;
&lt;button data-bs-toggle="popover" title="Başlık" data-bs-content="İçerik"&gt;Popover&lt;/button&gt;
    </code></pre>
    <button class="btn btn-outline-secondary me-3 mb-4" data-bs-toggle="tooltip" title="Tooltip açıklaması">Tooltip</button>
    <button class="btn btn-outline-info mb-4" data-bs-toggle="popover" title="Başlık" data-bs-content="Popover içeriği">Popover</button>

    <hr>

    {{-- 11. Toast --}}
    <h4>📢 Toast Bildirimi</h4>
    <pre><code>
&lt;div class="toast"&gt;...&lt;/div&gt;
    </code></pre>
    <button class="btn btn-secondary mb-3" onclick="bootstrap.Toast.getOrCreateInstance(document.getElementById('liveToast')).show()">Toast Göster</button>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Bildirim</strong>
                <button class="btn-close" data-bs-dismiss="toast" aria-label="Kapat"></button>
            </div>
            <div class="toast-body">Toast mesajı buraya gelecek.</div>
        </div>
    </div>

    <hr>

    {{-- 12. Carousel --}}
    <h4>🎠 Carousel (Slider)</h4>
    <pre><code>
&lt;div class="carousel slide"&gt;...&lt;/div&gt;
    </code></pre>
    <div id="carouselExample" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner rounded shadow">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/600x200/007bff/ffffff?text=Birinci" class="d-block w-100" alt="Birinci">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/600x200/28a745/ffffff?text=İkinci" class="d-block w-100" alt="İkinci">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/600x200/dc3545/ffffff?text=Üçüncü" class="d-block w-100" alt="Üçüncü">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <hr>

    {{-- 13. Tables --}}
    <h4>📋 Tablolar</h4>
    <pre><code>
&lt;table class="table table-bordered"&gt;...&lt;/table&gt;
    </code></pre>
    <table class="table table-bordered mb-5">
        <thead class="table-light">
            <tr><th>#</th><th>Ad</th><th>Soyad</th></tr>
        </thead>
        <tbody>
            <tr><td>1</td><td>Ahmet</td><td>Demir</td></tr>
            <tr><td>2</td><td>Elif</td><td>Çelik</td></tr>
        </tbody>
    </table>

    <hr>

    {{-- 14. Badges --}}
    <h4>🔖 Badge (Etiket)</h4>
    <pre><code>
&lt;span class="badge bg-danger"&gt;Yeni&lt;/span&gt;
    </code></pre>
    <h5>Yeni Bildirimler <span class="badge bg-danger">5</span></h5>

    <hr>

    {{-- 15. Progress Bar --}}
    <h4>📊 Progress Bar (İlerleme Çubuğu)</h4>
    <pre><code>
&lt;div class="progress"&gt;
  &lt;div class="progress-bar bg-info" style="width: 60%;"&gt;60%&lt;/div&gt;
&lt;/div&gt;
    </code></pre>
    <div class="progress mb-4" style="height: 25px;">
        <div class="progress-bar bg-info" style="width: 60%;">60%</div>
    </div>

    <hr>

    {{-- 16. Spinner --}}
    <h4>⏳ Spinner (Yükleniyor Simge)</h4>
    <pre><code>
&lt;div class="spinner-border text-primary" role="status"&gt;&lt;/div&gt;
    </code></pre>
    <div class="spinner-border text-primary mb-4" role="status">
        <span class="visually-hidden">Yükleniyor...</span>
    </div>

    <hr>

    {{-- 17. List Group --}}
    <h4>📦 List Group (Liste Grubu)</h4>
    <pre><code>
&lt;ul class="list-group"&gt;
  &lt;li class="list-group-item"&gt;Öğe 1&lt;/li&gt;
  &lt;li class="list-group-item active"&gt;Öğe 2&lt;/li&gt;
&lt;/ul&gt;
    </code></pre>
    <ul class="list-group mb-5">
        <li class="list-group-item">Öğe 1</li>
        <li class="list-group-item active">Öğe 2</li>
        <li class="list-group-item">Öğe 3</li>
    </ul>

</div>

<script>
    // Tooltipları başlat
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

    // Popoverları başlat
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    popoverTriggerList.forEach(el => new bootstrap.Popover(el));
</script>

</body>
</html>
