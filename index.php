<?php
include 'koneksi.php';

/* ============================
   HITUNG STATUS
============================ */
$statusData = mysqli_query($conn, "
    SELECT status, COUNT(*) as total
    FROM aspirasi
    GROUP BY status
");

$proses = 0;
$menunggu = 0;
$selesai = 0;

while ($rowStatus = mysqli_fetch_assoc($statusData)) {

    $status = strtolower($rowStatus['status']);

    if ($status == 'proses') {
        $proses = $rowStatus['total'];
    } elseif ($status == 'menunggu') {
        $menunggu = $rowStatus['total'];
    } elseif ($status == 'selesai') {
        $selesai = $rowStatus['total'];
    }
}

/* ============================
   AMBIL DATA ASPIRASI
============================ */
$data = mysqli_query($conn, "
    SELECT 
        siswa.nis,
        kategori.ket_kategori,
        input_aspirasi.lokasi,
        input_aspirasi.ket,
        aspirasi.status,
        aspirasi.feedback
    FROM input_aspirasi
    LEFT JOIN aspirasi 
        ON aspirasi.id_pelaporan = input_aspirasi.id_pelaporan
    JOIN siswa 
        ON input_aspirasi.nis = siswa.nis
    JOIN kategori 
        ON input_aspirasi.id_kategori = kategori.id_kategori
    ORDER BY input_aspirasi.id_pelaporan DESC
");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dashboard Aspirasi</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Segoe UI;
            background: #f4f6fb;
        }

        .navbar {
            background: linear-gradient(to right, #1e3c72, #2a5298);
            padding: 18px 40px;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            color: white;
            margin: 0;
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

        .login:hover {
            background: #1e8449;
        }

        .container {
            width: 90%;
            margin: 40px auto;
        }

        .cards {
            display: flex;
            gap: 25px;
            margin-bottom: 35px;
        }

        .card {
            flex: 1;
            padding: 30px;
            border-radius: 15px;
            color: white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .proses-card {
            background: #3498db;
        }

        .menunggu-card {
            background: #f39c12;
        }

        .selesai-card {
            background: #2ecc71;
        }

        .card h3 {
            margin: 0;
        }

        .card .number {
            font-size: 32px;
            font-weight: bold;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        th {
            background: #1e3c72;
            color: white;
            padding: 15px;
            text-align: left;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        tr:hover {
            background: #f9fbff;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
        }

        .proses {
            background: #d1ecf1;
            color: #0c5460;
        }

        .menunggu {
            background: #fff3cd;
            color: #856404;
        }

        .selesai {
            background: #d4edda;
            color: #155724;
        }

        footer {
            text-align: center;
            padding: 25px;
            margin-top: 50px;
            color: #777;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="nav-content">

            <h2>Portal Aspirasi Siswa</h2>

            <div class="menu">
                <a href="index.php">Dashboard</a>
                <a href="input-aspirasi.php">Input Aspirasi</a>
                <a href="login.php" class="login">Login</a>
            </div>

        </div>
    </div>

    <div class="container">

        <div class="cards">

            <div class="card proses-card">
                <h3>Proses</h3>
                <div class="number"><?php echo $proses; ?></div>
            </div>

            <div class="card menunggu-card">
                <h3>Menunggu</h3>
                <div class="number"><?php echo $menunggu; ?></div>
            </div>

            <div class="card selesai-card">
                <h3>Selesai</h3>
                <div class="number"><?php echo $selesai; ?></div>
            </div>

        </div>

        <h3>Data Aspirasi Siswa</h3>

        <table>

            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Feedback</th>
            </tr>

            <?php
            $no = 1;

            if (mysqli_num_rows($data) > 0) {

                while ($row = mysqli_fetch_assoc($data)) {

                    $status = strtolower($row['status'] ?? 'menunggu');
            ?>

                    <tr>

                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['nis']; ?></td>
                        <td><?php echo $row['ket_kategori']; ?></td>
                        <td><?php echo $row['lokasi']; ?></td>
                        <td><?php echo $row['ket']; ?></td>

                        <td>

                            <span class="badge 
<?php
                    if ($status == 'proses') echo 'proses';
                    elseif ($status == 'selesai') echo 'selesai';
                    else echo 'menunggu';
?>
">

                                <?php echo ucfirst($status); ?>

                            </span>

                        </td>

                        <td><?php echo $row['feedback'] ?? '-'; ?></td>

                    </tr>

                <?php
                }
            } else {
                ?>

                <tr>
                    <td colspan="7" style="text-align:center;">Belum ada data aspirasi</td>
                </tr>

            <?php } ?>

        </table>

    </div>

    <footer>
        © 2026 Portal Suara Siswa
    </footer>

</body>

</html>