
<?php
session_start();
include 'koneksi.php';

/* TIMEZONE INDONESIA */
date_default_timezone_set('Asia/Jakarta');

/* CEK LOGIN ADMIN */
if (!isset($_SESSION['status_login']) || $_SESSION['role'] != "admin") {
    header("Location: login.php");
    exit;
}

/* AMBIL DATA ASPIRASI */
$data = mysqli_query($conn, "
SELECT 
ia.id_pelaporan,
ia.created_at,
ia.nis,
s.kelas,
k.ket_kategori,
ia.lokasi,
ia.ket,
COALESCE(a.status,'Menunggu') as status,
a.feedback
FROM input_aspirasi ia
JOIN siswa s ON ia.nis = s.nis
JOIN kategori k ON ia.id_kategori = k.id_kategori
LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
ORDER BY ia.id_pelaporan DESC
");

/* HAPUS DATA */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    mysqli_query($conn, "DELETE FROM aspirasi WHERE id_pelaporan='$id'");
    mysqli_query($conn, "DELETE FROM input_aspirasi WHERE id_pelaporan='$id'");

    echo "<script>
    alert('Data berhasil dihapus');
    window.location='aspirasi.php';
    </script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aspirasi | Portal Aspirasi</title>

<style>
* {
box-sizing: border-box;
margin: 0;
padding: 0;
}

body {
font-family: 'Segoe UI', sans-serif;
background: #e9f0fb;
color: #333;
}

.navbar {
background: linear-gradient(135deg, #0d47a1, #1565c0, #1e88e5);
padding: 16px 40px;
display: flex;
justify-content: space-between;
align-items: center;
color: white;
box-shadow: 0 6px 15px rgba(0, 0, 0, 0.25);
position: sticky;
top: 0;
z-index: 1000;
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
transition: 0.3s;
}

.logout:hover {
background: #c62828;
}

.container {
max-width: 1400px;
margin: 30px auto;
padding: 0 20px;
}

.card {
background: white;
border-radius: 14px;
padding: 25px 30px;
box-shadow: 0 8px 20px rgba(33, 150, 243, 0.1);
overflow-x: auto;
}

table {
width: 100%;
border-collapse: separate;
border-spacing: 0 10px;
font-size: 15px;
}

thead tr {
background: linear-gradient(90deg, #2196f3, #1e88e5);
color: white;
}

thead th {
padding: 14px 20px;
text-align: center;
}

tbody tr {
background: #f5faff;
box-shadow: 0 2px 5px rgba(33, 150, 243, 0.15);
transition: 0.2s;
}

tbody tr:hover {
background: #e3f2fd;
transform: translateY(-3px);
box-shadow: 0 6px 15px rgba(33, 150, 243, 0.25);
}

tbody td {
padding: 15px 20px;
text-align: center;
}

.aksi {
display: flex;
justify-content: center;
gap: 8px;
}

.btn {
padding: 8px 14px;
border-radius: 8px;
text-decoration: none;
font-size: 13px;
font-weight: 600;
transition: 0.25s;
display: inline-block;
}

.btn-edit {
background: linear-gradient(135deg, #42a5f5, #1e88e5);
color: white;
}

.btn-edit:hover {
background: #1565c0;
transform: translateY(-2px);
}

.btn-hapus {
background: linear-gradient(135deg, #ef5350, #e53935);
color: white;
}

.btn-hapus:hover {
background: #c62828;
transform: translateY(-2px);
}
</style>
</head>

<body>

<div class="navbar">

<div class="logo">
Dashboard Admin
</div>

<div class="menu">
<a href="dashboard.php">🏠 Dashboard</a>
<a href="kategori.php">📂 Kategori</a>
<a href="siswa.php">👨‍🎓 Siswa</a>
<a href="aspirasi.php" class="active">💬 Aspirasi</a>
<a href="logout.php" class="logout">🚪 Logout</a>
</div>

</div>

<div class="container">

<div class="card">

<table>

<thead>
<tr>
<th>No</th>
<th>ID Laporan</th>
<th>Tanggal</th>
<th>NIS</th>
<th>Kelas</th>
<th>Kategori</th>
<th>Lokasi</th>
<th>Keterangan</th>
<th>Status</th>
<th>Feedback</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

<?php
$no = 1;
while ($row = mysqli_fetch_assoc($data)):
?>

<tr>

<td><?= $no++; ?></td>

<td><?= $row['id_pelaporan']; ?></td>

<td><?= date('d-m-Y H:i:s', strtotime($row['created_at'])); ?></td>

<td><?= htmlspecialchars($row['nis']); ?></td>

<td><?= htmlspecialchars($row['kelas']); ?></td>

<td><?= htmlspecialchars($row['ket_kategori']); ?></td>

<td><?= htmlspecialchars($row['lokasi']); ?></td>

<td><?= htmlspecialchars($row['ket']); ?></td>

<td><?= htmlspecialchars($row['status']); ?></td>

<td><?= htmlspecialchars($row['feedback']); ?></td>

<td>

<div class="aksi">

<a href="edit-aspirasi.php?id=<?= $row['id_pelaporan']; ?>" class="btn btn-edit">
Edit
</a>

<a href="aspirasi.php?hapus=<?= $row['id_pelaporan']; ?>"
class="btn btn-hapus"
onclick="return confirm('Yakin ingin menghapus data ini?')">
Hapus
</a>

</div>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>