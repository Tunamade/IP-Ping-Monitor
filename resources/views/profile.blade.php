@extends('layouts.master')

@section('title', 'Profil')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet"/>
    <style>
        .profile-card { margin-top: 100px; border-radius: 1rem; }
        .avatar-xl { width: 140px; height: 140px; object-fit: cover; border: 4px solid #fff; box-shadow: 0 10px 25px rgba(0,0,0,.15); }
        .btn-soft { background: #f1f5f9; border: 1px solid #e2e8f0; }
        .btn-soft:hover { background:#e7edf5; }
        .form-section-title { font-size: .95rem; letter-spacing: .02em; color: #64748b; text-transform: uppercase; }
        .nav-link.active { background: #111827 !important; color:#fff !important; }
        .divider { height: 1px; background: #e5e7eb; }
        .info-badge { background:#eef2ff; color:#3730a3; border:1px solid #c7d2fe; font-size:.8rem; }
        @media (max-width: 575.98px) { .avatar-xl { width: 110px; height: 110px; } }
    </style>
@endpush

@section('content')

    <div class="profile-cover w-100"></div>

    <div class="container">
        <!-- Profil Kartı -->
        <div class="card profile-card shadow-sm border-0 p-4 card-hover">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-md-auto text-center">
                    <img
                        src="{{ Auth::user()->avatar
                        ? asset('storage/avatars/' . Auth::user()->avatar)
                        : asset('images/default-avatar.png') }}"
                        class="rounded-circle avatar-xl"
                        alt="Avatar"
                        id="avatarPreview">
                </div>
                <div class="col">
                    <h2 class="mb-1">{{ Auth::user()->name }}</h2>
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                    <span class="badge info-badge">
                        <i class="bi bi-envelope me-1"></i>{{ Auth::user()->email }}
                    </span>
                    </div>

                    <!-- Profil Resmi Değiştir -->
                    <form id="avatarForm" enctype="multipart/form-data">
                        @csrf
                        <label class="btn btn-soft">
                            <i class="bi bi-camera me-2"></i>Profil Resmini Değiştir
                            <input type="file" accept="image/*" class="d-none" id="avatarInput">
                        </label>
                    </form>
                </div>
                <div class="col-12 col-md-auto text-center text-md-end">
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary mb-2">
                        <i class="bi bi-house-door me-2"></i>Ana Sayfa
                    </a>
                    <form id="logout-form-main" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="button" class="btn btn-danger btn-md" onclick="confirmLogout()">
                            <i class="bi bi-box-arrow-right me-1"></i>Çıkış Yap
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- İçerik: Menü ve Tablar -->
        <div class="row mt-4 align-items-stretch">
            <div class="col-lg-4 d-flex">
                <div class="card border-0 shadow-sm card-hover h-100 w-100">
                    <div class="card-body">
                        <div class="form-section-title mb-3">Profil Menüsü</div>
                        <div class="list-group">
                            <a href="#genelBilgiler" class="list-group-item list-group-item-action d-flex align-items-center active" data-bs-toggle="list">
                                <i class="bi bi-person-lines-fill me-2"></i>Genel Bilgiler
                            </a>
                            <a href="#sifreDegistir" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="list">
                                <i class="bi bi-key me-2"></i>Şifre Değiştir
                            </a>
                            <a href="#bildirimler" class="list-group-item list-group-item-action d-flex align-items-center" data-bs-toggle="list">
                                <i class="bi bi-bell me-2"></i>Bildirim Tercihleri
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="tab-content">
                    <!-- Genel Bilgiler -->
                    <div class="tab-pane fade show active" id="genelBilgiler">
                        <div class="card border-0 shadow-sm card-hover">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-section-title">Genel Bilgiler</div>
                                    <span class="text-muted small">Hesap bilgilerinizi görüntüleyin veya düzenleyin</span>
                                </div>

                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if ($errors->any())
                                    <div class="alert alert-danger mb-3">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div id="infoView">
                                    <p><strong>İsim:</strong> {{ Auth::user()->name }}</p>
                                    <p><strong>E-posta:</strong> {{ Auth::user()->email }}</p>
                                    <p><strong>Hakkımda:</strong> {{ Auth::user()->about ?? 'Belirtilmemiş' }}</p>
                                    <button class="btn btn-primary" onclick="toggleEdit(true)"><i class="bi bi-pencil me-2"></i>Düzenle</button>
                                </div>

                                <form id="infoEdit" action="{{ route('profile.update') }}" method="POST" class="row g-3 d-none">
                                    @csrf
                                    <div class="col-md-6">
                                        <label class="form-label">Ad Soyad</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">E-posta</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Hakkımda</label>
                                        <textarea name="about" class="form-control" rows="3">{{ old('about', Auth::user()->about ?? '') }}</textarea>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-light" onclick="toggleEdit(false)">İptal</button>
                                        <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle me-2"></i>Kaydet</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Şifre Değiştir -->
                    <div class="tab-pane fade" id="sifreDegistir">
                        <div class="card border-0 shadow-sm card-hover">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-section-title">Şifre Değiştir</div>
                                    <span class="text-muted small">Hesap güvenliğini artırın</span>
                                </div>
                                <form action="{{ route('profile.password') }}" method="POST" class="row g-3">
                                    @csrf
                                    <div class="col-12">
                                        <label class="form-label">Mevcut Şifre</label>
                                        <input type="password" name="current_password" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Yeni Şifre</label>
                                        <input type="password" name="password" class="form-control" minlength="8" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Yeni Şifre (Tekrar)</label>
                                        <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end gap-2">
                                        <button type="reset" class="btn btn-light">Sıfırla</button>
                                        <button type="submit" class="btn btn-dark"><i class="bi bi-shield-lock me-2"></i>Şifreyi Güncelle</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Bildirimler -->
                    <div class="tab-pane fade" id="bildirimler">
                        <div class="card border-0 shadow-sm card-hover">
                            <div class="card-body">
                                <div class="form-section-title mb-3">Bildirim Tercihleri</div>
                                <form action="{{ route('profile.notifications') }}" method="POST" class="row g-3">
                                    @csrf
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="emailNoti" name="email_notifications" value="1" {{ Auth::user()->email_notifications ? 'checked' : '' }}>
                                            <label class="form-check-label" for="emailNoti">E-posta bildirimleri</label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">Kaydet</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Cropper Modal -->
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="width:50vw; max-width:900px; height:50vw; max-height:500px;">
            <div class="modal-content" style="height:100%;">
                <div class="modal-header">
                    <h5 class="modal-title">Profil Resmini Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" style="height: calc(100% - 56px - 56px);">
                    <img id="cropperImage" style="width:100%; height:100%; object-fit:contain; display:block; margin:auto;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary" id="cropSaveBtn">Kaydet</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
    <script>
        function toggleEdit(editMode) {
            document.getElementById('infoView').classList.toggle('d-none', editMode);
            document.getElementById('infoEdit').classList.toggle('d-none', !editMode);
        }
        function confirmLogout() {
            if (confirm('Çıkış yapmak istediğinize emin misiniz?')) {
                document.getElementById('logout-form-main').submit();
            }
        }

        const hash = window.location.hash;
        if (hash) {
            const trigger = document.querySelector(`a[href="${hash}"]`);
            if (trigger) {
                const tab = new bootstrap.Tab(trigger);
                tab.show();
                document.querySelector(hash)?.scrollIntoView({behavior:'smooth', block:'start'});
            }
        }

        let cropper;
        const avatarInput = document.getElementById('avatarInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const cropperImage = document.getElementById('cropperImage');
        const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));

        avatarInput.addEventListener('change', function(e){
            const file = e.target.files[0];
            if(!file) return;
            const reader = new FileReader();
            reader.onload = function(event){
                cropperImage.src = event.target.result;
                cropperModal.show();

                setTimeout(() => {
                    if(cropper) cropper.destroy();
                    cropper = new Cropper(cropperImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        background: false,
                        autoCropArea: 1,
                        responsive: true,
                        modal: true,
                    });
                    cropper.resize();
                }, 100);
            };
            reader.readAsDataURL(file);
        });

        document.getElementById('cropSaveBtn').addEventListener('click', function(){
            const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
            canvas.toBlob(function(blob){
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('avatar', blob, 'avatar.png');
                fetch('{{ route("profile.avatar") }}', {
                    method: 'POST',
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if(data.status === 'ok'){
                            avatarPreview.src = URL.createObjectURL(blob);
                            cropperModal.hide();
                        } else {
                            alert('Bir hata oluştu!');
                        }
                    })
                    .catch(err => console.error(err));
            }, 'image/png');
        });
    </script>
@endpush
