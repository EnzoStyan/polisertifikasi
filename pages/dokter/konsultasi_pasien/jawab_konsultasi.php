<?php
include_once("../../../config/conn.php");
session_start();

// Validasi akses pengguna
if (!isset($_SESSION['login']) || $_SESSION['akses'] != 'dokter') {
    echo "<meta http-equiv='refresh' content='0; url=../'>";
    die();
}

if (isset($_POST['submit'])) {
    $id_konsultasi = $_POST['id_konsultasi'];
    $jawaban = $_POST['jawaban'];

    // Validasi input
    if (empty($jawaban)) {
        echo "<script>alert('Jawaban tidak boleh kosong!');</script>";
        echo "<meta http-equiv='refresh' content='0'>";
        exit;
    }

    // Update jawaban ke database
    $query = $pdo->prepare("UPDATE konsultasi SET jawaban = :jawaban WHERE id = :id_konsultasi");
    $query->bindParam(':jawaban', $jawaban);
    $query->bindParam(':id_konsultasi', $id_konsultasi);

    if ($query->execute()) {
        echo "<script>alert('Jawaban berhasil disimpan!');</script>";
    } else {
        echo "<script>alert('Gagal menyimpan jawaban!');</script>";
    }

    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
}
?>