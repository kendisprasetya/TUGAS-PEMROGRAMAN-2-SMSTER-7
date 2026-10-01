<?php
// 1. Wajib dipasang di baris paling atas untuk memproteksi halaman admin
require_once __DIR__ . '/../middleware/auth.php';

// 2. Memanggil koneksi database dari folder API
require_once __DIR__ . '/../API/koneksi.php';

// ... sisa kode CRUD berita Anda selanjutnya ...
// Aksi Hapus Berita
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: crud_berita.php");
    exit;
}

// Aksi Tambah atau Update Berita
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $title = $_POST['title'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $category = $_POST['category'];
    $author_name = $_POST['author_name'];
    $thumbnail_url = $_POST['thumbnail_url'];
    $content = $_POST['content'];
    $status = $_POST['status'];
    $published_at = $_POST['published_at'];

    if ($id) {
        // Proses Update
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, slug = ?, category = ?, author_name = ?, thumbnail_url = ?, content = ?, status = ?, published_at = ? WHERE id = ?");
        $stmt->execute([$title, $slug, $category, $author_name, $thumbnail_url, $content, $status, $published_at, $id]);
    } else {
        // Proses Insert (Tambah Baru)
        $stmt = $pdo->prepare("INSERT INTO posts (title, slug, category, author_name, thumbnail_url, content, status, published_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $category, $author_name, $thumbnail_url, $content, $status, $published_at]);
    }
    header("Location: crud_berita.php");
    exit;
}

// Ambil data jika mode edit dipilih
$editData = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $editData = $stmt->fetch();
}

// Ambil seluruh data berita untuk ditampilkan di tabel
$stmtAll = $pdo->query("SELECT * FROM posts ORDER BY id DESC");
$allPosts = $stmtAll->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita & Konten - SMAN 1 Nusantara</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

    <!-- Top Navbar -->
    <nav class="navbar navbar-dark bg-dark shadow-sm">
        <div class="container">
            <span class="navbar-brand fw-bold"><i class="bi bi-speedometer2 text-warning me-2"></i> Panel CMS Manajemen Berita</span>
            <a href="index.php" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left me-1"></i> Ke Portal Utama</a>
        </div>
    </nav>

    <div class="container my-5">
        <!-- Form Tambah / Edit Berita -->
        <div class="card shadow-sm border-0 mb-5 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white fw-bold py-3 px-4">
                <i class="bi bi-<?= $editData ? 'pencil-square' : 'plus-circle' ?> me-2"></i> 
                <?= $editData ? 'Edit Data Berita' : 'Tambah Berita Baru' ?>
            </div>
            <div class="card-body p-4">
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Berita</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($editData['title'] ?? '') ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="Prestasi Siswa" <?= ($editData['category'] ?? '') == 'Prestasi Siswa' ? 'selected' : '' ?>>Prestasi Siswa</option>
                                <option value="Akademik" <?= ($editData['category'] ?? '') == 'Akademik' ? 'selected' : '' ?>>Akademik</option>
                                <option value="Pengumuman" <?= ($editData['category'] ?? '') == 'Pengumuman' ? 'selected' : '' ?>>Pengumuman</option>
                                <option value="Kegiatan Sekolah" <?= ($editData['category'] ?? '') == 'Kegiatan Sekolah' ? 'selected' : '' ?>>Kegiatan Sekolah</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Penulis</label>
                            <input type="text" name="author_name" class="form-control" value="<?= htmlspecialchars($editData['author_name'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Status Publikasi</label>
                            <select name="status" class="form-select">
                                <option value="Published" <?= ($editData['status'] ?? '') == 'Published' ? 'selected' : '' ?>>Published</option>
                                <option value="Draft" <?= ($editData['status'] ?? '') == 'Draft' ? 'selected' : '' ?>>Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">URL Gambar Thumbnail</label>
                        <input type="text" name="thumbnail_url" class="form-control" value="<?= htmlspecialchars($editData['thumbnail_url'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal & Waktu Terbit</label>
                        <input type="datetime-local" name="published_at" class="form-control" value="<?= isset($editData['published_at']) ? date('Y-m-d\TH:i', strtotime($editData['published_at'])) : date('Y-m-d\TH:i') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Isi Berita / Konten</label>
                        <textarea name="content" class="form-control" rows="4" required><?= htmlspecialchars($editData['content'] ?? '') ?></textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-save me-1"></i> <?= $editData ? 'Update Berita' : 'Simpan Berita' ?></button>
                        <?php if ($editData): ?>
                            <a href="crud_berita.php" class="btn btn-secondary px-4">Batal</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Berita (Read & Delete) -->
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-dark text-white fw-bold py-3 px-4">
                <i class="bi bi-table me-2"></i> Daftar Publikasi Berita
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3 px-3">No</th>
                                <th class="py-3">Judul Berita</th>
                                <th class="py-3">Kategori</th>
                                <th class="py-3">Penulis</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($allPosts)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data berita tersedia.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($allPosts as $index => $post): ?>
                                    <tr>
                                        <td class="px-3"><?= $index + 1 ?></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($post['title']) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($post['category']) ?></span></td>
                                        <td><?= htmlspecialchars($post['author_name']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $post['status'] == 'Published' ? 'success' : 'warning text-dark' ?>">
                                                <?= htmlspecialchars($post['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="crud_berita.php?edit=<?= $post['id'] ?>" class="btn btn-sm btn-warning fw-semibold me-1"><i class="bi bi-pencil-square"></i> Edit</a>
                                            <a href="crud_berita.php?delete=<?= $post['id'] ?>" class="btn btn-sm btn-danger fw-semibold" onclick="return confirm('Yakin ingin menghapus berita ini?')"><i class="bi bi-trash"></i> Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>