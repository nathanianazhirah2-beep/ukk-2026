<?php
session_start();
include 'koneksi.php';

/* ============================
   AMBIL DATA BERDASARKAN ID
============================ */
$id = $_GET['id'];

$query = mysqli_query($conn,"SELECT * FROM kategori WHERE id_kategori='$id'");
$data = mysqli_fetch_array($query);


/* ============================
   PROSES UPDATE
============================ */
if(isset($_POST['update'])){

    $kategori = $_POST['kategori'];

    $update = mysqli_query($conn,"UPDATE kategori 
        SET kategori='$kategori'
        WHERE id_kategori='$id'
    ");

    if($update){
        echo "<script>
        alert('Kategori berhasil diupdate');
        window.location='kategori.php';
        </script>";
    }else{
        echo "<script>
        alert('Kategori gagal diupdate');
        </script>";
    }

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Kategori</title>

<style>

body{
    font-family:Segoe UI;
    background:#f4f6f9;
    margin:0;
}

.container{
    width:400px;
    margin:80px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

input[type=text]{
    width:100%;
    padding:10px;
    margin-top:10px;
    margin-bottom:20px;
    border:1px solid #ccc;
    border-radius:6px;
}

button{
    width:100%;
    padding:12px;
    border:none;
    background:#2196f3;
    color:white;
    font-size:16px;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#1976d2;
}

a{
    display:block;
    text-align:center;
    margin-top:10px;
    text-decoration:none;
    color:#555;
}

</style>

</head>

<body>

<div class="container">

<h2>Edit Kategori</h2>

<form method="POST">

<label>Nama Kategori</label>
<input type="text" name="kategori" value="<?php echo $data['kategori']; ?>" required>

<button type="submit" name="update">Update Kategori</button>

<a href="kategori.php">Kembali</a>

</form>

</div>

</body>
</html>