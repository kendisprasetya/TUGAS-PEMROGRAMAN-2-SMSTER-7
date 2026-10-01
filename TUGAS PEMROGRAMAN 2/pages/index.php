<?php
// Memanggil koneksi database dari folder API
require_once __DIR__ . '/../API/koneksi.php';

// Ambil data statistik sekolah
$stmtStats = $pdo->query("SELECT * FROM school_statistics");
$statistics = $stmtStats->fetchAll();

// Ambil data berita dari tabel posts yang benar
$stmtPosts = $pdo->query("SELECT * FROM posts ORDER BY id DESC LIMIT 4");
$posts = $stmtPosts->fetchAll();

// Ambil data fasilitas sekolah (Ekosistem Belajar)
$stmtFacilities = $pdo->query("SELECT * FROM facilities LIMIT 3");
$facilities = $stmtFacilities->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Resmi SMAN 1 Nusantara</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- CSS Kustom untuk Tampilan Elegan & Background Navigasi -->
    <style>
        .custom-navbar {
            background: linear-gradient(135deg, #0b1d3a 0%, #152c52 100%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        .navbar-nav .nav-link {
            position: relative;
            transition: all 0.3s ease-in-out;
            padding: 8px 14px !important;
            border-radius: 8px;
        }
        .navbar-nav .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.08);
            transform: translateY(-1px);
        }
        .btn-elegant-ppdb {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            border: none;
            color: #0b1d3a !important;
            font-weight: 700;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.2);
            border-radius: 8px;
        }
        .btn-elegant-ppdb:hover {
            background: linear-gradient(135deg, #ffca2c 0%, #ffb300 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
        }
    </style>
</head>
<body class="bg-light">

    <!-- Top Navigation Bar / Navbar Menu Publik yang Elegan -->
    <nav class="navbar navbar-expand-lg navbar-dark custom-navbar sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-5 tracking-wide" href="index.php">
                <i class="bi bi-mortarboard-fill text-warning me-2"></i> Portal SMAN 1 Nusantara
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white-50" href="alumni.php"><i class="bi bi-people-fill me-1 text-warning"></i> Direktori Alumni</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-elegant-ppdb btn-sm px-4 py-2" href="pendaftaran.php"><i class="bi bi-mortarboard me-1"></i> Pendaftaran PPDB</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section / Header Utama -->
    <header class="text-white py-5 shadow" style="background: linear-gradient(135deg, #0b1d3a 0%, #1c3d6e 100%);">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 fw-semibold">AKREDITASI A (UNGGUL) BAN-S/M 2024</span>
                    <h1 class="display-5 fw-bold mb-3">Mewujudkan Generasi Unggul, Berkarakter & Berdaya Saing Global</h1>
                    <p class="lead text-white-50">Pusat keunggulan pendidikan sains terapan, literasi humaniora, dan pembentukan watak luhur berasakan profil Pelajar Pancasila.</p>
                    <div class="mt-4">
                        <a href="#kabar" class="btn btn-warning fw-semibold px-4 py-2 me-2">Eksplorasi Portal</a>
                        <a href="pendaftaran.php" class="btn btn-outline-light fw-semibold px-4 py-2">Pendaftaran PPDB</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Statistik Sekolah -->
    <section class="container my-5">
        <div class="row text-center g-4">
            <?php foreach ($statistics as $stat): ?>
                <div class="col-md-3">
                    <div class="card p-4 shadow-sm border-0 h-100 bg-white rounded-4">
                        <h2 class="text-primary fw-bold mb-2"><?= htmlspecialchars($stat['value_number']) ?></h2>
                        <h5 class="fw-semibold text-dark"><?= htmlspecialchars($stat['label']) ?></h5>
                        <p class="text-muted small mb-0"><?= htmlspecialchars($stat['sub_label']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Kabar Nusantara Terkini (Berita) -->
    <section id="kabar" class="container my-5 py-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0"><i class="bi bi-newspaper text-primary me-2"></i> Kabar Nusantara Terkini</h3>
        </div>
        <div class="row g-4">
            <?php foreach ($posts as $post): ?>
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        <img src="<?= htmlspecialchars($post['thumbnail_url'] ?? 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400&auto=format&fit=crop&q=80') ?>" class="card-img-top" alt="Thumbnail" style="height: 160px; object-fit: cover;">
                        <div class="card-body d-flex flex-column p-4">
                            <span class="badge bg-primary bg-opacity-15 text-primary align-self-start mb-2 px-2 py-1"><?= htmlspecialchars($post['category']) ?></span>
                            <h6 class="card-title fw-bold text-dark mb-2"><?= htmlspecialchars($post['title']) ?></h6>
                            <p class="card-text small text-muted mt-auto"><?= substr(strip_tags($post['content'] ?? ''), 0, 75) ?>...</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Ekosistem Belajar (Fasilitas Sekolah) -->
    <section class="bg-white py-5 border-top shadow-sm">
        <div class="container my-3">
            <div class="text-center mb-5">
                <span class="text-uppercase text-primary small fw-bold tracking-wider">FASILITAS STANDAR GLOBAL</span>
                <h2 class="fw-bold mt-1">Ekosistem Belajar yang Mendukung Eksplorasi</h2>
                <p class="text-muted col-lg-6 mx-auto">Disediakan untuk mengasah potensi intelektual, kebugaran jasmani, serta ekspresi seni para siswa secara komprehensif.</p>
            </div>
            <div class="row g-4">
                <?php foreach ($facilities as $facility): ?>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                            <img src="<?= htmlspecialchars($facility['image_url']) ?>" class="card-img-top" alt="Fasilitas" style="height: 200px; object-fit: cover;">
                            <div class="card-body p-4">
                                <span class="badge bg-info bg-opacity-20 text-dark mb-2 px-2 py-1"><?= htmlspecialchars($facility['category']) ?></span>
                                <h5 class="card-title fw-bold text-dark"><?= htmlspecialchars($facility['title']) ?></h5>
                                <p class="card-text small text-muted mb-0"><?= htmlspecialchars($facility['description']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0 text-white-50 small">&copy; 2026 SMAN 1 Unggulan Nusantara. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>