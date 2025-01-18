<?php
include_once("../../../config/conn.php");
session_start();

// Validasi akses pengguna
if (!isset($_SESSION['login']) || $_SESSION['akses'] != 'pasien') {
    echo "<meta http-equiv='refresh' content='0; url=../'>";
    die();
}
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
<div class="wrapper">
<body class="hold-transition sidebar-mini layout-fixed">
<?php include "../../../layouts/header.php"?>
    <div class="container mt-5">
        <h3>Konsultasi Medis</h3>
        <form action="proses_konsultasi.php" method="POST">
            <div class="mb-3">
                <label for="id_dokter" class="form-label">Pilih Dokter</label>
                <select id="id_dokter" class="form-control" name="id_dokter" required>
                    <option value="">Pilih Dokter</option>
                    <?php
                    $data = $pdo->prepare("SELECT * FROM dokter");
                    $data->execute();
                    while ($row = $data->fetch()) {
                        echo "<option value='{$row['id']}'>{$row['nama']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Subjek Konsultasi</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="mb-3">
                <label for="pertanyaan" class="form-label">Pertanyaan</label>
                <textarea class="form-control" id="pertanyaan" name="pertanyaan" rows="4" required></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Kirim Konsultasi</button>
        </form>
    </div>
</body>
</html>