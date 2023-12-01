<?php
// Cek apakah ada pesan error dari proses login
if (isset($_GET['error'])) {
    echo "<p style='color: red;'>Username atau password salah.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Halaman Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h2>Selamat datang di Website CRUD Dokumen PDF</h2>
        </div>
    </header>
    <main>
        <div class="container">
            <h3>Silakan masuk dengan akun Anda:</h3>
            <form action="login_process.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat Saya</label>
                </div>
                <button type="submit">Login</button>
            </form>
            <!-- Tambahkan tombol untuk registrasi -->
            <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>Hak Cipta &copy; 2023 UAS Progweb. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
