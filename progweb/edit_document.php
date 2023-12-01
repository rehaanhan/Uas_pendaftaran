<!DOCTYPE html>
<html>
<head>
    <title>Edit Dokumen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        session_start();

        // Periksa apakah pengguna sudah login atau belum
        if (!isset($_SESSION["user_id"])) {
            header("Location: index.php");
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

        // Ambil user_id dari session untuk mengetahui pemilik dokumen
        $user_id = $_SESSION["user_id"];

        // Periksa apakah ada parameter id dari URL
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $document_id = $_GET['id'];

            // Ambil data dokumen dari database berdasarkan document_id dan user_id
            $sql = "SELECT * FROM documents WHERE id = $document_id AND user_id = $user_id";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $document = mysqli_fetch_assoc($result);
            } else {
                echo "<p>Dokumen tidak ditemukan.</p>";
                exit;
            }
        } else {
            echo "<p>Dokumen tidak ditemukan.</p>";
            exit;
        }

        // Tutup koneksi ke database
        mysqli_close($conn);
        ?>

        <h2>Edit Dokumen</h2>
        <form action="update_document.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="document_id" value="<?php echo $document['id']; ?>">
            <div class="form-group">
                <label for="document_name">Nama Dokumen:</label>
                <input type="text" id="document_name" name="document_name" value="<?php echo $document['document_name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="document_file">Ganti Dokumen (PDF):</label>
                <input type="file" id="document_file" name="document_file" accept=".pdf">
            </div>
            <p>Dokumen saat ini: <?php echo $document['document_file']; ?></p>
            <button type="submit">Simpan Perubahan</button>
        </form>
        <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
    </div>
</body>
</html>
