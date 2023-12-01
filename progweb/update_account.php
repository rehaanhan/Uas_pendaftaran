<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Lakukan validasi data pengaturan akun di sini jika diperlukan

    // Lakukan koneksi ke database
    $host = "localhost"; // Ganti dengan host database Anda
    $db_username = "root"; // Ganti dengan username database Anda
    $db_password = ""; // Ganti dengan password database Anda (kosong jika tidak ada)
    $database = "progweb"; // Ganti dengan nama database "progweb"

    $conn = mysqli_connect($host, $db_username, $db_password, $database);

    if (!$conn) {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    }

    // Ambil user_id dari session untuk mengidentifikasi akun yang akan diubah
    $user_id = $_SESSION["user_id"];

    // Hash password baru sebelum menyimpannya ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update data pengaturan akun ke database
    $sql = "UPDATE users SET username = '$username', password = '$hashed_password' WHERE id = $user_id";

    if (mysqli_query($conn, $sql)) {
        // Update pengaturan akun berhasil, redirect kembali ke halaman dashboard
        $_SESSION["username"] = $username; // Update session dengan username baru
        header("Location: dashboard.php?update_success=1");
    } else {
        // Terjadi kesalahan saat memperbarui data di database, beri pesan error
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Tutup koneksi ke database
    mysqli_close($conn);
}
?>
