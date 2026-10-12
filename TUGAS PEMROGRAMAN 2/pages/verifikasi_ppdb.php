<?php
// 1. Wajib dipasang di baris paling atas untuk memproteksi halaman admin
require_once __DIR__ . '/../middleware/auth.php';

// 2. Memanggil koneksi database dari folder API
require_once __DIR__ . '/../API/koneksi.php';

// Pastikan $pdo tersedia
if (!isset($pdo)) {
    die("Koneksi database gagal. Periksa kembali file API/koneksi.php");
}

// PROSES CREATE / TAMBAH DATA PENDAFTAR BARU
if (isset($_POST['tambah_pendaftar'])) {
    $registration_number = $_POST['registration_number'];
    $full_name           = $_POST['full_name'];
    $nisn                = $_POST['nisn'];
    $nik                 = $_POST['nik'];
    $origin_school       = $_POST['origin_school'];
    $admission_path      = $_POST['admission_path'];
    $target_major        = $_POST['target_major'];
    $report_avg_score    = $_POST['report_avg_score'];
    $zone_distance       = $_POST['zone_distance'];

    $stmt = $pdo->prepare("INSERT INTO ppdb_registrations (registration_number, full_name, nisn, nik, origin_school, admission_path, target_major, report_avg_score, zone_distance, status_selection) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING')");
    $stmt->execute([$registration_number, $full_name, $nisn, $nik, $origin_school, $admission_path, $target_major, $report_avg_score, $zone_distance]);
    
    header("Location: verifikasi_ppdb.php");
    exit();
}

// PROSES UPDATE / VERIFIKASI STATUS
if (isset($_POST['update_verifikasi'])) {
    $id               = $_POST['id_pendaftar'];
    $status_selection = $_POST['status_selection'];

    $stmt = $pdo->prepare("UPDATE ppdb_registrations SET status_selection = ? WHERE id = ?");
    $stmt->execute([$status_selection, $id]);
    
    header("Location: verifikasi_ppdb.php?id=" . $id);
    exit();
}

// PROSES DELETE / HAPUS DATA
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM ppdb_registrations WHERE id = ?");
    $stmt->execute([$id_hapus]);
    
    header("Location: verifikasi_ppdb.php");
    exit();
}

// AMBIL DATA DETAIL UNTUK PANEL SEBELAH KANAN
$selected_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$detail_data = null;

if ($selected_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM ppdb_registrations WHERE id = ?");
    $stmt->execute([$selected_id]);
    $detail_data = $stmt->fetch();
}

