
<?php
session_start();
include 'koneksi.php';

/* CEK LOGIN ADMIN */
if(!isset($_SESSION['status_login']) || $_SESSION['role'] != "admin"){
    header("Location: login-admin.php");
    exit;
}

$id = $_GET['id'];

/* AMBIL DATA ASPIRASI */
$data = mysqli_query($conn,"
SELECT 
ia.id_pelaporan,
ia.created_at,
ia.nis,
s.kelas,
k.ket_kategori,
ia.lokasi,
ia.ket,
a.status,
a.feedback
FROM input_aspirasi ia
JOIN siswa s ON ia.nis = s.nis
JOIN kategori k ON ia.id_kategori = k.id_kategori
LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
WHERE ia.id_pelaporan='$id'
");

$row = mysqli_fetch_assoc($data);


/* UPDATE DATA */
if(isset($_POST['submit'])){

$status = $_POST['status'];
$feedback = $_POST['feedback'];

$cek = mysqli_query($conn,"SELECT * FROM aspirasi WHERE id_pelaporan='$id'");

if(mysqli_num_rows($cek) > 0){

mysqli_query($conn,"
UPDATE aspirasi 
SET status='$status', feedback='$feedback'
WHERE id_pelaporan='$id'
");

}else{

mysqli_query($conn,"
INSERT INTO aspirasi (id_pelaporan,status,feedback)
VALUES('$id','$status','$feedback')
");

}

echo "<script>
alert('Data berhasil diperbarui');
window.location='aspirasi.php';
</script>";

}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Aspirasi</title>

<style>

/* RESET */
*{
box-sizing:border-box;
margin:0;
padding:0;
}

/* BODY */
body{
font-family:'Segoe UI',sans-serif;
background:#e9f0fb;
color:#333;
}

/* NAVBAR */
.navbar{
background:linear-gradient(135deg,#0d47a1,#1565c0,#1e88e5);
padding:16px 40px;
display:flex;
justify-content:space-between;
align-items:center;
color:white;
box-shadow:0 6px 15px rgba(0,0,0,0.25);
position:sticky;
top:0;
z-index:1000;
}

.logo{
font-size:20px;
font-weight:bold;
}

.menu{
display:flex;
gap:15px;
align-items:center;
}

.menu a{
color:white;
text-decoration:none;
padding:10px 16px;
border-radius:8px;
transition:0.3s;
}

.menu a:hover{
background:rgba(255,255,255,0.2);
}

.menu a.active{
background:white;
color:#1565c0;
font-weight:bold;
}

.logout{
background:#ff3b3b;
padding:10px 18px;
border-radius:25px;
font-weight:bold;
}

.logout:hover{
background:#c62828;
}

/* CONTAINER */
.container{
max-width:800px;
margin:40px auto;
padding:0 20px;
}

/* CARD */
.card{
background:white;
border-radius:14px;
padding:30px;
box-shadow:0 8px 20px rgba(33,150,243,0.1);
}

/* TITLE */
h1{
margin-bottom:25px;
color:#1e3a8a;
}

/* FORM */
.form-group{
margin-bottom:20px;
}

label{
display:block;
margin-bottom:8px;
font-weight:600;
}

input,textarea,select{
width:100%;
padding:10px;
border-radius:8px;
border:1px solid #ccc;
font-size:14px;
}

textarea{
resize:none;
height:100px;
}

/* BUTTON */
.btn{
background:linear-gradient(135deg,#42a5f5,#1e88e5);
color:white;
border:none;
padding:10px 18px;
border-radius:8px;
cursor:pointer;
font-weight:600;
}

.btn:hover{
background:#1565c0;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">

<div class="logo">
📢 Portal Aspirasi
</div>

<div class="menu">

<a href="dashboard.php">🏠 Dashboard</a>
<a href="kategori.php">📂 Kategori</a>
<a href="siswa.php">👨‍🎓 Siswa</a>
<a href="aspirasi.php" class="active">💬 Aspirasi</a>
<a href="logout-admin.php" class="logout">🚪 Logout</a>

</div>

</div>

<div class="container">

<div class="card">

<h1>Edit Aspirasi</h1>

<form method="POST">

<div class="form-group">
<label>Tanggal</label>
<input type="datetime-local"
value="<?= date('Y-m-d\TH:i',strtotime($row['created_at'])) ?>" disabled>
</div>

<div class="form-group">
<label>NIS</label>
<input type="text" value="<?= $row['nis']; ?>" disabled>
</div>

<div class="form-group">
<label>Kelas</label>
<input type="text" value="<?= $row['kelas']; ?>" disabled>
</div>

<div class="form-group">
<label>Kategori</label>
<input type="text" value="<?= $row['ket_kategori']; ?>" disabled>
</div>

<div class="form-group">
<label>Lokasi</label>
<input type="text" value="<?= $row['lokasi']; ?>" disabled>
</div>

<div class="form-group">
<label>Keterangan</label>
<textarea disabled><?= $row['ket']; ?></textarea> 
</div>

<div class="form-group">
<label>Status</label>

<select name="status">

<option value="Menunggu" <?= $row['status']=="Menunggu"?'selected':'' ?>>Menunggu</option>
<option value="Proses" <?= $row['status']=="Proses"?'selected':'' ?>>Proses</option>
<option value="Selesai" <?= $row['status']=="Selesai"?'selected':'' ?>>Selesai</option>

</select>

</div>

<div class="form-group">
<label>Feedback</label>
<textarea name="feedback"><?= $row['feedback']; ?></textarea>
</div>

<button type="submit" name="submit" class="btn">
Update Data
</button>

</form>

</div>

</div>

</body>
</html>
