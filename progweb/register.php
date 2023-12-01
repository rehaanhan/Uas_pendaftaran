<?php
// Cek apakah ada pesan error atau success dari proses registrasi
if (isset($_GET['error'])) {
    echo "<p style='color: red;'>Password dan konfirmasi password tidak cocok.</p>";
}

if (isset($_GET['success'])) {
    echo "<p style='color: green;'>Registrasi berhasil! Silakan masuk dengan akun Anda.</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Halaman Pendaftaran</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h2>Daftar Akun Baru</h2>
        </div>
    </header>
    <main>
        <div class="container">
            <form action="register_process.php" method="post">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit">Daftar</button>
            </form>
            <!-- Tambahkan tombol untuk kembali ke halaman login -->
            <p>Sudah punya akun? <a href="index.php">Masuk di sini</a></p>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>Hak Cipta &copy; 2023 UAS Progweb. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
