<?php
session_start();
include 'koneksi.php';

/* CEK LOGIN ADMIN */
if (!isset($_SESSION['status_login']) || $_SESSION['role'] != "admin") {
   header("Location: login-admin.php");
   exit;
}

/* CEK HALAMAN AKTIF */
$halaman = basename($_SERVER['PHP_SELF']);

/* HITUNG TOTAL */
$siswa = mysqli_query($conn, "SELECT COUNT(*) as total_siswa FROM siswa");
$total_siswa = mysqli_fetch_assoc($siswa)['total_siswa'];

$kategori = mysqli_query($conn, "SELECT COUNT(*) as total_kategori FROM kategori");
$total_kategori = mysqli_fetch_assoc($kategori)['total_kategori'];

$aspirasi = mysqli_query($conn, "SELECT COUNT(*) as total_aspirasi FROM input_aspirasi");
$total_aspirasi = mysqli_fetch_assoc($aspirasi)['total_aspirasi'];

$status = mysqli_query($conn, "SELECT COUNT(*) as selesai FROM aspirasi WHERE status='Selesai'");
$selesai = mysqli_fetch_assoc($status)['selesai'];

/* DATA FILTER */
$list_siswa = mysqli_query($conn,"SELECT nis FROM siswa");
$list_kategori = mysqli_query($conn,"SELECT id_kategori, ket_kategori FROM kategori");

/* FILTER */
$where = [];

if(!empty($_GET['tanggal'])){
$where[] = "DATE(ia.created_at)='".$_GET['tanggal']."'";
}

if(!empty($_GET['bulan'])){
$where[] = "DATE_FORMAT(ia.created_at,'%Y-%m')='".$_GET['bulan']."'";
}

if(!empty($_GET['nis'])){
$where[] = "ia.nis='".$_GET['nis']."'";
}

if(!empty($_GET['kategori'])){
$where[] = "ia.id_kategori='".$_GET['kategori']."'";
}

$where_sql = count($where) > 0 ? "WHERE ".implode(" AND ",$where) : "";

/* DATA */
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
$where_sql
ORDER BY ia.id_pelaporan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin</title>

<style>
*{
box-sizing:border-box;
margin:0;
padding:0;
}

body{
font-family:'Segoe UI',sans-serif;
background:#e9f0fb;
}

/* ================= NAVBAR (SUDAH DISAMAKAN) ================= */

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

/* CONTAINER */

.container{
max-width:1400px;
margin:30px auto;
padding:0 20px;
}

h1{
margin-bottom:25px;
color:#1e3a8a;
}

/* CARD */

.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;
margin-bottom:30px;
}

.card-stat{
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 6px 15px rgba(33,150,243,0.15);
text-align:center;
}

.card-stat h2{
font-size:35px;
color:#1565c0;
}

/* FILTER */

.filter{
background:white;
padding:20px;
border-radius:12px;
margin-bottom:20px;
box-shadow:0 6px 15px rgba(0,0,0,0.08);
display:flex;
flex-wrap:wrap;
gap:10px;
}

.filter input,
.filter select{
padding:8px;
border:1px solid #ccc;
border-radius:6px;
}

.filter button{
padding:8px 14px;
border:none;
background:#1565c0;
color:white;
border-radius:6px;
cursor:pointer;
}

/* TABLE */

.card{
background:white;
border-radius:12px;
padding:25px;
overflow-x:auto;
}

table{
width:100%;
border-collapse:collapse;
}

thead{
background:#1565c0;
color:white;
}

thead th{
padding:12px;
}

tbody td{
padding:10px;
border-bottom:1px solid #ddd;
text-align:center;
}

.status{
padding:6px 12px;
border-radius:20px;
color:white;
font-size:12px;
}

.menunggu{background:#f39c12;}
.proses{background:#3498db;}
.selesai{background:#2ecc71;}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">Dashboard Admin</div>

    <div class="menu">
        <a href="dashboard.php" class="active">🏠 Dashboard</a>
        <a href="kategori.php">📂 Kategori</a>
        <a href="siswa.php">👨‍🎓 Siswa</a>
        <a href="aspirasi.php">💬 Aspirasi</a>
        <a href="logout.php" class="logout">🚪 Logout</a>
    </div>
</div>

<div class="container">

<h1>Dashboard Admin</h1>

<div class="cards">
<div class="card-stat">
<h2><?= $total_siswa ?></h2>
<p>Total Siswa</p>
</div>

<div class="card-stat">
<h2><?= $total_kategori ?></h2>
<p>Total Kategori</p>
</div>

<div class="card-stat">
<h2><?= $total_aspirasi ?></h2>
<p>Total Aspirasi</p>
</div>

<div class="card-stat">
<h2><?= $selesai ?></h2>
<p>Selesai</p>
</div>
</div>

<!-- FILTER -->
<form method="GET" class="filter">
<input type="date" name="tanggal">
<input type="month" name="bulan">

<select name="nis">
<option value="">Semua Siswa</option>
<?php while($s=mysqli_fetch_assoc($list_siswa)): ?>
<option value="<?= $s['nis'] ?>"><?= $s['nis'] ?></option>
<?php endwhile; ?>
</select>

<select name="kategori">
<option value="">Semua Kategori</option>
<?php while($k=mysqli_fetch_assoc($list_kategori)): ?>
<option value="<?= $k['id_kategori'] ?>"><?= $k['ket_kategori'] ?></option>
<?php endwhile; ?>
</select>

<button type="submit">Filter</button>

<a href="dashboard.php">
<button type="button">Reset</button>
</a>
</form>

<div class="card">
<table>
<thead>
<tr>
<th>No</th>
<th>ID</th>
<th>Tanggal</th>
<th>NIS</th>
<th>Kelas</th>
<th>Kategori</th>
<th>Lokasi</th>
<th>Keterangan</th>
<th>Status</th>
<th>Feedback</th>
</tr>
</thead>

<tbody>
<?php $no=1; while($row=mysqli_fetch_assoc($data)): ?>
<tr>
<td><?= $no++ ?></td>
<td><?= $row['id_pelaporan'] ?></td>
<td><?= date('d-m-Y H:i',strtotime($row['created_at'])) ?></td>
<td><?= $row['nis'] ?></td>
<td><?= $row['kelas'] ?></td>
<td><?= $row['ket_kategori'] ?></td>
<td><?= $row['lokasi'] ?></td>
<td><?= $row['ket'] ?></td>

<td>
<span class="status 
<?php
if($row['status']=="Proses") echo "proses";
elseif($row['status']=="Selesai") echo "selesai";
else echo "menunggu";
?>">
<?= $row['status'] ?>
</span>
</td>

<td><?= $row['feedback'] ?></td>
</tr>
<?php endwhile; ?>
</tbody>

</table>
</div>

</div>
</body>
</html>