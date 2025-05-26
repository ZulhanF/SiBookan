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
    <title>SiBookan - Sistem Booking Ruangan Gedung A10</title>
    <meta name="description" content="SiBookan adalah sistem booking ruangan untuk Gedung A10. Dapatkan akses mudah untuk memesan ruangan secara online." />
    <meta name="keywords" content="sibookan, booking ruangan, gedung a10, sistem booking, pemesanan ruangan" />
    <meta name="author" content="SiBookan" />
    <meta name="robots" content="index, follow" />
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://sibookan.my.id/" />
    <meta property="og:title" content="SiBookan - Sistem Booking Ruangan Gedung A10" />
    <meta property="og:description" content="SiBookan adalah sistem booking ruangan untuk Gedung A10. Dapatkan akses mudah untuk memesan ruangan secara online." />
    <meta property="og:image" content="https://sibookan.my.id/logo.png" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://sibookan.my.id/" />
    <meta property="twitter:title" content="SiBookan - Sistem Booking Ruangan Gedung A10" />
    <meta property="twitter:description" content="SiBookan adalah sistem booking ruangan untuk Gedung A10. Dapatkan akses mudah untuk memesan ruangan secara online." />
    <meta property="twitter:image" content="https://sibookan.my.id/logo.png" />

    <link rel="canonical" href="https://sibookan.my.id/" />
    <link rel="shortcut icon" href="🏢" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3464, #3668c0);
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
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

        .logo {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e3c72;
            text-decoration: none;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .login-btn, .register-btn {
            background: #2a5298;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .login-btn:hover, .register-btn:hover {
            background: #1e3c72;
        }

        .hero {
            padding: 8rem 0 4rem;
            text-align: center;
            color: white;
        }

        .hero h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.9;
        }

        .features {
            padding: 4rem 0;
            background: linear-gradient(135deg, #1a3464, #3668c0);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-card {
            background: #f8fafc;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .feature-card h3 {
            color: #1e3c72;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        .cta {
            padding: 4rem 0;
            text-align: center;
            color: white;
        }

        .cta h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .cta p {
            max-width: 600px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }

        .cta-btn {
            display: inline-block;
            background: white;
            color: #1e3c72;
            padding: 1rem 2rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: transform 0.3s ease;
        }

        .cta-btn:hover {
            transform: translateY(-2px);
        }

        footer {
            background: #1e3c72;
            color: white;
            padding: 2rem 0;
            text-align: center;
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
            <a href="index.php" class="logo">SiBookan</a>
            <div class="nav-buttons">
                <a href="login.php" class="login-btn">Login</a>
                <a target="_blank" href="https://api.whatsapp.com/send/?phone=%2B6281946728927&text=Min%2C+info+buat+akun+sibookan&type=phone_number&app_absent=0" class="register-btn">Register</a>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Selamat Datang di SiBookan</h1>
            <p>Sistem booking ruangan modern untuk Gedung A10. Pesan ruangan dengan mudah, cepat, dan efisien.</p>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <h3>Booking Online 24/7</h3>
                    <p>Pesan ruangan kapan saja dan di mana saja. Sistem kami tersedia 24 jam untuk memudahkan Anda.</p>
                </div>
                <div class="feature-card">
                    <h3>Proses Cepat</h3>
                    <p>Hanya butuh beberapa klik untuk memesan ruangan. Tidak perlu antri atau mengisi formulir manual.</p>
                </div>
                <div class="feature-card">
                    <h3>Notifikasi Real-time</h3>
                    <p>Dapatkan konfirmasi booking secara instan dan notifikasi pengingat sebelum waktu pemakaian.</p>
                </div>
            </div>
        </div>
        <section class="cta">
        <div class="container">
            <h2>Siap untuk Memulai?</h2>
            <p>Bergabunglah dengan SiBookan sekarang dan nikmati kemudahan dalam memesan ruangan.</p>
            <a href="login.php" class="cta-btn">Login Sekarang</a>
        </div>
    </section>
    </section>


    <footer>
        <div class="footer-content">
            <p>&copy; 2024 SiBookan. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>