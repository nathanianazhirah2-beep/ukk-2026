
<?php
include 'koneksi.php';

/* ============================
   AMBIL DATA SISWA
============================ */
$data_siswa = mysqli_query($conn, "SELECT * FROM siswa ORDER BY nis ASC");

/* ============================
   AMBIL DATA KATEGORI
============================ */
$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY ket_kategori ASC");

/* ============================
   TAMBAH ASPIRASI
============================ */
if (isset($_POST['submit'])) {

  $nis = $_POST['nis'];
  $id_kategori = $_POST['id_kategori'];
  $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
  $ket = mysqli_real_escape_string($conn, $_POST['ket']);

  $query = mysqli_query($conn, "
INSERT INTO input_aspirasi
(nis,id_kategori,lokasi,ket)
VALUES
('$nis','$id_kategori','$lokasi','$ket')
");

  if ($query) {

    echo "<script>
alert('Aspirasi berhasil dikirim!');
window.location='index.php';
</script>";
  } else {

    echo "Error : " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Input Aspirasi</title>

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Segoe UI;
      background: #f4f6fb;
    }

    /* NAVBAR */
    .navbar {
      background: linear-gradient(to right, #1e3c72, #2a5298);
      padding: 18px 40px;
    }

    .nav-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      color: white;
      font-size: 22px;
      font-weight: bold;
    }

    .menu a {
      text-decoration: none;
      color: white;
      margin-left: 25px;
      font-weight: 500;
    }

    .menu a:hover {
      opacity: 0.8;
    }

    .login {
      background: #27ae60;
      padding: 8px 15px;
      border-radius: 20px;
    }

    /* CONTAINER */
    .container {
      width: 90%;
      max-width: 800px;
      margin: 40px auto;
    }

    /* CARD */
    .card {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    h2 {
      margin-bottom: 20px;
      color: #1e3c72;
    }

    /* FORM */
    form input,
    form select,
    form textarea {

      width: 100%;
      padding: 12px;
      margin-bottom: 15px;
      border-radius: 8px;
      border: 1px solid #ddd;
      font-size: 14px;

    }

    form input:focus,
    form select:focus,
    form textarea:focus {

      outline: none;
      border: 1px solid #2a5298;

    }

    button {

      padding: 12px 20px;
      background: #2a5298;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;

    }

    button:hover {

      background: #1e3c72;

    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <div class="navbar">

    <div class="nav-content">

      <div class="logo">Portal Aspirasi</div>

      <div class="menu">

        <a href="index.php">Dashboard</a>
        <a href="input-aspirasi.php" class="active">Input Aspirasi</a>
        <a href="login.php" class="login">Login</a>

      </div>

    </div>

  </div>

  <!-- CONTAINER -->
  <div class="container">

    <div class="card">

      <h2>Input Aspirasi</h2>

      <form method="POST">

        <label>NIS</label>

        <select name="nis" required>

          <option value="">-- Pilih NIS --</option>

          <?php
          while ($row = mysqli_fetch_assoc($data_siswa)) {
          ?>

            <option value="<?= $row['nis']; ?>">

              <?= $row['nis']; ?> | <?= $row['kelas']; ?>

            </option>

          <?php } ?>

        </select>

        <label>Kategori</label>

        <select name="id_kategori" required>

          <option value="">-- Pilih Kategori --</option>

          <?php
          while ($row = mysqli_fetch_assoc($kategori)) {
          ?>

            <option value="<?= $row['id_kategori']; ?>">

              <?= $row['ket_kategori']; ?>

            </option>

          <?php } ?>

        </select>

        <label>Lokasi</label>
        <input type="text" name="lokasi" placeholder="Masukkan lokasi" required>

        <label>Keterangan</label>
        <textarea name="ket" rows="4" placeholder="Masukkan keterangan aspirasi" required></textarea>

        <button type="submit" name="submit">Kirim Aspirasi</button>

      </form>

    </div>

  </div>

</body>

</html>