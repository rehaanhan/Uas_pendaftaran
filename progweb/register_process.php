<?php
// Pastikan file ini dipanggil dari form pendaftaran (register.php) dengan metode POST.
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Validasi data (misalnya, periksa apakah password dan konfirmasi password sama)
    if ($password !== $confirm_password) {
        // Password tidak cocok, beri pesan error dan kembali ke halaman pendaftaran
        header("Location: register.php?error=1");
        exit;
    }

    // Lakukan koneksi ke database
    $host = "localhost"; // Ganti dengan host database Anda
    $db_username = "root"; // Ganti dengan username database Anda
    $db_password = ""; // Ganti dengan password database Anda (kosong jika tidak ada)
    $database = "progweb"; // Ganti dengan nama database "progweb"

    $conn = mysqli_connect($host, $db_username, $db_password, $database);

    if (!$conn) {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    }

    // Enkripsi password sebelum menyimpan ke database (misalnya menggunakan password_hash())
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Lakukan operasi INSERT untuk menyimpan data pengguna baru ke dalam tabel "users"
    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        // Registrasi berhasil, arahkan pengguna ke halaman login
        header("Location: index.php?success=1");
    } else {
        // Terjadi kesalahan saat menambahkan data ke database, beri pesan error
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Tutup koneksi ke database
    mysqli_close($conn);
}
?>
