<?php
$host = 'localhost';
$dbname = 'portal_sekolah_db';
$username = 'root'; // Ubah sesuai username database Anda (misal: root)
$password = '';     // Ubah sesuai password database Anda (kosongkan jika default XAMPP)

try {
    // Membuat koneksi menggunakan PDO (PHP Data Objects)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Mengatur mode error exception agar mudah melakukan debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mengatur default fetch mode menjadi associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // echo "Koneksi database berhasil!"; // Uncomment untuk testing koneksi
} catch (PDOException $e) {
    // Menampilkan pesan jika koneksi gagal
    die("Koneksi database gagal: " . $e->getMessage());
}
?>