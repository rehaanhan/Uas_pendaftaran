<?php
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $document_id = $_POST["document_id"];
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

    // Periksa apakah dokumen ada dan milik pengguna yang sedang login
    $sql = "SELECT * FROM documents WHERE id = $document_id AND user_id = $user_id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $document = mysqli_fetch_assoc($result);
        $old_file = 'uploads/' . $document['unique_file_name'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
        // Jika ada file yang diunggah, proses unggah dokumen baru
        if ($_FILES["document_file"]["error"] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/"; // Folder untuk menyimpan dokumen PDF (pastikan folder sudah ada dan memiliki izin tertulis)
            $file_name = $_FILES["document_file"]["name"];
            $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $unique_file_name = uniqid() . "_" . str_replace(" ", "_", $file_name);
            $target_file = $target_dir . $unique_file_name;

            // Cek apakah file yang diunggah adalah PDF
            if ($file_extension !== "pdf") {
                echo "File yang diunggah harus berupa dokumen PDF.";
                exit;
            }

            // Pindahkan file ke folder uploads
            if (move_uploaded_file($_FILES["document_file"]["tmp_name"], $target_file)) {
                // Hapus file lama dari server jika ada
                

                // Simpan informasi dokumen ke database (termasuk kolom document_file dan unique_file_name yang diperbarui)
                $sql_update_file = "UPDATE documents SET document_name = '$document_name', document_file = '$unique_file_name', unique_file_name = '$unique_file_name' WHERE id = $document_id";

                if (mysqli_query($conn, $sql_update_file)) {
                    // Redirect ke halaman dashboard setelah pembaruan berhasil
                    header("Location: dashboard.php?update_success=1");
                } else {
                    // Terjadi kesalahan saat memperbarui data ke database, beri pesan error
                    echo "Error: " . $sql_update_file . "<br>" . mysqli_error($conn);
                }
            } else {
                echo "Terjadi kesalahan saat mengunggah dokumen.";
            }
        } else {
            // Jika tidak ada file yang diunggah, hanya perbarui nama dokumen
            $sql_update_name = "UPDATE documents SET document_name = '$document_name' WHERE id = $document_id";
            if (mysqli_query($conn, $sql_update_name)) {
                // Redirect ke halaman dashboard setelah pembaruan berhasil (tanpa mengunggah file baru)
                header("Location: dashboard.php?update_success=1");
            } else {
                // Terjadi kesalahan saat memperbarui data ke database, beri pesan error
                echo "Error: " . $sql_update_name . "<br>" . mysqli_error($conn);
            }
        }
    } else {
        echo "<p>Dokumen tidak ditemukan atau Anda tidak memiliki izin untuk mengedit dokumen ini.</p>";
    }

    // Tutup koneksi ke database
    mysqli_close($conn);
}
?>
