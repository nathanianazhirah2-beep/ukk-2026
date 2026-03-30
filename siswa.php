<?php
session_start();
include 'koneksi.php';

/* ============================
   CEK LOGIN ADMIN
============================ */
if (!isset($_SESSION['status_login']) || $_SESSION['role'] != "admin") {
    header("Location: login-admin.php");
    exit;
}

/* ============================
   TAMBAH SISWA
============================ */
if (isset($_POST['tambah'])) {

    $nis = $_POST['nis'];
    $kelas = $_POST['kelas'];

    $cek = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('NIS sudah ada!');</script>";
    } else {

        mysqli_query($conn, "INSERT INTO siswa (nis,kelas) VALUES ('$nis','$kelas')");

        echo "<script>
        alert('Data siswa berhasil ditambahkan');
        window.location='siswa.php';
        </script>";
    }
}

/* ============================
   HAPUS SISWA
============================ */
if (isset($_GET['hapus'])) {
    $nis = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM siswa WHERE nis='$nis'");

    echo "<script>
    alert('Data siswa berhasil dihapus');
    window.location='siswa.php';
    </script>";
}

/* ============================
   UPDATE SISWA
============================ */
if (isset($_POST['update'])) {

    $nis_lama = $_POST['nis_lama'];
    $nis = $_POST['nis'];
    $kelas = $_POST['kelas'];

    $update = mysqli_query($conn, "UPDATE siswa 
        SET nis='$nis', kelas='$kelas'
        WHERE nis='$nis_lama'
    ");

    if ($update) {
        echo "<script>
        alert('Data siswa berhasil diupdate');
        window.location='siswa.php';
        </script>";
    }
}

/* ============================
   AMBIL DATA SISWA
============================ */
$data = mysqli_query($conn, "SELECT * FROM siswa ORDER BY nis ASC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Siswa | Portal Aspirasi</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
        }

        /* ============================
NAVBAR MODERN
============================ */

        .navbar {
            background: linear-gradient(135deg, #0d47a1, #1565c0, #1e88e5);
            padding: 16px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .menu {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .menu a {
            color: white;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 8px;
            transition: 0.3s;
            font-weight: 500;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .menu a.active {
            background: white;
            color: #1565c0;
            font-weight: bold;
        }

        .logout {
            background: #ff3b3b;
            padding: 10px 18px;
            border-radius: 25px;
            font-weight: bold;
        }

        .logout:hover {
            background: #c62828;
        }

        /* KONTEN */

        .container {
            padding: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* FORM TAMBAH */

        .form-tambah {
            background: linear-gradient(135deg, #2196f3, #42a5f5);
            color: white;
        }

        .form-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .form-row input {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-row input:focus {
            outline: none;
            box-shadow: 0 0 0 2px white;
        }

        .btn-tambah {
            margin-top: 10px;
            padding: 12px 22px;
            background: #0d47a1;
            border: none;
            color: white;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-tambah:hover {
            background: #08306b;
        }

        /* TABLE */

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th,
        table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background: #2196f3;
            color: white;
        }

        .edit {
            background: orange;
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
        }

        .hapus {
            background: red;
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
        }

        button {
            padding: 10px 18px;
            background: #2196f3;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background: #1976d2;
        }

        input {
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->

    <div class="navbar">

        <div class="logo">
            Dashboard Admin
        </div>

        <div class="menu">

            <a href="dashboard.php">🏠 Dashboard</a>
            <a href="kategori.php">📂 Kategori</a>
            <a href="siswa.php" class="active">👨‍🎓 Siswa</a>
            <a href="aspirasi.php">💬 Aspirasi</a>
            <a href="logout.php" class="logout">🚪 Logout</a>

        </div>

    </div>


    <div class="container">

        <!-- FORM TAMBAH SISWA -->

        <div class="card form-tambah">

            <h3>Tambah Data Siswa</h3>

            <form method="POST">

                <div class="form-row">

                    <input type="text" name="nis" placeholder="Masukkan NIS" required>

                    <input type="text" name="kelas" placeholder="Masukkan Kelas (contoh: XI RPL 1)" required>

                </div>

                <button type="submit" name="tambah" class="btn-tambah">
                    Tambah Siswa
                </button>

            </form>

        </div>


        <!-- DATA SISWA -->

        <div class="card">

            <h3>Data Siswa</h3>

            <table>

                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Kelas</th>
                    <th>Aksi</th>
                </tr>

                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($data)) {
                ?>

                    <tr>

                        <td><?= $no++; ?></td>
                        <td><?= $row['nis']; ?></td>
                        <td><?= $row['kelas']; ?></td>

                        <td>

                            <a href="siswa.php?edit=<?= $row['nis']; ?>" class="edit">Edit</a>

                            <a href="siswa.php?hapus=<?= $row['nis']; ?>"
                                class="hapus"
                                onclick="return confirm('Yakin ingin menghapus data siswa ini?')">
                                Hapus
                            </a>

                        </td>

                    </tr>

                <?php } ?>

            </table>

        </div>


        <!-- FORM EDIT -->

        <?php

        if (isset($_GET['edit'])) {

            $nis = $_GET['edit'];

            $edit = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$nis'");

            $dataEdit = mysqli_fetch_assoc($edit);

        ?>

            <div class="card">

                <h3>Edit Data Siswa</h3>

                <form method="POST">

                    <input type="hidden" name="nis_lama" value="<?= $dataEdit['nis']; ?>">

                    <input type="text" name="nis" value="<?= $dataEdit['nis']; ?>" required>

                    <input type="text" name="kelas" value="<?= $dataEdit['kelas']; ?>" required>

                    <br><br>

                    <button type="submit" name="update">Update</button>

                </form>

            </div>

        <?php } ?>

    </div>

</body>

</html>