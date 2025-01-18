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

// Ambil data konsultasi yang ingin diedit
$query = $pdo->prepare("SELECT * FROM konsultasi WHERE id = :id_konsultasi AND id_pasien = :id_pasien");
$query->bindParam(':id_konsultasi', $id_konsultasi);
$query->bindParam(':id_pasien', $id_pasien);
$query->execute();

$konsultasi = $query->fetch();

// Jika konsultasi tidak ditemukan, redirect ke halaman sebelumnya
if (!$konsultasi) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Proses update data konsultasi
    $subject = $_POST['subject'];
    $pertanyaan = $_POST['pertanyaan'];

    $updateQuery = $pdo->prepare("UPDATE konsultasi SET subject = :subject, pertanyaan = :pertanyaan WHERE id = :id_konsultasi");
    $updateQuery->bindParam(':subject', $subject);
    $updateQuery->bindParam(':pertanyaan', $pertanyaan);
    $updateQuery->bindParam(':id_konsultasi', $id_konsultasi);

    if ($updateQuery->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $error = "Terjadi kesalahan saat mengupdate data!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Konsultasi | <?= getenv('APP_NAME') ?></title>
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
    <h3>Edit Konsultasi</h3>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="subject">Subjek</label>
            <input type="text" class="form-control" id="subject" name="subject" value="<?= htmlspecialchars($konsultasi['subject']) ?>" required>
        </div>
        <div class="form-group">
            <label for="pertanyaan">Pertanyaan</label>
            <textarea class="form-control" id="pertanyaan" name="pertanyaan" rows="4" required><?= htmlspecialchars($konsultasi['pertanyaan']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
</body>
</html>
