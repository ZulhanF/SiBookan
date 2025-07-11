<?php
include "database.php";

session_start();

$error_message = '';
$matakuliah_list = []; // Inisialisasi array kosong

// Cek apakah user sudah login
if (!isset($_SESSION["is_login"])) {
    header("Location: /login");
    exit();
}

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
        header("Location: /home");
        exit();
    } else {
        $error_message = "Username atau password salah!";
    }
}

// Fungsi untuk mengambil mata kuliah berdasarkan dosen
function getMatakuliahByDosen($db, $username)
{
    $query = "SELECT mk.id_matkul, mk.nama_matkul 
              FROM mata_kuliah mk
              INNER JOIN dosen_mata_kuliah dmk ON mk.id_matkul = dmk.id_matkul
              INNER JOIN dosen u ON dmk.id_dosen = u.id_dosen
              WHERE u.username = ?
              ORDER BY mk.nama_matkul ASC";

    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $matakuliah_list = [];
    while ($row = $result->fetch_assoc()) {
        $matakuliah_list[] = $row;
    }

    return $matakuliah_list;
}

// Ambil mata kuliah untuk dosen yang login hanya jika sudah login
if (isset($_SESSION["username"])) {
    $matakuliah_list = getMatakuliahByDosen($db, $_SESSION["username"]);
}

// Query untuk mengambil data booking
$filter_tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$filter_jam = isset($_GET['jam']) ? $_GET['jam'] : '';

if ($filter_jam && $filter_tanggal) {
    // Filter berdasarkan tanggal dan jam
    $query = "SELECT br.*, d.nama_dosen, mk.nama_matkul 
              FROM booking_ruangan br 
              JOIN dosen d ON br.id_dosen = d.id_dosen 
              JOIN mata_kuliah mk ON br.id_matkul = mk.id_matkul 
              WHERE br.tanggal = '$filter_tanggal' 
              AND (
                  (br.jam_mulai <= '$filter_jam' AND DATE_ADD(br.jam_mulai, INTERVAL br.durasi MINUTE) > '$filter_jam')
              )
              ORDER BY br.tanggal DESC, br.jam_mulai ASC";
    $result = mysqli_query($db, $query);
    $filter_active = true;
} else if ($filter_tanggal) {
    // Filter berdasarkan tanggal saja
    $query = "SELECT br.*, d.nama_dosen, mk.nama_matkul 
              FROM booking_ruangan br 
              JOIN dosen d ON br.id_dosen = d.id_dosen 
              JOIN mata_kuliah mk ON br.id_matkul = mk.id_matkul 
              WHERE br.tanggal = '$filter_tanggal' 
              ORDER BY br.tanggal DESC, br.jam_mulai ASC";
    $result = mysqli_query($db, $query);
    $filter_active = true;
} else {
    // Default: tampilkan semua
    $query = "SELECT br.*, d.nama_dosen, mk.nama_matkul 
              FROM booking_ruangan br 
              JOIN dosen d ON br.id_dosen = d.id_dosen 
              JOIN mata_kuliah mk ON br.id_matkul = mk.id_matkul 
              ORDER BY br.tanggal DESC, br.jam_mulai ASC";
    $result = mysqli_query($db, $query);
    $filter_active = false;
}

