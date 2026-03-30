<?php
session_start();

/* HAPUS SEMUA SESSION */
session_unset();
session_destroy();

/* PINDAH KE DASHBOARD SISWA */
header("Location: index.php");
exit;
?>