if (!$detail_data) {
    $stmt = $pdo->query("SELECT * FROM ppdb_registrations ORDER BY id ASC LIMIT 1");
    $detail_data = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen & Verifikasi Pendaftar PPDB - SIMS Admin</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased flex h-screen overflow-hidden">

    <!-- SIDEBAR KIRI -->
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col justify-between shrink-0 border-r border-slate-800">
        <div>
            <div class="p-5 flex items-center space-x-3 border-b border-slate-800">
                <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h1 class="text-white font-bold text-sm tracking-wide">SIMS Admin</h1>
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-medium">Panel Manajemen</p>
                </div>
            </div>
            <div class="px-4 py-4">
                <p class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider px-3 mb-2">CMS Publik & Akademik</p>
                <nav class="space-y-1">
                    <a href="dashboard_cms.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition">
                        <i class="fa-solid fa-square-poll-vertical w-5"></i>
                        <span>Ringkasan SIMS</span>
                    </a>
                    <a href="crud_berita.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition">
                        <i class="fa-solid fa-newspaper w-5"></i>
                        <span>Kelola Berita & Profil</span>
                    </a>
                    <a href="verifikasi_ppdb.php" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white shadow-md transition">
                        <i class="fa-solid fa-user-check w-5"></i>
                        <span>Verifikasi PPDB</span>
                    </a>
                </nav>
            </div>
        </div>
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <a href="index.php" class="flex items-center space-x-3 mb-4 text-xs font-medium text-slate-400 hover:text-white transition">
                <i class="fa-solid fa-globe"></i>
                <span>Lihat Portal Publik</span>
            </a>
            <div class="flex items-center space-x-3 bg-slate-800/60 p-2.5 rounded-xl border border-slate-700/50">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-slate-600">
                <div class="overflow-hidden">
                    <h4 class="text-xs font-semibold text-white truncate">Dr. H. Sudirman</h4>
                    <p class="text-[11px] text-slate-400 truncate">Kepala SIMS & IT</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <!-- TOP NAVBAR -->
        <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center space-x-3">
                <span class="font-bold text-slate-900 text-sm">SMA Negeri Unggulan 1 Nusantara</span>
            </div>
            <div class="flex items-center space-x-4">
                <a href="index.php" class="text-xs font-semibold text-blue-600 hover:underline flex items-center space-x-1.5 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100">
                    <span>Kembali ke Portal Web</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </header>

        <!-- KONTEN BERGULIR -->
        <div class="flex-1 overflow-y-auto p-8 space-y-6">
            
            <!-- HEADER JUDUL -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500 mb-1">
                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded">MODUL ADMISI 2025/2026</span>
                        <span>•</span>
                        <span>DATABASE: portal_sekolah_db (ppdb_registrations)</span>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen & Verifikasi Pendaftar PPDB</h2>
                    <p class="text-xs text-slate-500">Validasi dokumen digital calon peserta didik dan audit kelengkapan berkas rapor[cite: 16].</p>
                </div>
                <!-- Tombol Tambah Data Baru -->
                <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-4 py-2.5 rounded-lg shadow transition flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Data Pendaftar</span>
                </button>
            </div>

            <!-- GRID UTAMA -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- KOLOM KIRI: TABEL DATA -->
                <div class="lg:col-span-8 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 bg-slate-50 border-b border-slate-200">
                        <h3 class="font-bold text-xs uppercase tracking-wider text-slate-700">Daftar Berkas Calon Siswa</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-100/70 border-b border-slate-200 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="p-3">No. Pendaftaran</th>
                                    <th class="p-3">Nama Siswa & NISN</th>
                                    <th class="p-3">Target Jurusan</th>
                                    <th class="p-3">Rapor</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 text-xs">
                                <?php
                                $stmt_tampil = $pdo->query("SELECT * FROM ppdb_registrations ORDER BY id DESC");
                                $rows = $stmt_tampil->fetchAll();
                                
                                if (count($rows) > 0) {
                                    foreach ($rows as $row) {
                                        $status = $row['status_selection'] ?? 'PENDING';
                                        $badge_color = 'bg-amber-100 text-amber-800';
                                        if ($status == 'DITERIMA' || $status == 'ACCEPTED') $badge_color = 'bg-emerald-100 text-emerald-800';
                                        if ($status == 'DITOLAK' || $status == 'REJECTED') $badge_color = 'bg-rose-100 text-rose-800';
                                ?>
                                <tr class="hover:bg-slate-50 transition <?php echo ($selected_id == $row['id']) ? 'bg-blue-50/50' : ''; ?>">
                                    <td class="p-3 font-semibold text-blue-600">
                                        <a href="verifikasi_ppdb.php?id=<?php echo $row['id']; ?>" class="hover:underline">
                                            <?php echo htmlspecialchars($row['registration_number'] ?? 'PPDB-' . $row['id']); ?>
                                        </a>
                                    </td>
                                    <td class="p-3">
                                        <div class="font-bold text-slate-900"><?php echo htmlspecialchars($row['full_name']); ?></div>
                                        <div class="text-[11px] text-slate-500">NISN: <?php echo htmlspecialchars($row['nisn'] ?? '-'); ?></div>
                                    </td>
                                    <td class="p-3">
                                        <span class="bg-slate-200 text-slate-800 font-bold px-2 py-0.5 rounded text-[10px]"><?php echo htmlspecialchars($row['target_major'] ?? '-'); ?></span>
                                    </td>
                                    <td class="p-3 font-semibold"><?php echo htmlspecialchars($row['report_avg_score'] ?? '0.00'); ?></td>
                                    <td class="p-3">
                                        <span class="<?php echo $badge_color; ?> px-2 py-0.5 rounded-full text-[10px] font-bold">
                                            <?php echo htmlspecialchars($status); ?>
                                        </span>
                                    </td>
                                    <td class="p-3 text-center space-x-2">
                                        <a href="verifikasi_ppdb.php?id=<?php echo $row['id']; ?>" class="text-blue-600 hover:text-blue-800 font-semibold" title="Pilih & Verifikasi">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="verifikasi_ppdb.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="text-rose-600 hover:text-rose-800" title="Hapus Data">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='p-6 text-center text-slate-400'>Belum ada data pendaftar dalam database. Silakan tambah data baru.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- KOLOM KANAN: PANEL DETAIL & FORM UPDATE -->
                <div class="lg:col-span-4 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <?php if ($detail_data): ?>
                    <div class="bg-slate-900 text-white p-4 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-semibold text-slate-400 uppercase">ID: <?php echo htmlspecialchars($detail_data['registration_number'] ?? 'PPDB-' . $detail_data['id']); ?></p>
                            <h3 class="font-bold text-sm text-white"><?php echo htmlspecialchars($detail_data['full_name']); ?></h3>
                        </div>
                        <span class="bg-amber-500 text-slate-950 px-2 py-0.5 rounded text-[10px] font-bold"><?php echo htmlspecialchars($detail_data['status_selection'] ?? 'PENDING'); ?></span>
                    </div>

                    <div class="p-4 space-y-4 bg-slate-50/50">
                        <div class="bg-white p-3 rounded-lg border border-slate-200 text-xs space-y-1.5">
                            <p><span class="text-slate-500">NISN:</span> <strong class="text-slate-800"><?php echo htmlspecialchars($detail_data['nisn'] ?? '-'); ?></strong></p>
                            <p><span class="text-slate-500">NIK:</span> <strong class="text-slate-800"><?php echo htmlspecialchars($detail_data['nik'] ?? '-'); ?></strong></p>
                            <p><span class="text-slate-500">Sekolah Asal:</span> <strong class="text-slate-800"><?php echo htmlspecialchars($detail_data['origin_school'] ?? '-'); ?></strong></p>
                            <p><span class="text-slate-500">Jalur Masuk:</span> <strong class="text-slate-800"><?php echo htmlspecialchars($detail_data['admission_path'] ?? '-'); ?></strong></p>
                            <p><span class="text-slate-500">Target Jurusan:</span> <strong class="text-slate-800"><?php echo htmlspecialchars($detail_data['target_major'] ?? '-'); ?></strong></p>
                            <p><span class="text-slate-500">Rata-rata Rapor:</span> <strong class="text-slate-800"><?php echo htmlspecialchars($detail_data['report_avg_score'] ?? '-'); ?></strong></p>
                            <p><span class="text-slate-500">Jarak Zonasi:</span> <strong class="text-slate-800"><?php echo htmlspecialchars($detail_data['zone_distance'] ?? '-'); ?></strong></p>
                        </div>

                        <!-- FORM UPDATE STATUS SELEKSI -->
                        <form action="" method="POST" class="space-y-3">
                            <input type="hidden" name="id_pendaftar" value="<?php echo $detail_data['id']; ?>">
                            
                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">Tetapkan Keputusan Seleksi</label>
                                <select name="status_selection" class="w-full bg-white border border-slate-300 rounded-lg p-2 text-xs font-semibold focus:outline-none">
                                    <option value="PENDING" <?php echo ($detail_data['status_selection'] == 'PENDING') ? 'selected' : ''; ?>>Pending</option>
                                    <option value="DITERIMA" <?php echo ($detail_data['status_selection'] == 'DITERIMA') ? 'selected' : ''; ?>>Terima</option>
                                    <option value="DITOLAK" <?php echo ($detail_data['status_selection'] == 'DITOLAK') ? 'selected' : ''; ?>>Tolak</option>
                                </select>
                            </div>

                            <button type="submit" name="update_verifikasi" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-2.5 rounded-lg shadow transition flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span>Simpan & Mutakhirkan Status</span>
                            </button>
                        </form>
                    </div>
                    <?php else: ?>
                    <div class="p-6 text-center text-slate-400 text-xs">Pilih salah satu data dari tabel sebelah kiri untuk melihat detail dan melakukan verifikasi.</div>
                    <?php endif; ?>
                </div>

            </div>

        </div>
    </div>

    <!-- MODAL TAMBAH DATA (CREATE) -->
    <div id="modalTambah" class="hidden fixed inset-0 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-lg w-full overflow-hidden">
            <div class="bg-slate-900 text-white p-4 flex items-center justify-between">
                <h3 class="font-bold text-sm">Tambah Data Pendaftar Baru</h3>
                <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="text-slate-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="" method="POST" class="p-5 space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">No. Pendaftaran</label>
                        <input type="text" name="registration_number" required placeholder="PPDB-2025-XXXX" class="w-full border border-slate-300 rounded-lg p-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">NISN</label>
                        <input type="text" name="nisn" required placeholder="0078192831" class="w-full border border-slate-300 rounded-lg p-2 text-xs">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">NIK</label>
                        <input type="text" name="nik" placeholder="3201..." class="w-full border border-slate-300 rounded-lg p-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Lengkap Siswa</label>
                        <input type="text" name="full_name" required placeholder="Nama Lengkap" class="w-full border border-slate-300 rounded-lg p-2 text-xs">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Sekolah Asal</label>
                        <input type="text" name="origin_school" placeholder="SMP Negeri 1..." class="w-full border border-slate-300 rounded-lg p-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Jalur Masuk</label>
                        <input type="text" name="admission_path" placeholder="Prestasi Akademik" class="w-full border border-slate-300 rounded-lg p-2 text-xs">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Target Jurusan</label>
                        <input type="text" name="target_major" placeholder="MIPA 1" class="w-full border border-slate-300 rounded-lg p-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Rata-rata Rapor</label>
                        <input type="number" step="0.01" name="report_avg_score" placeholder="89.40" class="w-full border border-slate-300 rounded-lg p-2 text-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Jarak Zonasi</label>
                        <input type="text" name="zone_distance" placeholder="1.4 km" class="w-full border border-slate-300 rounded-lg p-2 text-xs">
                    </div>
                </div>
                <div class="pt-3 flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')" class="bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-xs font-semibold">Batal</button>
                    <button type="submit" name="tambah_pendaftar" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-700">Simpan Pendaftar</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>