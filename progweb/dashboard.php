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

// Periksa apakah ada parameter sort dari URL
$sort = "upload_date"; // Default sortir berdasarkan tanggal upload
if (isset($_GET["sort"])) {
    $sort_option = $_GET["sort"];
    if ($sort_option === "document_name") {
        $sort = "document_name";
    }
}

// Ambil data dokumen dari database, diurutkan berdasarkan pilihan sortir
$sql = "SELECT * FROM documents WHERE user_id = $user_id ORDER BY $sort";
$result = mysqli_query($conn, $sql);
// Inisialisasi variabel untuk menyimpan data dokumen
$documents = [];

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $documents[] = $row;
    }
}

// Cek apakah ada pesan kesuksesan dari proses unggah dokumen
if (isset($_GET['upload_success'])) {
    echo "<p style='color: green;'>Dokumen berhasil diunggah.</p>";
}

// Cek apakah ada pesan kesuksesan dari proses pembaruan dokumen
if (isset($_GET['update_success'])) {
    echo "<p style='color: green;'>Dokumen berhasil diperbarui.</p>";
}

// Cek apakah ada pesan kesuksesan dari proses penghapusan dokumen
if (isset($_GET['delete_success'])) {
    echo "<p style='color: green;'>Dokumen berhasil dihapus.</p>";
}

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION["user_id"]) && empty($_COOKIE["remember_user"])) {
    header("Location: index.php");
    exit;
}

// Tutup koneksi ke database
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <h2>Selamat datang di Dashboard, <?php echo $_SESSION["username"]; ?></h2>
        </div>
    </header>
    <nav>
        <div class="container">
            <!-- Form Pencarian -->
            <form action="search_document.php" method="get">
                <input type="text" name="keyword" placeholder="Cari dokumen...">
                <button type="submit">Cari</button>
            </form>
            <!-- Form Sortir -->
            <form action="dashboard.php" method="get">
                <label for="sort">Sortir berdasarkan:</label>
                <select name="sort" id="sort">
                    <option value="document_name">Nama Dokumen</option>
                    <option value="upload_date">Tanggal Upload</option>
                </select>
                <button type="submit">Sortir</button>
            </form>
            <!-- Tambahkan tombol logout -->
            <form action="logout.php" method="post">
                <button type="submit">Logout</button>
            </form>
        </div>
    </nav>
    <main>
        <div class="container">
            <h3>Daftar Dokumen PDF Anda:</h3>
            <table>
                <tr>
                    <th>Nama Dokumen</th>
                    <th>Tanggal Upload</th>
                    <th>Aksi</th>
                </tr>
                <?php
                // Tampilkan daftar dokumen PDF
                foreach ($documents as $document) {
                    echo "<tr>";
                    echo "<td>{$document['document_name']}</td>";
                    echo "<td>{$document['upload_date']}</td>";
                    echo "<td>";
                    echo "<a href='edit_document.php?id={$document['id']}'>Edit</a> | ";
                    echo "<a href='delete_document.php?id={$document['id']}'>Hapus</a> | ";
                
                    // Tambahkan tombol unduh
                    echo "<a href='download_document.php?id={$document['id']}' download>Unduh</a>";
                    
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <h3>Unggah Dokumen Baru:</h3>
            <form action="upload_document.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="document_name">Nama Dokumen:</label>
                    <input type="text" id="document_name" name="document_name" required>
                </div>
                <div class="form-group">
                    <label for="document_file">Pilih Dokumen (PDF):</label>
                    <input type="file" id="document_file" name="document_file" required accept=".pdf">
                </div>
                <button type="submit">Unggah</button>
            </form>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>Hak Cipta &copy; 2023 UAS Progweb. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>