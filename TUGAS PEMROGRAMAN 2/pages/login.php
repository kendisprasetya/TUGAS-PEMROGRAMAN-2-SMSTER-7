<?php
session_start();
// Memanggil koneksi database PDO dari folder API
require_once __DIR__ . '/../API/koneksi.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        // Melakukan pengecekan pencocokan data ke tabel 'users' di database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->execute([$username, md5($password)]);
        $user = $stmt->fetch();

        if ($user) {
            // Jika data ditemukan di database, set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect sukses masuk ke dashboard CMS
            header("Location: dashboard_cms.php");
            exit();
        } else {
            $error = "Username atau password salah! Silakan coba lagi.";
        }
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan sistem: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin SIMS - SMAN 1 Nusantara</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-900 text-slate-800 antialiased flex items-center justify-center h-screen overflow-hidden">

    <div class="max-w-md w-full mx-4">
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl bg-blue-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg mx-auto mb-3">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <h1 class="text-white font-bold text-xl tracking-wide">SIMS Admin Portal</h1>
            <p class="text-xs text-slate-400 mt-1">Sistem Informasi Manajemen Sekolah Terpadu</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl border border-slate-800 p-8">
            <h2 class="text-lg font-bold text-slate-900 mb-1">Masuk ke Panel Kontrol</h2>
            <p class="text-xs text-slate-500 mb-6">Masukkan username dan password yang terdaftar di database.</p>

            <?php if (!empty($error)): ?>
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs p-3 rounded-lg flex items-center space-x-2">
                    <i class="fa-solid fa-circle-exclamation text-sm"></i>
                    <span><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-xs">
                            <i class="fa-solid fa-user"></i>
                        </span>
                        <!-- Kolom kosong saat dibuka, terhubung ke database saat disubmit -->
                        <input type="text" name="username" required placeholder="Masukkan username" class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <!-- Kolom kosong saat dibuka, terhubung ke database saat disubmit -->
                        <input type="password" name="password" required placeholder="••••••••" class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-3 rounded-lg shadow-md transition flex items-center justify-center space-x-2 mt-4">
                    <span>Masuk ke Dashboard</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
            </form>
        </div>

        <div class="text-center mt-6 text-xs text-slate-500">
            <a href="index.php" class="hover:text-white transition flex items-center justify-center space-x-1.5">
                <i class="fa-solid fa-globe"></i>
                <span>Kembali ke Website Portal Publik</span>
            </a>
        </div>
    </div>

</body>
</html>