<?php
include_once("../../../config/conn.php");
session_start();

// Validasi akses pengguna
if (!isset($_SESSION['login']) || $_SESSION['akses'] != 'pasien') {
    echo "<meta http-equiv='refresh' content='0; url=../'>";
    die();
}

$id_pasien = $_SESSION['id'];

if (isset($_POST['submit'])) {
    $id_dokter = $_POST['id_dokter'];
    $subject = $_POST['subject'];
    $pertanyaan = $_POST['pertanyaan'];

    // Validasi input
    if (empty($id_dokter) || empty($subject) || empty($pertanyaan)) {
        echo "<script>alert('Semua field wajib diisi!');</script>";
        echo "<meta http-equiv='refresh' content='0'>";
        exit;
    }

    // Simpan data ke database
    $query = $pdo->prepare("INSERT INTO konsultasi (subject, pertanyaan, id_pasien, id_dokter, tgl_konsultasi) 
                            VALUES (:subject, :pertanyaan, :id_pasien, :id_dokter, NOW())");
    $query->bindParam(':subject', $subject);
    $query->bindParam(':pertanyaan', $pertanyaan);
    $query->bindParam(':id_pasien', $id_pasien);
    $query->bindParam(':id_dokter', $id_dokter);

    if ($query->execute()) {
        echo "<script>alert('Konsultasi berhasil dikirim!');</script>";
    } else {
        echo "<script>alert('Gagal mengirim konsultasi!');</script>";
    }

    echo "<meta http-equiv='refresh' content='0; url=index.php'>";
}
?>