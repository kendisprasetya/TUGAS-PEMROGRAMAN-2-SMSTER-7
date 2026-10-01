<?php
// Memanggil koneksi database dari folder API
require_once __DIR__ . '/../API/koneksi.php';

$success_message = '';
$error_message = '';

// Proses penyimpanan data pendaftaran ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ambil data dari form multi-step
        $registration_number = 'PPDB-' . date('Y') . '-' . rand(1000, 9999);
        $full_name           = $_POST['full_name'] ?? '';
        $nisn                = $_POST['nisn'] ?? '';
        $nik                 = $_POST['nik'] ?? '';
        $birth_place         = $_POST['birth_place'] ?? '';
        $birth_date          = $_POST['birth_date'] ?? '';
        $gender              = $_POST['gender'] ?? '';
        $religion            = $_POST['religion'] ?? '';
        $origin_school       = $_POST['origin_school'] ?? '';
        
        // Data Peminatan & Nilai
        $admission_path      = $_POST['admission_path'] ?? '';
        $target_major        = $_POST['target_major'] ?? '';
        $report_avg_score    = $_POST['report_avg_score'] ?? 0.00;
        $zone_distance       = $_POST['zone_distance'] ?? '-';

        // Query Insert ke tabel ppdb_registrations yang sudah ada di database
        $stmt = $pdo->prepare("INSERT INTO ppdb_registrations 
            (registration_number, full_name, nisn, nik, birth_place, birth_date, gender, religion, origin_school, admission_path, target_major, report_avg_score, zone_distance, status_selection, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())");
        
        $stmt->execute([
            $registration_number, $full_name, $nisn, $nik, $birth_place, $birth_date, 
            $gender, $religion, $origin_school, $admission_path, $target_major, 
            $report_avg_score, $zone_distance
        ]);

        $success_message = "Pendaftaran berhasil! Nomor Registrasi Anda: <strong>{$registration_number}</strong>";
    } catch (PDOException $e) {
        $error_message = "Gagal menyimpan pendaftaran: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran PPDB - SMAN 1 Nusantara</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .step-indicator {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-bottom: 2rem;
        }
        .step-item {
            text-align: center;
            z-index: 1;
            flex: 1;
        }
        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px auto;
            font-weight: bold;
        }
        .step-item.active .step-circle {
            background: #212529;
            color: #fff;
        }
        .step-item.completed .step-circle {
            background: #198754;
            color: #fff;
        }
        .step-line {
            position: absolute;
            top: 20px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: #dee2e6;
            z-index: 0;
        }
    </style>
