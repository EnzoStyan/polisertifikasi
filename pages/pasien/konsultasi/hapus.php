<?php
include_once("../../../config/conn.php");
session_start();

// Validasi akses pengguna
if (!isset($_SESSION['login']) || $_SESSION['akses'] != 'pasien') {
    echo "<meta http-equiv='refresh' content='0; url=../'>";
    die();
}

$id_pasien = $_SESSION['id'];
$id_konsultasi = $_GET['id'];  // Dapatkan ID konsultasi dari URL

// Periksa apakah konsultasi yang akan dihapus milik pasien ini
$query = $pdo->prepare("SELECT * FROM konsultasi WHERE id = :id_konsultasi AND id_pasien = :id_pasien");
$query->bindParam(':id_konsultasi', $id_konsultasi);
$query->bindParam(':id_pasien', $id_pasien);
$query->execute();

$konsultasi = $query->fetch();

if ($konsultasi) {
    // Hapus konsultasi jika ada
    $deleteQuery = $pdo->prepare("DELETE FROM konsultasi WHERE id = :id_konsultasi");
    $deleteQuery->bindParam(':id_konsultasi', $id_konsultasi);

    if ($deleteQuery->execute()) {
        header('Location: index.php');
        exit;
    } else {
        echo "Terjadi kesalahan saat menghapus konsultasi.";
    }
} else {
    // Jika konsultasi tidak ditemukan atau bukan milik pasien, redirect
    header('Location: index.php');
    exit;
}
?>
