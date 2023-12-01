<?php
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $document_name = $_POST["document_name"];

    // Lakukan koneksi ke database
    $host = "localhost"; // Ganti dengan host database Anda
    $db_username = "root"; // Ganti dengan username database Anda
    $db_password = ""; // Ganti dengan password database Anda (kosong jika tidak ada)
    $database = "progweb"; // Ganti dengan nama database "progweb"

    $conn = mysqli_connect($host, $db_username, $db_password, $database);

    if (!$conn) {
        die("Koneksi ke database gagal: " . mysqli_connect_error());
    }

    // Ambil user_id dari session untuk mengetahui pemilik dokumen
    $user_id = $_SESSION["user_id"];

    // Proses unggah dokumen ke server dan simpan informasi ke database
    if ($_FILES["document_file"]["error"] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; // Folder untuk menyimpan dokumen PDF (pastikan folder sudah ada dan memiliki izin tertulis)
        $file_name = basename($_FILES["document_file"]["name"]);

        // Cek apakah file yang diunggah adalah PDF
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if ($file_type !== "pdf") {
            echo "File yang diunggah harus berupa dokumen PDF.";
            exit;
        }

        // Generate nama unik untuk file yang diunggah agar tidak ada duplikasi
        $unique_file_name = uniqid() . "_" . $file_name;
        $target_file = $target_dir . $unique_file_name;

        // Pindahkan file ke folder uploads
        if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $target_file)) {
            // Simpan informasi dokumen ke database
            $upload_date = date("Y-m-d"); // Menggunakan tanggal saat ini sebagai tanggal unggah
            $sql = "INSERT INTO documents (user_id, document_name, document_file, unique_file_name, upload_date) VALUES ('$user_id', '$document_name', '$unique_file_name', '$unique_file_name','$upload_date')";

            if (mysqli_query($conn, $sql)) {
                // Redirect ke halaman dashboard setelah unggah dokumen berhasil
                header("Location: dashboard.php?upload_success=1");
            } else {
                // Terjadi kesalahan saat menambahkan data ke database, beri pesan error
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Terjadi kesalahan saat mengunggah dokumen.";
        }
    } else {
        echo "Terjadi kesalahan saat mengunggah dokumen.";
    }

    // Tutup koneksi ke database
    mysqli_close($conn);
}
?>
