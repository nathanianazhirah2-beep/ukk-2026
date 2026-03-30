<?php
session_start();
include 'koneksi.php';

/* ============================
   PROSES LOGIN ADMIN
============================ */
if (isset($_POST['submit'])) {

    $user = mysqli_real_escape_string($conn, $_POST['user']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    $cek = mysqli_query($conn, "SELECT * FROM admin 
                                WHERE Username='$user' 
                                AND password='" . md5($pass) . "'");

    if (mysqli_num_rows($cek) > 0) {

        $data = mysqli_fetch_assoc($cek);

        $_SESSION['status_login'] = true;
        $_SESSION['role']         = "admin";
        $_SESSION['id_admin']     = $data['id_admin'];
        $_SESSION['username']     = $data['Username'];

        echo "<script>
                alert('Login berhasil!');
                window.location='dashboard.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Username atau Password salah!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Portal Aspirasi</title>

    <style>
        /* RESET */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        /* BODY */
        body {
            background: linear-gradient(135deg, #0d47a1, #1565c0, #1e88e5);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* LOGIN BOX */
        .login-box {
            background: white;
            width: 360px;
            padding: 40px;
            border-radius: 14px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        /* TITLE */
        .login-box h2 {
            margin-bottom: 25px;
            color: #0d47a1;
            font-size: 28px;
            font-weight: bold;
        }

        /* INPUT WRAPPER */
        .input-group {
            position: relative;
        }

        /* INPUT */
        .login-box input {
            width: 100%;
            padding: 14px 40px 14px 12px;
            margin: 12px 0;
            border: none;
            border-radius: 8px;
            background: #e3f2fd;
            font-size: 15px;
        }

        .login-box input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #1565c0;
        }

        /* ICON MATA */
        .eye-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
        }

        /* BUTTON */
        .login-box button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2196f3, #1e88e5);
            border: none;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        .login-box button:hover {
            background: linear-gradient(135deg, #1565c0, #0d47a1);
        }
    </style>
</head>

<body>

    <div class="login-box">

        <h2>Login Admin</h2>

        <form method="POST">

            <input type="text" name="user" placeholder="Masukkan Username" required>

            <div class="input-group">
                <input type="password" name="pass" id="password" placeholder="Masukkan Password" required>
                <span class="eye-icon" onclick="togglePassword()" id="eye">👁️</span>
            </div>

            <button type="submit" name="submit">Login</button>

        </form>

    </div>

    <script>
        function togglePassword() {
            var x = document.getElementById("password");
            var eye = document.getElementById("eye");

            if (x.type === "password") {
                x.type = "text";
                eye.innerHTML = "🙈";
            } else {
                x.type = "password";
                eye.innerHTML = "👁️";
            }
        }
    </script>

</body>

</html>