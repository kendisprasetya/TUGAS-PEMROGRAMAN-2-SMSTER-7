<?php
$host     = 'mysql-7277fa0-kubielfigma-3e00.h.aivencloud.com'; // Sesuaikan host Anda
$port     = '10618';                                            // Sesuaikan port Aiven Anda
$dbname   = 'defaultdb';                                        
$username = 'avnadmin';                                         
$password = 'AVNS_kflNsgVTYX1zXQ5XHlC';                     // Masukkan password Aiven Anda

try {
    // DSN standar dengan port
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

    // Opsi koneksi: Memaksa SSL aktif TAPI menonaktifkan verifikasi sertifikat agar tidak diblokir XAMPP
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false, // <-- Kunci utamanya di sini
    ];

    $pdo = new PDO($dsn, $username, $password, $options);

    // echo "Koneksi database berhasil!"; // Uncomment untuk testing
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>