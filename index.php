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
    <title>SiBookan UNESA - Sistem Booking Ruangan Online Gedung A10</title>
    <meta name="description" content="SiBookan adalah sistem booking ruangan online untuk Gedung A10 UNESA Ketintang. Pesan ruangan dengan mudah, cepat, dan efisien untuk mahasiswa dan dosen UNESA. Fitur pencarian real-time, booking 24/7, dan proses cepat." />
    <meta name="keywords" content="sibookan, sibookan unesa, gedung a10 unesa, booking ruangan unesa, pemesanan ruangan unesa, sistem booking unesa, reservasi ruangan unesa, jadwal ruangan unesa, ruang kuliah unesa" />
    <meta name="author" content="SiBookan UNESA" />
    <meta name="robots" content="index, follow" />
    <meta name="language" content="Indonesian" />
    <meta name="revisit-after" content="7 days" />
    <meta name="generator" content="SiBookan UNESA" />
    <meta property="og:title" content="SiBookan - Sistem Booking Ruangan Online Gedung A10" />
    <meta property="og:description" content="SiBookan adalah sistem booking ruangan online untuk Gedung A10 UNESA Ketintang. Pesan ruangan dengan mudah, cepat, dan efisien untuk mahasiswa dan dosen UNESA." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://sibookan.my.id" />
    <meta property="og:image" content="https://sibookan.my.id/favicon-96x96.png" />
    
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/svg+xml" href="/favicon.svg" />
	<link rel="shortcut icon" href="/favicon.ico" />
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
	<meta name="apple-mobile-web-app-title" content="SiBookan" />
	<link rel="manifest" href="/site.webmanifest" />
    <link rel="canonical" href="https://sibookan.my.id" />

    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebApplication",
        "name": "SiBookan",
        "description": "Sistem booking ruangan online untuk Gedung A10 UNESA Ketintang",
        "url": "https://sibookan.my.id",
        "applicationCategory": "Education",
        "operatingSystem": "Web",
        "offers": {
            "@type": "Offer",
            "price": "0",
            "priceCurrency": "IDR"
        },
        "featureList": [
            "Pencarian Ruangan Berdasarkan Waktu",
            "Booking Online 24/7",
            "Proses Cepat dan Efisien"
        ]
    }
    </script>

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
            max-width: 1500px;
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

        .login-btn,
        .register-btn {
            background: #2a5298;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .kelas-btn {
            background: #f7ad19;
            color: black;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .kelas-btn:hover {
            background:rgb(210, 145, 16);
        }

        .login-btn:hover,
        .register-btn:hover {
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
            font-size: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .features {
            padding: 4rem 0;
            background: linear-gradient(135deg, #1a3464, #3668c0);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 3rem;
            margin-top: 1rem;
        }

        .feature-card {
            background: #f8fafc;
            padding: 3rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .feature-card h2 {
            color: #1e3c72;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }

        .feature-card p {
            color: black;
            line-height: 1.6;
            font-size: 1rem;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            transition: transform 0.5s ease;
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
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h1>Selamat Datang di <span style="color: #f7ad19;">SiBookan</span></h1>
            <p>SiBookan adalah platform booking ruangan online yang dirancang khusus untuk Gedung A10 Universitas Negeri Surabaya (UNESA). Dengan antarmuka yang mudah digunakan, SiBookan membantu mahasiswa dan dosen melakukan reservasi ruangan secara cepat, efisien, dan tanpa ribet. Sistem kami menyediakan fitur pencarian real-time, booking 24/7, dan proses pemesanan yang cepat.</p>
            <a href="homesiswa.php" class="kelas-btn" aria-label="Lihat Jadwal Kelas">Lihat Kelas</a>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <h2>Pencarian Ruangan Berdasarkan Waktu</h2>
                    <p>Nikmati kemudahan mencari ruangan dengan sistem pencarian cerdas kami. Pilih tanggal dan waktu yang Anda inginkan, dan SiBookan akan menampilkan daftar ruangan yang tersedia secara real-time. Sistem kami memastikan tidak ada jadwal yang bertabrakan.</p>
                </div>
                <div class="feature-card">
                    <h2>Booking Online 24/7</h2>
                    <p>Akses sistem pemesanan ruangan kapanpun dan dimanapun Anda berada. Tidak perlu khawatir dengan jam kerja atau hari libur - SiBookan selalu siap melayani Anda 24 jam sehari, 7 hari seminggu. Proses booking yang mudah dan cepat.</p>
                </div>
                <div class="feature-card">
                    <h2>Proses Cepat dan Efisien</h2>
                    <p>Hilangkan kerumitan proses pemesanan manual. Dengan SiBookan, Anda dapat memesan ruangan dalam hitungan menit. Sistem kami yang intuitif memandu Anda melalui setiap langkah dengan jelas, dari pemilihan ruangan hingga konfirmasi booking. Hemat waktu dan tenaga Anda.</p>
                </div>
            </div>
        </div>
    </section>


    <footer>
        <div class="footer-content">
            <p>&copy; 2025 SiBookan. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>