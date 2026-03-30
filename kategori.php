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
   TAMBAH KATEGORI
============================ */
if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['ket_kategori']);

    mysqli_query($conn, "INSERT INTO kategori (ket_kategori) VALUES ('$nama')");

    echo "<script>
    alert('Kategori berhasil ditambahkan');
    window.location='kategori.php';
    </script>";
}

/* ============================
   UPDATE KATEGORI
============================ */
if (isset($_POST['update'])) {

    $id = $_POST['id'];
    $nama = mysqli_real_escape_string($conn, $_POST['ket_kategori']);

    $update = mysqli_query($conn, "UPDATE kategori 
        SET ket_kategori='$nama'
        WHERE id_kategori='$id'
    ");

    if ($update) {
        echo "<script>
        alert('Kategori berhasil diupdate');
        window.location='kategori.php';
        </script>";
    }
}

/* ============================
   HAPUS KATEGORI
============================ */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM kategori WHERE id_kategori='$id'");

    echo "<script>
    alert('Kategori berhasil dihapus');
    window.location='kategori.php';
    </script>";
}

/* ============================
   AMBIL DATA KATEGORI
============================ */
$data = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Kategori | Portal Aspirasi</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
        }

        /* ============================
NAVBAR
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

        /* ============================
KONTEN
============================ */

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

        /* ============================
EDIT KATEGORI (BARU)
============================ */

        .edit-kategori {
            background: white;
            border-top: 5px solid #42a5f5;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .edit-kategori h3 {
            margin-top: 0;
            color: #1565c0;
        }

        .edit-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .edit-form input {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .edit-form input:focus {
            border: 1px solid #42a5f5;
            outline: none;
            box-shadow: 0 0 6px rgba(66, 165, 245, 0.4);
        }

        .btn-update {
            background: #42a5f5;
            border: none;
            padding: 12px;
            color: white;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-update:hover {
            background: #1e88e5;
        }

        .btn-batal {
            background: #9e9e9e;
            padding: 12px;
            text-align: center;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-batal:hover {
            background: #616161;
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
            <a href="kategori.php" class="active">📂 Kategori</a>
            <a href="siswa.php">👨‍🎓 Siswa</a>
            <a href="aspirasi.php">💬 Aspirasi</a>
            <a href="logout.php" class="logout">🚪 Logout</a>
        </div>

    </div>


    <div class="container">

        <!-- TAMBAH KATEGORI -->

        <div class="card form-tambah">

            <h3>Tambah Kategori</h3>

            <form method="POST">

                <div class="form-row">

                    <input type="text" name="ket_kategori" placeholder="Masukkan Nama Kategori" required>

                </div>

                <button type="submit" name="tambah" class="btn-tambah">
                    Tambah Kategori
                </button>

            </form>

        </div>


        <!-- DATA KATEGORI -->

        <div class="card">

            <h3>Data Kategori</h3>

            <table>

                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>

                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($data)) {
                ?>

                    <tr>

                        <td><?= $no++; ?></td>

                        <td><?= htmlspecialchars($row['ket_kategori']); ?></td>

                        <td>

                            <a href="kategori.php?edit=<?= $row['id_kategori']; ?>" class="edit">Edit</a>

                            <a href="kategori.php?hapus=<?= $row['id_kategori']; ?>"
                                class="hapus"
                                onclick="return confirm('Yakin hapus kategori ini?')">Hapus</a>

                        </td>

                    </tr>

                <?php } ?>

            </table>

        </div>


        <!-- FORM EDIT -->

        <?php
        if (isset($_GET['edit'])) {

            $id = $_GET['edit'];

            $edit = mysqli_query($conn, "SELECT * FROM kategori WHERE id_kategori='$id'");
            $dataEdit = mysqli_fetch_assoc($edit);
        ?>

            <div class="edit-kategori">

                <h3>Edit Kategori</h3>

                <form method="POST" class="edit-form">

                    <input type="hidden" name="id" value="<?= $dataEdit['id_kategori']; ?>">

                    <input type="text" name="ket_kategori"
                        value="<?= htmlspecialchars($dataEdit['ket_kategori']); ?>" required>

                    <button type="submit" name="update" class="btn-update">
                        Update Kategori
                    </button>

                    <a href="kategori.php" class="btn-batal">
                        Batal
                    </a>

                </form>

            </div>

        <?php } ?>

    </div>

</body>

</html>