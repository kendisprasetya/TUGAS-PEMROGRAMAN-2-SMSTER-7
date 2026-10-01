<?php
// Memanggil koneksi database dari folder API
require_once __DIR__ . '/../API/koneksi.php';

// Ambil filter tahun atau jurusan jika ada
$search = $_GET['search'] ?? '';
$year = $_GET['year'] ?? '';

$query = "SELECT * FROM alumni WHERE (full_name LIKE ? OR current_activity LIKE ?)";
$params = ["%$search%", "%$search%"];

if (!empty($year)) {
    $query .= " AND graduation_year = ?";
    $params[] = $year;
}

$query .= " ORDER BY graduation_year DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$alumniList = $stmt->fetchAll(); // Diperbaiki dari titik ($stmt.fetchAll) menjadi panah ($stmt->fetchAll)

// Ambil daftar tahun kelulusan untuk filter dropdown
$stmtYears = $pdo->query("SELECT DISTINCT graduation_year FROM alumni ORDER BY graduation_year DESC");
$years = $stmtYears->fetchAll(); // Diperbaiki juga di sini
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Direktori & Tracer Study Alumni - SMAN 1 Nusantara</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-mortarboard-fill text-warning me-2"></i> Portal SMAN 1 Nusantara
            </a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Kembali ke Beranda</a>
        </div>
    </nav>

    <!-- Header Section -->
    <header class="text-white py-5 shadow" style="background: linear-gradient(135deg, #0b1d3a 0%, #1c3d6e 100%);">
        <div class="container py-4 text-center">
            <span class="badge bg-warning text-dark mb-3 px-3 py-2 fw-semibold">TRACER STUDY & DIREKTORI</span>
            <h1 class="display-5 fw-bold mb-3">Jejak Langkah Alumni SMAN 1 Nusantara</h1>
            <p class="lead text-white-50 col-lg-8 mx-auto">Menyaksikan kontribusi nyata para alumni hebat di berbagai institusi terkemuka dunia kerja dan perguruan tinggi.</p>
        </div>
    </header>

    <!-- Konten Utama Direktori -->
    <section class="container my-5">
        
        <!-- Filter & Search Bar -->
        <div class="card shadow-sm border-0 rounded-4 p-4 mb-5 bg-white">
            <form method="GET" action="" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control border-start-0 bg-light" placeholder="Cari nama alumni atau profesi...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="year" class="form-select bg-light">
                        <option value="">Semua Tahun Kelulusan</option>
                        <?php foreach ($years as $y): ?>
                            <option value="<?= $y['graduation_year'] ?>" <?= $year == $y['graduation_year'] ? 'selected' : '' ?>>Angkatan <?= $y['graduation_year'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 fw-semibold">Filter Data</button>
                </div>
            </form>
        </div>

        <!-- Grid Kartu Alumni -->
        <div class="row g-4">
            <?php if (count($alumniList) > 0): foreach ($alumniList as $alumni): ?>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden bg-white">
                        <div class="position-relative">
                            <img src="<?= htmlspecialchars($alumni['photo_url']) ?>" class="card-img-top object-fit-cover" alt="Foto Alumni" style="height: 220px;">
                            <span class="badge bg-dark position-absolute top-0 end-0 m-3 px-3 py-2">Angkatan <?= htmlspecialchars($alumni['graduation_year']) ?></span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 small"><?= htmlspecialchars($alumni['major']) ?></span>
                            </div>
                            <h4 class="card-title fw-bold text-dark fs-5 mb-1"><?= htmlspecialchars($alumni['full_name']) ?></h4>
                            <p class="text-muted small fw-semibold mb-3">
                                <i class="bi bi-briefcase-fill text-warning me-1"></i> <?= htmlspecialchars($alumni['current_activity']) ?> 
                                <?php if (!empty($alumni['university_or_company'])): ?>
                                    di <strong><?= htmlspecialchars($alumni['university_or_company']) ?></strong>
                                <?php endif; ?>
                            </p>
                            <p class="card-text text-muted small fst-italic mt-auto bg-light p-3 rounded-3 border-start border-primary border-4">
                                "<?= htmlspecialchars($alumni['testimonial']) ?>"
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="col-12 text-center py-5">
                    <div class="text-muted fs-5"><i class="bi bi-inbox display-4 d-block mb-2"></i> Data alumni tidak ditemukan.</div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0 text-white-50 small">&copy; 2026 SMAN 1 Unggulan Nusantara. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>