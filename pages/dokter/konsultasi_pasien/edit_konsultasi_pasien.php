<?php
include_once("../../../config/conn.php");
session_start();

// Validasi akses pengguna
if (!isset($_SESSION['login']) || $_SESSION['akses'] != 'dokter') {
    echo "<meta http-equiv='refresh' content='0; url=../'>";
    die();
}

$id_dokter = $_SESSION['id'];
$id_konsultasi = $_GET['id'];  // Dapatkan ID konsultasi dari URL

// Ambil data konsultasi yang ingin diedit jawabannya
$query = $pdo->prepare("SELECT k.*, p.nama AS nama_pasien 
                        FROM konsultasi k 
                        JOIN pasien p ON k.id_pasien = p.id 
                        WHERE k.id = :id_konsultasi AND k.id_dokter = :id_dokter");
$query->bindParam(':id_konsultasi', $id_konsultasi);
$query->bindParam(':id_dokter', $id_dokter);
$query->execute();

$konsultasi = $query->fetch();

// Jika konsultasi tidak ditemukan, redirect ke halaman sebelumnya
if (!$konsultasi) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses update jawaban konsultasi
    $jawaban = $_POST['jawaban'];

    $updateQuery = $pdo->prepare("UPDATE konsultasi SET jawaban = :jawaban WHERE id = :id_konsultasi");
    $updateQuery->bindParam(':jawaban', $jawaban);
    $updateQuery->bindParam(':id_konsultasi', $id_konsultasi);

    if ($updateQuery->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Terjadi kesalahan saat mengupdate jawaban!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Jawaban Konsultasi | <?= getenv('APP_NAME') ?></title>
  <!-- Add other required stylesheets here -->
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="../../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../../../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../../plugins/summernote/summernote-bs4.min.css">
</head>
<body>
<?php include "../../../layouts/header.php"?>

<div class="container mt-5">
    <h3>Edit Jawaban Konsultasi</h3>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="jawaban">Jawaban</label>
            <textarea class="form-control" id="jawaban" name="jawaban" rows="5" required><?= htmlspecialchars($konsultasi['jawaban']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
</body>
</html>
