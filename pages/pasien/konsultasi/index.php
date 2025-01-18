<?php
include_once("../../../config/conn.php");
session_start();

// Validasi akses pengguna
if (!isset($_SESSION['login']) || $_SESSION['akses'] != 'pasien') {
    echo "<meta http-equiv='refresh' content='0; url=../'>";
    die();
}

$id_pasien = $_SESSION['id'];

$query = $pdo->prepare("SELECT k.*, d.nama AS nama_dokter 
                        FROM konsultasi k 
                        JOIN dokter d ON k.id_dokter = d.id 
                        WHERE k.id_pasien = :id_pasien");
$query->bindParam(':id_pasien', $id_pasien);
$query->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= getenv('APP_NAME') ?> | Dashboard</title>

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
<body class="hold-transition sidebar-mini layout-fixed">
<?php include "../../../layouts/header.php"?>

    <div class="container mt-5">
        <h3>Daftar Konsultasi</h3>
        <div>
        <a href="konsultasi.php" class="btn btn-primary btn-sm float-right"><i class="fa fa-plus"></i> Tambah Konsultasi</a>
      </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Dokter</th>
                    <th>Subjek</th>
                    <th>Pertanyaan</th>
                    <th>Jawaban</th>
                    <th>Aksi</th>                 
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $query->fetch()): ?>
                <tr>
                    <td><?= $row['tgl_konsultasi'] ?></td>
                    <td><?= $row['nama_dokter'] ?></td>
                    <td><?= $row['subject'] ?></td>
                    <td><?= $row['pertanyaan'] ?></td>
                    <td><?= $row['jawaban'] ?? 'Belum dijawab' ?></td>
                    <td>
                      <a href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                      <a href="hapus.php?id=<?= $row['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus konsultasi ini?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>