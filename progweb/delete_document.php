<?php
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Periksa apakah ada parameter id dari URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $document_id = $_GET['id'];

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

    // Periksa apakah dokumen ada dan milik pengguna yang sedang login
    $sql = "SELECT * FROM documents WHERE id = $document_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
// Hapus dokumen dari database dan server
$row = mysqli_fetch_assoc($result);
$file_path = 'uploads/' . $row['unique_file_name'];

// Tampilkan path file sebelum menghapus
echo "Path file yang akan dihapus: " . $file_path . "<br>";

        // Hapus file dari server
        if (unlink($file_path)) {
            // Hapus data dokumen dari database
            $sql_delete = "DELETE FROM documents WHERE id = $document_id";
            if (mysqli_query($conn, $sql_delete)) {
                // Redirect ke halaman dashboard setelah penghapusan berhasil
                header("Location: dashboard.php?delete_success=1");
            } else {
                // Terjadi kesalahan saat menghapus data dari database, beri pesan error
                echo "Error: " . $sql_delete . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "<p>Terjadi kesalahan saat menghapus dokumen.</p>";
        }
    } else {
        echo "<p>Dokumen tidak ditemukan atau Anda tidak memiliki izin untuk menghapus dokumen ini.</p>";
    }

    // Tutup koneksi ke database
    mysqli_close($conn);
}
?>
