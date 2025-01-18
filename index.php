<?php
include_once("../../../config/conn.php");
session_start();

// Validasi akses pengguna
if (!isset($_SESSION['login']) || $_SESSION['akses'] != 'dokter') {
    echo "<meta http-equiv='refresh' content='0; url=../'>";
    die();
}

$id_dokter = $_SESSION['id'];

$query = $pdo->prepare("SELECT k.*, p.nama AS nama_pasien 
                        FROM konsultasi k 
                        JOIN pasien p ON k.id_pasien = p.id 
                        WHERE k.id_dokter = :id_dokter");
$query->bindParam(':id_dokter', $id_dokter);
$query->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= getenv('APP_NAME') ?> Form Konsultasi | Pasien </title>

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
        <h3>Daftar Konsultasi untuk Dokter</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Subjek</th>
                    <th>Pertanyaan</th>
                    <th>Jawaban</th>
                    <th>Aksi</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $query->fetch()): ?>
                <tr>
                    <td><?= $row['tgl_konsultasi'] ?></td>
                    <td><?= $row['nama_pasien'] ?></td>
                    <td><?= $row['subject'] ?></td>
                    <td><?= $row['pertanyaan'] ?></td>
                    <td><?= $row['jawaban'] ?? 'Belum dijawab' ?></td>
                    <td>
                        <?php if (isset($_GET['tanggapi']) && $_GET['tanggapi'] == $row['id']): ?>
                            <form action="proses_jawab_konsultasi.php" method="POST">
                                <input type="hidden" name="id_konsultasi" value="<?= $row['id'] ?>">
                                <textarea name="jawaban" rows="2" class="form-control mb-2" required></textarea>
                                <button type="submit" name="submit" class="btn btn-success btn-sm">Jawab</button>
                                <a href="index.php" class="btn btn-secondary btn-sm">Batal</a>
                            </form>
                        <?php else: ?>
                            <!-- Tombol tanggapi -->
                            <?php if (empty($row['jawaban'])): ?>
                                <a href="?tanggapi=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Tanggapi</a>
                            <?php else: ?>
                                <a href="edit_konsultasi_pasien.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit Jawaban</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>