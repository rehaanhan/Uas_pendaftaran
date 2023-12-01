<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Lakukan koneksi ke database
    $host = "localhost"; // Ganti dengan host database Anda
    $db_username = "root"; // Ganti dengan username database Anda
    $db_password = ""; // Ganti dengan password database Anda (kosong jika tidak ada)
    $database = "progweb"; // Ganti dengan nama database "progweb"

    $conn = mysqli_connect($host, $db_username, $db_password, $database);

    if (!$conn) {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    }

    // Ambil data pengguna dari database berdasarkan username
    $sql = "SELECT id, username, password FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password
        if (password_verify($password, $row["password"])) {
            // Password cocok, simpan informasi login ke session
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
    
            // Jika "Ingat Saya" diceklis, buat cookie untuk menyimpan informasi login selama 30 hari
            if (isset($_POST["remember"])) {
                $cookie_value = $row["id"] . "|" . $row["username"];
                setcookie("remember_user", $cookie_value, time() + (30 * 24 * 60 * 60), "/"); // Cookie akan berlaku selama 30 hari
            }
    
            // Redirect ke halaman dashboard setelah login berhasil
            header("Location: dashboard.php");
        } else {
            // Password tidak cocok, arahkan kembali ke halaman login dengan pesan error
            header("Location: index.php?error=1");
        }

    // Tutup koneksi ke database
    mysqli_close($conn);
}
}
?>