// Proses form booking
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_booking'])) {
    $tanggal = mysqli_real_escape_string($db, $_POST['tanggal']);
    $jam_mulai = mysqli_real_escape_string($db, $_POST['jam']);
    $jumlah_sks = mysqli_real_escape_string($db, $_POST['sks']);
    $nomor_ruangan = mysqli_real_escape_string($db, $_POST['ruangan']);
    $id_matkul = mysqli_real_escape_string($db, $_POST['matkul']);
    $kelas = mysqli_real_escape_string($db, $_POST['kelas']);

    // Validasi tanggal tidak boleh di masa lalu
    $today = date('Y-m-d');
    if ($tanggal < $today) {
        header("Location: home.php?notif=invalid_date");
        exit();
    }

    // Hitung durasi berdasarkan SKS (1 SKS = 50 menit)
    $durasi = $jumlah_sks * 50;

    // Ambil id_dosen dari session
    $username = $_SESSION["username"];
    $query_dosen = "SELECT id_dosen FROM dosen WHERE username = '$username'";
    $result_dosen = mysqli_query($db, $query_dosen);
    $dosen = mysqli_fetch_assoc($result_dosen);
    $id_dosen = $dosen['id_dosen'];

    // Cek apakah ruangan sudah dibooking pada waktu yang sama
    $check_query = "SELECT * FROM booking_ruangan 
                   WHERE nomor_ruangan = '$nomor_ruangan' 
                   AND tanggal = '$tanggal' 
                   AND (
                       (jam_mulai <= '$jam_mulai' AND DATE_ADD(jam_mulai, INTERVAL durasi MINUTE) > '$jam_mulai')
                       OR 
                       (jam_mulai < DATE_ADD('$jam_mulai', INTERVAL $durasi MINUTE) AND jam_mulai >= '$jam_mulai')
                   )";
    $check_result = mysqli_query($db, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        header("Location: home.php?notif=fail_booking");
        exit();
        // echo "<script>alert('Ruangan sudah dibooking pada waktu tersebut!');</script>";
    } else {
        // Insert booking baru
        $insert_query = "INSERT INTO booking_ruangan (tanggal, jam_mulai, durasi, jumlah_sks, nomor_ruangan, id_matkul, kelas, id_dosen, status_booking) 
                        VALUES ('$tanggal', '$jam_mulai', $durasi, '$jumlah_sks', '$nomor_ruangan', '$id_matkul', '$kelas', '$id_dosen', 'Dipakai')";

        if (mysqli_query($db, $insert_query)) {
            header("Location: home.php?notif=success_booking");
            exit();
            // echo "<script>alert('Booking berhasil!'); window.location.href='home.php';</script>";
        } else {
            header("Location: home.php?notif=error_booking");
            exit();
            // echo "<script>alert('Error: " . mysqli_error($db) . "');</script>";
        }
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

        html,
        body {
            height: 100%;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3464, #3668c0);
            background-size: cover;
            color: #333;
            display: flex;
            flex-direction: column;
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

        .hamburger {
            display: none;
            cursor: pointer;
            padding: 10px;
        }

        .hamburger .material-icons {
            color: #1e3c72;
            font-size: 28px;
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

        .material-icons {
            font-size: 25px;
            color: white;
        }

        .table-container {
            margin-top: 8rem;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            margin-left: auto;
            margin-right: auto;
            max-width: 1500px;
            width: calc(100% - 4rem);
        }

        h1 {
            margin-bottom: 1rem;
            font-size: 2rem;
            font-weight: 600;
            color: #429ebd;
            text-align: center;
        }

        table {
            background: #ffffff;
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            word-wrap: break-word;
        }

        th {
            background-color: #1e3c72;
            color: white;
            font-weight: 500;
        }

        .header-cell {
            display: flex;
            align-items: center;
            gap: 8px;
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
            margin-top: 0;
            margin-bottom: 1rem;
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

        .Booking {
            width: calc(100% - 4rem);
            max-width: 1500px;
            background: #1a3464;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 0 auto 2rem auto;
        }

        .Booking p {
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            color: #f5f5f5;
            margin-bottom: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .form-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-item label {
            font-size: 15px;
            font-weight: 500;
            color: #f5f5f5;
        }

        .Booking select,
        .Booking input[type="date"],
        .Booking input[type="text"] {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #bcd0ee;
            font-size: 15px;
            color: #1a3464;
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .Booking select:focus,
        .Booking input[type="date"]:focus,
        .Booking input[type="text"]:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 3px rgba(42, 82, 152, 0.1);
        }

        .Booking select option {
            padding: 8px;
        }

        .button_booking {
            background: #2a5298;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
        }

        .button_booking:hover {
            background: #1e3c72;
            transform: translateY(-2px);
        }

        .button_booking:active {
            transform: translateY(0);
        }

        @media (max-width: 1200px) {
            .table-container,
            .Booking {
                width: calc(100% - 4rem);
                margin: 2rem auto;
            }
        }

        @media (max-width: 900px) {
            .table-container,
            .Booking {
                width: calc(100% - 2rem);
                padding: 24px 16px;
                margin: 1rem auto;
            }

            .form-grid {
                gap: 16px;
            }
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .table-container,
            .Booking {
                width: calc(100% - 2rem);
                padding: 16px;
                margin: 1rem auto;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .Booking p {
                font-size: 20px;
            }
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

        @media screen and (max-width: 1024px) {
            .table-container {
                width: 95%;
                padding: 1rem;
            }

            th,
            td {
                padding: 10px;
                font-size: 14px;
            }
        }

        @media screen and (max-width: 768px) {
            .header-content {
                padding: 0 1rem;
            }

            .nav-buttons {
                gap: 0.5rem;
            }

            .login-btn,
            .register-btn,
            .ruanganku-btn,
            .daftarpj-btn {
                padding: 0.6rem 1rem;
                font-size: 14px;
            }

            .material-icons {
                font-size: 20px;
            }

            .table-container {
                width: 98%;
                margin-top: 6rem;
                padding: 0.8rem;
            }

            .button-container {
                flex-direction: column;
                align-items: stretch;
            }

            .button {
                width: 100%;
                text-align: center;
                margin-bottom: 0.5rem;
            }

            .Booking {
                width: 98%;
                padding: 1rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }

        @media screen and (max-width: 480px) {
            .logo {
                font-size: 1.2rem;
            }

            .nav-buttons {
                flex-direction: column;
                width: 100%;
            }

            .login-btn,
            .register-btn,
            .ruanganku-btn,
            .daftarpj-btn {
                width: 100%;
                justify-content: center;
            }

            .table-container {
                margin-top: 12rem;
            }

            th,
            td {
                padding: 8px;
                font-size: 12px;
            }
        }

        /* Set column widths */
        th:nth-child(1),
        td:nth-child(1) {
            width: 15%;
        }

        /* Tanggal */
        th:nth-child(2),
        td:nth-child(2) {
            width: 15%;
        }

        /* Nomor Ruangan */
        th:nth-child(3),
        td:nth-child(3) {
            width: 15%;
        }

        /* Status */
        th:nth-child(4),
        td:nth-child(4) {
            width: 25%;
        }

        /* Matkul */
        th:nth-child(5),
        td:nth-child(5) {
            width: 15%;
        }

        /* Kelas */
        th:nth-child(6),
        td:nth-child(6) {
            width: 15%;
        }

        .button_booking {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
        }

        .button_booking:hover {
            background-color: rgb(55, 146, 58);
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 16px;
            background: #f5f7fa;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(66, 158, 189, 0.07);
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .filter-form label {
            font-weight: 500;
            color: #1e3c72;
            margin-right: 4px;
        }

        .filter-form input[type="date"],
        .filter-form select {
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #bcd0ee;
            font-size: 15px;
            background: #fff;
            color: #1a3464;
            min-width: 120px;
        }

        .filter-form .button {
            padding: 8px 18px;
            font-size: 15px;
            border-radius: 5px;
            background: #2a5298;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .filter-form .button:hover {
            background: #1e3c72;
        }

        @media (max-width: 600px) {
            .filter-form {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
                padding: 10px 6px;
            }
        }

        .notif-popup {
            position: fixed;
            top: 80px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 280px;
            max-width: 90vw;
            background: #fff;
            color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.13);
            padding: 18px 32px;
            font-size: 1.1rem;
            font-weight: 500;
            z-index: 3000;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 6px solid #2a5298;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s, top 0.3s;
        }

        .notif-popup.show {
            opacity: 1;
            pointer-events: auto;
            top: 100px;
        }

        .notif-popup.success {
            border-left-color: #4caf50;
        }

        .notif-popup.fail {
            border-left-color: #e53935;
        }

        .notif-popup.error {
            border-left-color: #fbc02d;
        }

        .notif-popup .material-icons {
            font-size: 28px;
        }

        .nav-btn-logout {
            background: #e53935;
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

        .nav-btn-logout:hover {
            background: #b71c1c;
            transform: translateY(-2px);
        }

        .mobile-nav {
            display: none;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            background: white;
            padding: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        .mobile-nav.show {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .mobile-nav a {
            width: 100%;
            text-align: center;
        }

        /* Mobile Table Styles */
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0 -1rem;
            padding: 0 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        @media screen and (max-width: 768px) {
            .hamburger {
                display: block;
            }

            .nav-buttons {
                display: none;
            }

            .table-container {
                margin-top: 6rem;
                padding: 1rem;
            }

            .table-wrapper {
                margin: 0 -1rem;
                padding: 0 1rem;
            }

            table {
                min-width: 800px;
                margin: 0;
            }

            .Booking {
                margin-top: 1rem;
            }

            .form-grid {
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
            <div class="hamburger" id="hamburger">
                <span class="material-icons">menu</span>
            </div>
            <div class="nav-buttons">
                <a href="/home" class="login-btn">
                    <span class="material-icons">event_available</span>
                    Booking
                </a>
                <a href="/ruanganku" class="ruanganku-btn">
                    <span class="material-icons">meeting_room</span>
                    Ruanganku
                </a>
                <a href="/daftarpj" class="daftarpj-btn">
                    <span class="material-icons">group</span>
                    Daftar PJ
                </a>
                <a href="/logout" class="nav-btn-logout">
                    <span class="material-icons">logout</span>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobileNav">
        <a href="/home" class="login-btn">
            <span class="material-icons">event_available</span>
            Booking
        </a>
        <a href="/ruanganku" class="ruanganku-btn">
            <span class="material-icons">meeting_room</span>
            Ruanganku
        </a>
        <a href="/daftarpj" class="daftarpj-btn">
            <span class="material-icons">group</span>
            Daftar PJ
        </a>
        <a href="/logout" class="nav-btn-logout">
            <span class="material-icons">logout</span>
            Logout
        </a>
    </div>

    <div class="table-container">
        <h1>Booking <span style="color: #f7ad19;">Ruangan</span></h1>
        <form method="get" class="filter-form">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" value="<?= htmlspecialchars($filter_tanggal) ?>" required>
            <label for="jam">Jam</label>
            <select name="jam" id="jam">
                <option value="">Pilih Jam</option>
                <option value="07:00" <?= $filter_jam == "07:00" ? 'selected' : ''; ?>>07:00</option>
                <option value="07:50" <?= $filter_jam == "07:50" ? 'selected' : ''; ?>>07:50</option>
                <option value="08:40" <?= $filter_jam == "08:40" ? 'selected' : ''; ?>>08:40</option>
                <option value="09:30" <?= $filter_jam == "09:30" ? 'selected' : ''; ?>>09:30</option>
                <option value="10:20" <?= $filter_jam == "10:20" ? 'selected' : ''; ?>>10:20</option>
                <option value="11:10" <?= $filter_jam == "11:10" ? 'selected' : ''; ?>>11:10</option>
                <option value="12:00" <?= $filter_jam == "12:00" ? 'selected' : ''; ?>>12:00</option>
                <option value="12:50" <?= $filter_jam == "12:50" ? 'selected' : ''; ?>>12:50</option>
                <option value="13:40" <?= $filter_jam == "13:40" ? 'selected' : ''; ?>>13:40</option>
                <option value="14:30" <?= $filter_jam == "14:30" ? 'selected' : ''; ?>>14:30</option>
                <option value="15:20" <?= $filter_jam == "15:20" ? 'selected' : ''; ?>>15:20</option>
                <option value="16:10" <?= $filter_jam == "16:10" ? 'selected' : ''; ?>>16:10</option>
            </select>
            <button type="submit" class="button">Cek</button>
        </form>
        <div class="table-wrapper">
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
                            Status
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
                    <th>
                        <div class="header-cell">
                            <span class="material-icons">person</span>
                            Dosen
                        </div>
                    </th>
                </tr>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . date('Y-m-d', strtotime($row['tanggal'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nomor_ruangan']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status_booking']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['jam_mulai']) . " -(" . htmlspecialchars($row['jumlah_sks']) . "SKS )</td>";
                        echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_dosen']) . "</td>";
                        echo "</tr>";
                    }
                } else if ($filter_active) {
                    echo "<tr><td colspan='6' style='text-align: center; color: #28a745; font-family: 'Poppins', sans-serif; font-weight: bold;'>Semua kelas tersedia pada jam dan tanggal tersebut</td></tr>";
                } else {
                    echo "<tr><td colspan='6' style='text-align: center;'>Tidak ada data booking</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    <div class="Booking">
        <p>Mau Booking Dimana ?</p>
        <form method="POST" action="">
            <div class="form-grid">
                <div class="form-item">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" min="<?= date('Y-m-d') ?>" required />
                </div>
                <div class="form-item">
                    <label for="jam">Jam Mulai</label>
                    <select name="jam" id="jam" required>
                        <option value="" disabled selected>Pilih Jam Mulai</option>
                        <option value="07:00">07:00</option>
                        <option value="07:50">07:50</option>
                        <option value="08:40">08:40</option>
                        <option value="09:30">09:30</option>
                        <option value="10:20">10:20</option>
                        <option value="11:10">11:10</option>
                        <option value="12:00">12:00</option>
                        <option value="12:50">12:50</option>
                        <option value="13:40">13:40</option>
                        <option value="14:30">14:30</option>
                        <option value="15:20">15:20</option>
                        <option value="16:10">16:10</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="sks">Jumlah SKS</label>
                    <select name="sks" id="sks" required>
                        <option value="" disabled selected>Pilih Jumlah SKS</option>
                        <option value="1">1 SKS</option>
                        <option value="2">2 SKS</option>
                        <option value="3">3 SKS</option>
                        <option value="4">4 SKS</option>
                        <option value="5">5 SKS</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="ruangan">Ruangan</label>
                    <select name="ruangan" id="ruangan" required>
                        <option value="" disabled selected>Pilih Ruangan</option>
                        <option value="A10.01.01">A10.01.01</option>
                        <option value="A10.01.02">A10.01.02</option>
                        <option value="A10.01.03">A10.01.03</option>
                        <option value="A10.01.04">A10.01.04</option>
                        <option value="A10.01.05">A10.01.05</option>
                        <option value="A10.01.06">A10.01.06</option>
                        <option value="A10.01.07">A10.01.07</option>
                        <option value="A10.01.08">A10.01.08</option>
                        <option value="A10.01.09">A10.01.09</option>
                        <option value="A10.01.10">A10.01.10</option>
                        <option value="A10.01.11">A10.01.11</option>
                        <option value="A10.01.12">A10.01.12</option>
                        <option value="A10.01.13">A10.01.13</option>
                        <option value="A10.01.14">A10.01.14</option>
                        <option value="A10.01.15">A10.01.15</option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="matkul">Mata Kuliah</label>
                    <select name="matkul" id="matkul" required>
                        <option value="" disabled selected>Pilih Mata Kuliah</option>
                        <?php
                        if (!empty($matakuliah_list)) {
                            foreach ($matakuliah_list as $matkul) {
                                echo '<option value="' . $matkul['id_matkul'] . '">';
                                echo htmlspecialchars($matkul['nama_matkul']);
                                echo '</option>';
                            }
                        } else {
                            echo '<option value="" disabled>Tidak ada mata kuliah yang tersedia</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-item">
                    <label for="kelas">Kelas</label>
                    <input type="text" id="kelas" name="kelas" placeholder="Contoh: TI23C" required />
                </div>
            </div>
            <div style="text-align: center;">
                <button class="button_booking" type="submit" name="submit_booking">
                    <span class="material-icons">event_available</span>
                    Booking Sekarang
                </button>
            </div>
        </form>
    </div>
    <!-- Notifikasi Pop-up -->
    <div id="notif-popup" class="notif-popup" style="display:none;"></div>
    <script>
        // Notifikasi pop-up
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const notif = urlParams.get('notif');
            if (notif) {
                const notifPopup = document.getElementById('notif-popup');
                let message = '';
                let icon = '';
                let notifClass = '';
                if (notif === 'success_booking') {
                    message = 'Booking berhasil!';
                    icon = '<span class="material-icons" style="color:#4caf50;">check_circle</span>';
                    notifClass = 'success';
                } else if (notif === 'fail_booking') {
                    message = 'Ruangan sudah dibooking pada waktu tersebut!';
                    icon = '<span class="material-icons" style="color:#e53935;">error</span>';
                    notifClass = 'fail';
                } else if (notif === 'error_booking') {
                    message = 'Terjadi kesalahan saat booking.';
                    icon = '<span class="material-icons" style="color:#fbc02d;">warning</span>';
                    notifClass = 'error';
                } else if (notif === 'invalid_date') {
                    message = 'Tidak dapat booking untuk tanggal yang sudah lewat!';
                    icon = '<span class="material-icons" style="color:#e53935;">error</span>';
                    notifClass = 'fail';
                }
                notifPopup.innerHTML = icon + '<span>' + message + '</span>';
                notifPopup.className = 'notif-popup show ' + notifClass;
                notifPopup.style.display = 'flex';
                setTimeout(function() {
                    notifPopup.classList.remove('show');
                    setTimeout(function() {
                        notifPopup.style.display = 'none';
                        // Hapus query string notif dari URL tanpa reload
                        if (window.history.replaceState) {
                            window.history.replaceState({}, '', window.location.pathname);
                        }
                    }, 500);
                }, 3000);
            }

            // Hamburger Menu Toggle
            const hamburger = document.getElementById('hamburger');
            const mobileNav = document.getElementById('mobileNav');

            hamburger.addEventListener('click', function() {
                mobileNav.classList.toggle('show');
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!hamburger.contains(event.target) && !mobileNav.contains(event.target)) {
                    mobileNav.classList.remove('show');
                }
            });
        });

        // Set min date ke hari ini
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('tanggal').setAttribute('min', today);
        });
    </script>

    <footer>
        <div class="footer-content">
            <p>&copy; 2025 SiBookan. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>