</head>
<body class="bg-light">

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-mortarboard-fill text-warning me-2"></i> Portal SMAN 1 Nusantara
            </a>
            <a href="index.php" class="btn btn-outline-light btn-sm">Kembali ke Beranda</a>
        </div>
    </nav>

    <!-- Header / Judul Form -->
    <div class="container my-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Formulir Penerimaan Peserta Didik Baru (PPDB)</h2>
            <p class="text-muted">Isi setiap bagian dengan seksama sesuai data pada dokumen Kartu Keluarga dan Rapor SMP/MTs asal.</p>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?= $success_message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= $error_message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5 bg-white">
            
            <!-- Step Wizard Indicator (Persis seperti gambar referensi) -->
            <div class="step-indicator px-md-5">
                <div class="step-line"></div>
                <div class="step-item active" id="indicator-step-1">
                    <div class="step-circle">1</div>
                    <small class="fw-bold d-block text-dark">Data Pribadi</small>
                </div>
                <div class="step-item" id="indicator-step-2">
                    <div class="step-circle">2</div>
                    <small class="text-muted d-block">Orang Tua/Wali</small>
                </div>
                <div class="step-item" id="indicator-step-3">
                    <div class="step-circle">3</div>
                    <small class="text-muted d-block">Peminatan</small>
                </div>
                <div class="step-item" id="indicator-step-4">
                    <div class="step-circle">4</div>
                    <small class="text-muted d-block">Unggah Berkas</small>
                </div>
            </div>

            <!-- Form Pendaftaran Multi-Step -->
            <form action="" method="POST" id="formPpdb">
                
                <!-- STEP 1: DATA PRIBADI -->
                <div class="form-step" id="step-1">
                    <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">
                        <i class="bi bi-person-fill text-primary me-2"></i> 1. Data Pribadi Calon Siswa
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Lengkap (Sesuai Ijazah/KK)</label>
                            <input type="text" class="form-control" name="full_name" required placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NISN (Nomor Induk Siswa Nasional)</label>
                            <input type="text" class="form-control" name="nisn" required placeholder="Contoh: 0081234567">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" class="form-control" name="nik" required placeholder="Sesuai Kartu Keluarga">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Asal Sekolah (SMP/MTs)</label>
                            <input type="text" class="form-control" name="origin_school" required placeholder="Nama SMP/MTs Asal">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tempat Lahir</label>
                            <input type="text" class="form-control" name="birth_place" required placeholder="Kota kelahiran">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="birth_date" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Jenis Kelamin</label>
                            <select class="form-select" name="gender" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Agama</label>
                            <select class="form-select" name="religion" required>
                                <option value="">Pilih Agama</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Buddha">Buddha</option>
                                <option value="Konghucu">Konghucu</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-dark px-4" onclick="nextStep(2)">Selanjutnya <i class="bi bi-arrow-right ms-1"></i></button>
                    </div>
                </div>

                <!-- STEP 2: ORANG TUA / WALI -->
                <div class="form-step d-none" id="step-2">
                    <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">
                        <i class="bi bi-people-fill text-primary me-2"></i> 2. Data Orang Tua / Wali
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Ayah Kandung</label>
                            <input type="text" class="form-control" placeholder="Nama ayah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Pekerjaan Ayah</label>
                            <input type="text" class="form-control" placeholder="Pekerjaan ayah">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Nama Ibu Kandung</label>
                            <input type="text" class="form-control" placeholder="Nama ibu">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Pekerjaan Ibu</label>
                            <input type="text" class="form-control" placeholder="Pekerjaan ibu">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Nomor Telepon / WhatsApp Orang Tua</label>
                            <input type="text" class="form-control" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary px-4" onclick="prevStep(1)"><i class="bi bi-arrow-left me-1"></i> Kembali</button>
                        <button type="button" class="btn btn-dark px-4" onclick="nextStep(3)">Selanjutnya <i class="bi bi-arrow-right ms-1"></i></button>
                    </div>
                </div>

                <!-- STEP 3: PEMINATAN & NILAI -->
                <div class="form-step d-none" id="step-3">
                    <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">
                        <i class="bi bi-journal-check text-primary me-2"></i> 3. Jalur Masuk & Peminatan
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jalur Pendaftaran</label>
                            <select class="form-select" name="admission_path" required>
                                <option value="Prestasi Akademik">Prestasi Akademik</option>
                                <option value="Zonasi">Zonasi</option>
                                <option value="Afirmasi">Afirmasi</option>
                                <option value="Perpindahan Tugas">Perpindahan Tugas Orang Tua</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Target Jurusan / Peminatan</label>
                            <select class="form-select" name="target_major" required>
                                <option value="MIPA 1 (Sains)">MIPA 1 (Sains Unggulan)</option>
                                <option value="IPS 1">IPS 1 (Sosial Humaniora)</option>
                                <option value="Bahasa & Budaya">Bahasa & Budaya Global</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Rata-rata Nilai Rapor Semester 1 - 5</label>
                            <input type="number" step="0.01" class="form-control" name="report_avg_score" placeholder="Contoh: 89.50" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Jarak Tempat Tinggal ke Sekolah (Zonasi)</label>
                            <input type="text" class="form-control" name="zone_distance" placeholder="Contoh: 1.4 km">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary px-4" onclick="prevStep(2)"><i class="bi bi-arrow-left me-1"></i> Kembali</button>
                        <button type="button" class="btn btn-dark px-4" onclick="nextStep(4)">Selanjutnya <i class="bi bi-arrow-right ms-1"></i></button>
                    </div>
                </div>

                <!-- STEP 4: UNGGAH BERKAS -->
                <div class="form-step d-none" id="step-4">
                    <h5 class="fw-bold mb-4 text-dark border-bottom pb-2">
                        <i class="bi bi-cloud-upload-fill text-primary me-2"></i> 4. Unggah Berkas Persyaratan
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Scan Rapor Semester 1 - 5 (PDF)</label>
                            <input type="file" class="form-control" accept=".pdf">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Scan Kartu Keluarga / KK (JPG/PNG/PDF)</label>
                            <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                        </div>
                        <div class="col-12">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label small text-muted" for="invalidCheck">
                                    Saya menyatakan bahwa seluruh data yang diisi dan berkas yang diunggah adalah benar dan sesuai dengan dokumen asli.
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary px-4" onclick="prevStep(3)"><i class="bi bi-arrow-left me-1"></i> Kembali</button>
                        <button type="submit" class="btn btn-success px-5 fw-bold"><i class="bi bi-check-circle me-1"></i> Kirim Pendaftaran Final</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- Script Navigasi Multi-Step Wizard -->
    <script>
        function nextStep(step) {
            document.querySelectorAll('.form-step').forEach(el => el.classList.add('d-none'));
            document.getElementById('step-' + step).classList.remove('d-none');

            for (let i = 1; i <= 4; i++) {
                let indicator = document.getElementById('indicator-step-' + i);
                indicator.classList.remove('active', 'completed');
                if (i < step) {
                    indicator.classList.add('completed');
                } else if (i === step) {
                    indicator.classList.add('active');
                }
            }
            window.scrollTo({ top: 150, behavior: 'smooth' });
        }

        function prevStep(step) {
            nextStep(step);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>