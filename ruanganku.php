<?php
include "database.php";

session_start();

$error_message = '';

if (isset($_POST['login'])) {
    $password = $_POST['password'];
    $username = $_POST['username'];

    $login = "SELECT * FROM user WHERE username='$username' AND password = '$password'";
    $masuk = $db->query($login);

    if ($masuk->num_rows > 0) {
        $tabel = $masuk->fetch_assoc();
        $_SESSION["username"] = $tabel["username"];
        $_SESSION["nama"] = $tabel["nama"];
        $_SESSION["is_login"] = true;
        header("Location: home.php");
        exit();
    } else {
        $error_message = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SiBookan UNESA - Sistem Booking Ruangan Gedung A10</title>
    <meta name="description" content="SiBookan adalah sistem booking ruangan online untuk Gedung A10 UNESA Ketintang. Pesan ruangan dengan mudah, cepat, dan efisien untuk mahasiswa dan dosen UNESA." />
    <meta name="keywords" content="sibookan, sibookan unesa, gedung a10 unesa, booking ruangan unesa, pemesanan ruangan unesa, sistem booking unesa" />
    <meta name="author" content="SiBookan UNESA" />
    <meta name="robots" content="index, follow" />

    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="favicon.svg" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        html, body {
            height: 100%;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3464, #3668c0);
            color: #333;
            display: flex;
            flex-direction: column;
        }

        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 2rem;
            flex: 1;
        }

        header {
            background: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
            order: 1;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e3c72;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo .material-icons {
            color: #1e3c72;
            font-size: 24px;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
            order: 2;
        }

        .login-btn,
        .register-btn,
        .ruanganku-btn,
        .daftarpj-btn {
            background: #2a5298;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .login-btn:hover,
        .register-btn:hover,
        .ruanganku-btn:hover,
        .daftarpj-btn:hover {
            background: #1e3c72;
            transform: translateY(-2px);
        }

        /* Table Styles */
        .table-container {
            margin-top: 8rem;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
            margin-bottom: 2rem;
            margin-left: 10rem;
            margin-right: 10rem;
        }

        h1 {
            margin-bottom: 1rem;
            font-size: 2rem;
            font-weight: 600;
            color: #429ebd;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            background: white;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #1e3c72;
            color: white;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .header-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .material-icons {
            font-size: 25px;
            color: white;
        }

        tr {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .button-container {
            display: flex;
            justify-content: right;
            gap: 1rem;
            margin-top: 1rem;
        }
        .button {
            background-color: #2a5298;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
        }
        .button:hover {
            background-color: #1e3c72;
        }

        footer {
            background: #1e3c72;
            color: white;
            padding: 2rem 0;
            text-align: center;
            margin-top: auto;
            width: 100%;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-content p {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="header-content">
            <div class="logo-container">
                <a href="index.php" class="logo">
                    <span class="material-icons">school</span>
                    SiBookan
                </a>
            </div>
            <div class="nav-buttons">
                <a href="home.php" class="login-btn">
                    <span class="material-icons">event_available</span>
                    Booking
                </a>
                <a href="ruanganku.php" class="ruanganku-btn">
                    <span class="material-icons">meeting_room</span>
                    Ruanganku
                </a>
                <a href="daftarpj.php" class="daftarpj-btn">
                    <span class="material-icons">group</span>
                    Daftar PJ
                </a>
            </div>
        </div>
    </header>

    <div class="table-container">
        <h1>Daftar <span style="color: #f7ad19;">Ruanganku</span></h1>
        <table>
            <tr>
                <th>
                    <div class="header-cell">
                        <span class="material-icons">calendar_month</span>
                        Tanggal
                    </div>
                </th>
                <th>
                    <div class="header-cell">
                        <span class="material-icons">meeting_room</span>
                        Nomor Ruangan
                    </div>
                </th>
                <th>
                    <div class="header-cell">
                        <span class="material-icons">schedule</span>
                        Waktu
                    </div>
                </th>
                <th>
                    <div class="header-cell">
                        <span class="material-icons">menu_book</span>
                        Matkul
                    </div>
                </th>
                <th>
                    <div class="header-cell">
                        <span class="material-icons">school</span>
                        Kelas
                    </div>
                </th>
            </tr>
            <tr>
                <td>A10.01.1</td>
                <td>Tersedia</td>
                <td>Ricky</td>
                <td>PBP</td>
                <td>TI23C</td>
            </tr>
            <tr>
                <td>A10.01.1</td>
                <td>Tersedia</td>
                <td>Ricky</td>
                <td>PBP</td>
                <td>TI23C</td>
            </tr>
        </table>
        <div class="button-container">
            <a href="ubahjadwal.php" class="button">Ubah Jadwal</a>
            <a href="hapusjadwal.php" class="button">Hapus Jadwal</a>
        </div>
    </div>
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 SiBookan. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>