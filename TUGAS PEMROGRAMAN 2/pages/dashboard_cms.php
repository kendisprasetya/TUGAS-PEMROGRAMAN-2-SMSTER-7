<?php
// Memanggil middleware autentikasi session agar aman
require_once __DIR__ . '/../middleware/auth.php';
// Memanggil koneksi database dari folder API
require_once __DIR__ . '/../API/koneksi.php';

// 1. Mengambil data statistik kartu atas
$stmtStats = $pdo->query("SELECT 
    COUNT(*) AS total_pendaftar,
    SUM(CASE WHEN status_selection = 'Diterima' THEN 1 ELSE 0 END) AS total_lulus,
    SUM(CASE WHEN status_selection = 'Pending' THEN 1 ELSE 0 END) AS total_pending,
    SUM(CASE WHEN document_status = 'Belum Lengkap' THEN 1 ELSE 0 END) AS total_belum_lengkap
FROM ppdb_registrations");
$stats = $stmtStats->fetch();

// 2. Mengambil data antrean berkas PPDB terbaru
$stmtPpdb = $pdo->query("SELECT * FROM ppdb_registrations ORDER BY id DESC LIMIT 3");
$listPpdb = $stmtPpdb->fetchAll();

// 3. Mengambil data berita/artikel portal terbaru
$stmtBerita = $pdo->query("SELECT * FROM posts ORDER BY id DESC LIMIT 3");
$listBerita = $stmtBerita->fetchAll();

// 4. Mengambil data pesan aspirasi masuk terbaru
$stmtAspirasi = $pdo->query("SELECT * FROM contact_aspirations ORDER BY created_at DESC LIMIT 2");
$listAspirasi = $stmtAspirasi->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan SIMS - Panel Manajemen CMS</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .fs-7 { font-size: 0.85rem; }
        .fs-8 { font-size: 0.75rem; }
        .sidebar { background-color: #1e2530 !important; }
        .nav-link.active { background-color: #0d6efd !important; }
        .hover-shadow:hover { transform: translateY(-2px); transition: all 0.2s ease-in-out; }
    </style>
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="row">
            
            <!-- Sidebar Navigasi Kiri -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse text-white min-vh-100 p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="position-sticky pt-2">
                        <div class="mb-4 px-2 d-flex align-items-center">
                            <div class="bg-primary text-white rounded p-1 me-2 fw-bold px-2">S</div>
                            <div>
                                <h6 class="fw-bold text-white mb-0">SIMS Admin</h6>
                                <small class="text-warning fs-8">PANEL MANAJEMEN</small>
                            </div>
                        </div>
                        <h6 class="sidebar-heading px-2 text-muted text-uppercase fs-8 mb-2">CMS Publik & Akademik</h6>
                        <ul class="nav flex-column gap-1">
                            <li class="nav-item">
                                <a class="nav-link text-white active rounded-2 py-2" href="dashboard_cms.php">
                                    <i class="bi bi-grid-1x2-fill me-2"></i> Ringkasan SIMS
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white-50 hover-white py-2" href="crud_berita.php">
                                    <i class="bi bi-journal-text me-2"></i> Kelola Berita & Profil
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white-50 hover-white py-2" href="verifikasi_ppdb.php">
                                    <i class="bi bi-check2-square me-2"></i> Verifikasi PPDB
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bagian Bawah Sidebar (Profil & Tombol Logout) -->
                <div>
                    <hr class="border-secondary my-3">
                    <div class="px-2 mb-3">
                        <a href="index.php" class="text-decoration-none text-white-50 small d-block mb-3">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Portal Publik
                        </a>
                        <div class="d-flex align-items-center bg-secondary bg-opacity-25 p-2 rounded-3 mb-2">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&q=80" class="rounded-circle me-2" width="35" height="35" alt="Avatar">
                            <div>
                                <h6 class="mb-0 text-white fs-7 fw-bold"><?= htmlspecialchars($_SESSION['full_name'] ?? 'Administrator Utama') ?></h6>
                                <small class="text-muted fs-8"><?= htmlspecialchars($_SESSION['role'] ?? 'Admin') ?></small>
                            </div>
                        </div>
                    </div>
                    <!-- Tombol Logout -->
                    <a href="logout.php" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?')" class="btn btn-outline-danger btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                    </a>
                </div>
            </nav>

            <!-- Konten Utama Dashboard -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                
                <!-- Header Atas Dashboard -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-1">
                                <li class="breadcrumb-item text-muted small">CMS Panel</li>
                                <li class="breadcrumb-item text-primary small fw-semibold active" aria-current="page">Ringkasan Sistem Eksekutif</li>
                            </ol>
                        </nav>
                        <h2 class="h3 fw-bold text-dark mb-1">Pusat Kendali Informasi & Layanan Akademik</h2>
                        <p class="text-muted small mb-0">Tahun Ajaran 2024/2025 Semester Genap &bull; Terakhir disinkronkan: Hari ini, 09:42 WIB</p>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
                        <a href="index.php" class="btn btn-outline-secondary btn-sm fw-semibold bg-white">Portal PPDB Live</a>
                        <a href="index.php" class="btn btn-light border btn-sm fw-semibold">Lihat Website Publik</a>
                        <a href="crud_berita.php" class="btn btn-dark btn-sm fw-semibold"><i class="bi bi-plus-lg me-1"></i> Tulis Berita Baru</a>
                    </div>
                </div>

                <!-- 4 Shortcut Menu Tombol Cepat -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <a href="crud_berita.php" class="card text-decoration-none shadow-sm border-0 p-3 h-100 rounded-4 hover-shadow bg-white">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                                    <i class="bi bi-journal-text fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 fs-7">Kelola Berita</h6>
                                    <small class="text-muted fs-8">Publikasi & Agenda</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="verifikasi_ppdb.php" class="card text-decoration-none shadow-sm border-0 p-3 h-100 rounded-4 hover-shadow bg-white">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-25 text-warning p-3 rounded-3 me-3">
                                    <i class="bi bi-shield-check fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 fs-7">Verifikasi PPDB</h6>
                                    <small class="text-danger fw-semibold fs-8">89 Butuh Review</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 p-3 h-100 rounded-4 bg-white">
                            <div class="d-flex align-items-center">
                                <div class="bg-info bg-opacity-10 text-info p-3 rounded-3 me-3">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 fs-7">Direktori Guru & Siswa</h6>
                                    <small class="text-muted fs-8">Master Data Pokok</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 p-3 h-100 rounded-4 bg-white">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary bg-opacity-10 text-secondary p-3 rounded-3 me-3">
                                    <i class="bi bi-gear fs-4"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1 fs-7">Pengaturan CMS</h6>
                                    <small class="text-muted fs-8">SEO, Banner & Hak Akses</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4 Statistik Angka Utama -->
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 p-4 rounded-4 bg-white h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-uppercase text-muted fs-8 fw-bold">Statistik Lalu Lintas</span>
                                <i class="bi bi-eye text-primary fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">48.210</h3>
                            <span class="badge bg-success bg-opacity-15 text-success align-self-start fw-semibold fs-8"><i class="bi bi-arrow-up-short"></i> +14.2% vs bulan sebelumnya</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 p-4 rounded-4 bg-white h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-uppercase text-muted fs-8 fw-bold">Publikasi Konten</span>
                                <i class="bi bi-newspaper text-primary fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">64</h3>
                            <div class="text-muted fs-8 mt-1"><span class="fw-bold text-warning">4 Draft Pending</span> &bull; 60 terbit aktif</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 p-4 rounded-4 bg-white h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-uppercase text-muted fs-8 fw-bold">Pendaftar PPDB 2025</span>
                                <i class="bi bi-mortarboard text-warning fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1"><?= $stats['total_pendaftar'] ?? 0 ?></h3>
                            <div class="text-muted fs-8 mt-1"><span class="fw-bold text-danger"><?= $stats['total_pending'] ?? 0 ?> Antrean Verifikasi</span> &bull; Target 420 kuota</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 p-4 rounded-4 bg-white h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="text-uppercase text-muted fs-8 fw-bold">Aspirasi & Kontak</span>
                                <i class="bi bi-envelope text-primary fs-4"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-1">12</h3>
                            <div class="text-muted fs-8 mt-1"><span class="badge bg-danger">Belum Dibalas</span> &bull; 96 pesan terselesaikan</div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Tengah: Analisis Portal Publik (Grafik Batang) & Sebaran Minat Siswa (Donut) -->
                <div class="row g-4 mb-4">
                    <!-- Grafik Batang Kunjungan Website -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                            <div class="mb-3">
                                <span class="text-uppercase text-muted fs-8 fw-bold d-block">Analisis Portal Publik</span>
                                <h5 class="fw-bold text-dark mb-2 fs-6">Tren Kunjungan Website (7 Hari Terakhir)</h5>
                                <div>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="Filter Waktu">
                                        <input type="radio" class="btn-check" name="btnradio" id="btnradio1" checked>
                                        <label class="btn btn-outline-secondary fs-8 px-2 py-1" for="btnradio1">Mingguan</label>
                                        <input type="radio" class="btn-check" name="btnradio" id="btnradio2">
                                        <label class="btn btn-outline-secondary fs-8 px-2 py-1" for="btnradio2">Bulanan</label>
                                        <input type="radio" class="btn-check" name="btnradio" id="btnradio3">
                                        <label class="btn btn-outline-secondary fs-8 px-2 py-1" for="btnradio3">Tahunan</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Simulasi Visual Bar Chart Kunjungan -->
                            <div class="d-flex align-items-end justify-content-around border-bottom pb-3 mb-3 pt-2" style="height: 200px;">
                                <div class="text-center">
                                    <small class="text-muted d-block mb-1 fs-8">5.8k</small>
                                    <div class="bg-dark rounded-top mx-auto" style="width: 32px; height: 110px;"></div>
                                    <span class="small text-muted d-block mt-2 fs-8">Sen</span>
                                </div>
                                <div class="text-center">
                                    <small class="text-muted d-block mb-1 fs-8">6.9k</small>
                                    <div class="bg-dark rounded-top mx-auto" style="width: 32px; height: 135px;"></div>
                                    <span class="small text-muted d-block mt-2 fs-8">Sel</span>
                                </div>
                                <div class="text-center">
                                    <small class="text-dark fw-bold d-block mb-1 fs-8">8.4k</small>
                                    <div class="bg-warning rounded-top mx-auto" style="width: 32px; height: 170px;" title="Puncak Traffic Pengumuman (Rabu)"></div>
                                    <span class="small text-muted d-block mt-2 fs-8">Rab</span>
                                </div>
                                <div class="text-center">
                                    <small class="text-muted d-block mb-1 fs-8">6.2k</small>
                                    <div class="bg-dark rounded-top mx-auto" style="width: 32px; height: 120px;"></div>
                                    <span class="small text-muted d-block mt-2 fs-8">Kam</span>
                                </div>
                                <div class="text-center">
                                    <small class="text-muted d-block mb-1 fs-8">5.1k</small>
                                    <div class="bg-dark rounded-top mx-auto" style="width: 32px; height: 100px;"></div>
                                    <span class="small text-muted d-block mt-2 fs-8">Jum</span>
                                </div>
                                <div class="text-center">
                                    <small class="text-muted d-block mb-1 fs-8">4.3k</small>
                                    <div class="bg-secondary rounded-top mx-auto" style="width: 32px; height: 80px;"></div>
                                    <span class="small text-muted d-block mt-2 fs-8">Sab</span>
                                </div>
                                <div class="text-center">
                                    <small class="text-muted d-block mb-1 fs-8">3.8k</small>
                                    <div class="bg-secondary rounded-top mx-auto" style="width: 32px; height: 70px;"></div>
                                    <span class="small text-muted d-block mt-2 fs-8">Min</span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="d-flex gap-3 flex-wrap">
                                    <span class="fs-8 text-muted"><span class="badge bg-warning p-1 me-1"></span> Puncak Traffic Pengumuman (Rabu)</span>
                                    <span class="fs-8 text-muted"><span class="badge bg-dark p-1 me-1"></span> Aktivitas Hari Sekolah</span>
                                </div>
                                <small class="text-muted fw-semibold fs-8">Rata-rata 6.887 User / Hari</small>
                            </div>
                        </div>
                    </div>

                    <!-- Sebaran Minat Siswa Baru (Grafik Donut & Progress) -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0 rounded-4 p-4 bg-white h-100">
                            <span class="text-uppercase text-muted fs-8 fw-bold">Sebaran Minat Siswa Baru</span>
                            <h5 class="fw-bold text-dark mb-3">Pendaftar Jalur & Jurusan</h5>

                            <div class="text-center position-relative my-2">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle border border-4 border-dark p-4" style="width: 120px; height: 120px;">
                                    <div class="text-center">
                                        <h5 class="fw-bold mb-0">382</h5>
                                        <small class="text-muted fs-8" style="font-size: 10px;">TOTAL CALON</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-1 fs-8">
                                    <span class="fw-bold text-dark"><span class="badge bg-dark rounded-circle p-1 me-1"></span> MIPA (Sains Unggulan)</span>
                                    <span>210 Siswa <strong class="text-dark">55%</strong></span>
                                </div>
                                <div class="progress mb-3" style="height: 5px;"><div class="progress-bar bg-dark" style="width: 55%"></div></div>

                                <div class="d-flex justify-content-between align-items-center mb-1 fs-8">
                                    <span class="fw-bold text-dark"><span class="badge bg-warning rounded-circle p-1 me-1"></span> IPS (Sosial Humaniora)</span>
                                    <span>134 Siswa <strong class="text-dark">35%</strong></span>
                                </div>
                                <div class="progress mb-3" style="height: 5px;"><div class="progress-bar bg-warning" style="width: 35%"></div></div>

                                <div class="d-flex justify-content-between align-items-center mb-1 fs-8">
                                    <span class="fw-bold text-dark"><span class="badge bg-secondary rounded-circle p-1 me-1"></span> Bahasa & Budaya Global</span>
                                    <span>38 Siswa <strong class="text-dark">10%</strong></span>
                                </div>
                                <div class="progress" style="height: 5px;"><div class="progress-bar bg-secondary" style="width: 10%"></div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Tabel: Daftar Publikasi Berita Portal -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4 bg-white">
                            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <span class="badge bg-warning text-dark mb-1">Terbaru</span>
                                    <h5 class="fw-bold text-dark mb-0">Daftar Publikasi Berita Portal</h5>
                                </div>
                                <div class="d-flex gap-2">
                                    <input type="text" class="form-control form-control-sm" placeholder="Cari judul berita atau penulis..." style="width: 240px;">
                                    <a href="crud_berita.php" class="btn btn-outline-secondary btn-sm fw-semibold">Lihat Semua Konten (64)</a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light fs-8">
                                            <tr>
                                                <th class="px-4 py-3">JUDUL BERITA & CUPLIKAN</th>
                                                <th class="py-3">KATEGORI</th>
                                                <th class="py-3">TANGGAL UNGGAH</th>
                                                <th class="py-3">PENULIS</th>
                                                <th class="py-3 text-end px-4">STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($listBerita) > 0): foreach ($listBerita as $berita): ?>
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="<?= htmlspecialchars($berita['thumbnail_url'] ?? 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=100&q=80') ?>" class="rounded me-3 object-fit-cover" width="50" height="35" alt="News">
                                                        <div>
                                                            <div class="fw-bold text-dark fs-7"><?= htmlspecialchars($berita['title']) ?></div>
                                                            <small class="text-muted fs-8"><?= substr(strip_tags($berita['content']), 0, 50) ?>...</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-light text-dark border fs-8"><?= htmlspecialchars($berita['category']) ?></span></td>
                                                <td><small class="text-muted fs-8"><?= htmlspecialchars($berita['published_at'] ?? '-') ?></small></td>
                                                <td><span class="fs-8"><?= htmlspecialchars($berita['author_name'] ?? 'Admin') ?></span></td>
                                                <td class="text-end px-4"><span class="badge bg-success bg-opacity-15 text-success fs-8"><?= htmlspecialchars($berita['status'] ?? 'Published') ?></span></td>
                                            </tr>
                                            <?php endforeach; else: ?>
                                            <tr><td colspan="5" class="text-center py-3 text-muted small">Belum ada data berita.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bagian Bawah: Antrean Berkas PPDB & Pesan Masuk -->
                <div class="row g-4">
                    <!-- Tabel Antrean Berkas PPDB -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0 rounded-4 bg-white h-100">
                            <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-uppercase text-muted fs-8 fw-bold">Pendaftaran Terbaru</span>
                                    <h5 class="fw-bold text-dark mb-0">Antrean Berkas PPDB 2025</h5>
                                </div>
                                <a href="verifikasi_ppdb.php" class="text-decoration-none fw-semibold small text-primary">Buka Meja Verifikasi &rarr;</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light fs-8">
                                            <tr>
                                                <th class="px-4 py-3">CALON PESERTA DIDIK</th>
                                                <th class="py-3">JALUR & PEMINATAN</th>
                                                <th class="py-3">STATUS BERKAS</th>
                                                <th class="py-3 text-end px-4">AKSI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (count($listPpdb) > 0): foreach ($listPpdb as $ppdb): ?>
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-dark rounded-circle p-2">CS</span>
                                                        <div>
                                                            <div class="fw-bold text-dark fs-7"><?= htmlspecialchars($ppdb['full_name']) ?> <span class="badge bg-dark fs-8"><?= htmlspecialchars($ppdb['target_major']) ?></span></div>
                                                            <small class="text-muted fs-8"><?= htmlspecialchars($ppdb['origin_school']) ?> &bull; Nilai Rata-rata: <?= htmlspecialchars($ppdb['report_avg_score']) ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><span class="fs-8 text-muted"><?= htmlspecialchars($ppdb['admission_path']) ?></span></td>
                                                <td><span class="badge bg-warning bg-opacity-25 text-dark fs-8"><?= htmlspecialchars($ppdb['status_selection'] ?? 'Pending') ?></span></td>
                                                <td class="text-end px-4"><a href="verifikasi_ppdb.php" class="btn btn-sm btn-dark px-3 fs-8">Periksa</a></td>
                                            </tr>
                                            <?php endforeach; else: ?>
                                            <tr><td colspan="4" class="text-center py-3 text-muted small">Belum ada antrean pendaftar.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>