<?php
session_start();
include 'koneksi.php';

// Ambil data dari tabel aspirasi saja
$data = mysqli_query($conn,"
SELECT * FROM aspirasi
ORDER BY id_aspirasi DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Aspirasi</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI';
    background:#f4f6fb;
}

/* NAVBAR */
.navbar{
    background:linear-gradient(to right,#1e3c72,#2a5298);
    padding:18px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    color:white;
}

.menu a{
    color:white;
    text-decoration:none;
    margin-left:25px;
    font-weight:500;
}

.logout{
    background:#e74c3c;
    padding:8px 15px;
    border-radius:20px;
}

/* CONTAINER */
.container{
    width:95%;
    margin:40px auto;
}

h3{
    color:#1e3c72;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 15px 35px rgba(0,0,0,0.08);
}

th{
    background:#1e3c72;
    color:white;
    padding:15px;
    text-align:left;
}

td{
    padding:15px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f0f4ff;
}

/* STATUS BADGE */
.badge{
    padding:6px 14px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.menunggu{
    background:#fff3cd;
    color:#856404;
}

.proses{
    background:#d1ecf1;
    color:#0c5460;
}

.selesai{
    background:#d4edda;
    color:#155724;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <h2>Portal Aspirasi Siswa</h2>
    <div class="menu">
        <a href="dashboard-siswa.php">Dashboard</a>
        <a href="input-aspirasi.php">Input Aspirasi</a>
        <a href="aspirasi.php">Aspirasi</a>
        <a href="kategori.php">Kategori</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="container">
    <h3>Data Aspirasi Siswa</h3>

    <table>
        <tr>
            <th>No</th>
            <th>ID Aspirasi</th>
            <th>NIS</th>
            <th>Status</th>
            <th>Feedback</th>
        </tr>

        <?php 
        $no = 1;
        while($row = mysqli_fetch_assoc($data)){ 
            $status = strtolower(trim(str_replace(['("','";',';','"'], '', $row['status'])));
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['id_aspirasi']; ?></td>
            <td><?= $row['id_pelaporan']; ?></td>
            <td>
                <span class="badge <?= $status; ?>">
                    <?= ucfirst($status); ?>
                </span>
            </td>
            <td><?= $row['feedback']; ?></td>
        </tr>
        <?php } ?>
    </table>

</div>

</body>
</html>