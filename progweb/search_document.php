<?php
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["keyword"])) {
    $keyword = $_GET["keyword"];

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

    // Lakukan pencarian dokumen berdasarkan keyword
    $sql = "SELECT * FROM documents WHERE user_id = $user_id AND document_name LIKE '%$keyword%'";
    $result = mysqli_query($conn, $sql);

    // Inisialisasi variabel untuk menyimpan data dokumen
    $search_results = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $search_results[] = $row;
        }
    }

    // Tutup koneksi ke database
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hasil Pencarian Dokumen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Hasil Pencarian Dokumen: "<?php echo $keyword; ?>"</h2>
        <?php if (count($search_results) > 0) : ?>
            <table>
                <tr>
                    <th>Nama Dokumen</th>
                    <th>Tanggal Upload</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($search_results as $document) : ?>
                    <tr>
                        <td><?php echo $document['document_name']; ?></td>
                        <td><?php echo $document['upload_date']; ?></td>
                        <td>
                            <a href="edit_document.php?id=<?php echo $document['id']; ?>">Edit</a> |
                            <a href="delete_document.php?id=<?php echo $document['id']; ?>">Hapus</a> |
                            <a href="download_document.php?id=<?php echo $document['id']; ?>">Unduh</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p>Tidak ditemukan dokumen dengan kata kunci "<?php echo $keyword; ?>"</p>
        <?php endif; ?>
        <p><a href="dashboard.php">Kembali ke Dashboard</a></p>
    </div>
</body>
</html>
