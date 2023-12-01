<?php
session_start();

// Hapus session
session_unset();
session_destroy();

// Hapus cookie "remember_user" jika ada
if (isset($_COOKIE["remember_user"])) {
    setcookie("remember_user", "", time() - 3600, "/");
}

// Hapus cookie "PHPSESSID"
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), "", time() - 3600, "/");
}

// Menghapus cookie PHPSESSID yang sudah ada dari browser
if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", "", time() - 3600, "/");
}

// Redirect kembali ke halaman login setelah logout
header("Location: index.php");
?>
