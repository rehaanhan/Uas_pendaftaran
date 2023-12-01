<?php
// Lakukan koneksi ke database jika diperlukan
$host = "localhost";
$db_username = "root";
$db_password = "";
$database = "progweb";

$conn = mysqli_connect($host, $db_username, $db_password, $database);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// Periksa apakah ada parameter ID dokumen pada URL
if (isset($_GET['id'])) {
    // Dapatkan ID dokumen dari URL
    $document_id = $_GET['id'];

    // Query untuk mendapatkan data dokumen dari database berdasarkan ID
    $sql = "SELECT * FROM documents WHERE id = $document_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        // Ambil data dokumen dari hasil query
        $document_data = mysqli_fetch_assoc($result);

        // Tentukan lokasi file PDF yang akan diunduh
        $file_path = 'uploads/' . $document_data['document_file'];

        // Periksa apakah file ada sebelum melakukan unduhan
        if (file_exists($file_path)) {
            // Mulai unduh file PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $document_data['document_name'] . '.pdf"');
            readfile($file_path);
            exit;
        } else {
            // File tidak ditemukan
            echo "File tidak ditemukan.";
        }
    } else {
        // Dokumen tidak ditemukan
        echo "Dokumen tidak ditemukan.";
    }
} else {
    // Parameter ID tidak ada dalam URL
    echo "ID dokumen tidak valid.";
}

// Tutup koneksi ke database jika diperlukan
// ...

?>
