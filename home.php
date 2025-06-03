<?php
include "database.php";

session_start();

$error_message = '';
$matakuliah_list = []; // Inisialisasi array kosong

// Cek apakah user sudah login
if (!isset($_SESSION["is_login"])) {
    header("Location: login.php");
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
        header("Location: home.php");
        exit();
    } else {
        $error_message = "Username atau password salah!";
    }
}

// Fungsi untuk mengambil mata kuliah berdasarkan dosen
function getMatakuliahByDosen($db, $username) {
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
            width: 90%;
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

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
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
            width: 100%;
            max-width: 1600px;
            margin: 10px auto;
            background-color: #1a3464;
            border-radius: 10px;
            padding: 32px 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.13);
            font-family: Arial, sans-serif;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        .form-item {
            display: flex;
            flex-direction: column;
        }

        .form-item label {
            font-size: 15px;
            margin-bottom: 6px;
            color: #fff;
            font-weight: bold;
        }

        .Booking select,
        .Booking input[type="date"],
        .Booking input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 0;
            font-size: 15px;
        }

        @media (max-width: 900px) {
            .Booking {
                max-width: 98vw;
                padding: 18px 6px;
            }

            .form-grid {
                gap: 10px;
            }
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .Booking {
                padding: 10px 2vw;
            }

            .form-grid {
                grid-template-columns: 1fr;
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
            
            th, td {
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

            th, td {
                padding: 8px;
                font-size: 12px;
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
        <h1>Booking <span style="color: #f7ad19;">Ruangan</span></h1>
        <div class="button-container">
            <a href="ubahjadwal.php" class="button">Pilih Waktu</a>
            <a href="hapusjadwal.php" class="button">Pilih Tanggal</a>
        </div>
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
            <tr>
                <td>2024-03-20</td>
                <td>A10.01.01</td>
                <td>Dikonfirmasi</td>
                <td>Pemrograman Berbasis Platform</td>
                <td>TI23C</td>
                <td>Dr. Ricky</td>
            </tr>
            <tr>
                <td>2024-03-21</td>
                <td>A10.01.02</td>
                <td>Menunggu</td>
                <td>Basis Data</td>
                <td>TI23B</td>
                <td>Dr. Budi</td>
            </tr>
			            <tr>
                <td>2024-03-21</td>
                <td>A10.01.02</td>
                <td>Menunggu</td>
                <td>Basis Data</td>
                <td>TI23B</td>
                <td>Dr. Budi</td>
            </tr>
			            <tr>
                <td>2024-03-21</td>
                <td>A10.01.02</td>
                <td>Menunggu</td>
                <td>Basis Data</td>
                <td>TI23B</td>
                <td>Dr. Budi</td>
            </tr>
        </table>

    </div>
    <div class="Booking">
        <p
            style="
          text-align: center;
          font-size: 20px;
          font-weight: bold;
          color: #fff;
          margin-bottom: 18px;
          margin-top: 1px;
        ">
            Mau Booking Dimana ?
        </p>
        <div class="form-grid">
            <div class="form-item">
                <label for="tanggal" style="font-weight: bold; color: #fff">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" />
            </div>
            <div class="form-item">
                <label for="jam" style="font-weight: bold; color: #fff">Jam Mulai</label>
                <select name="jam" id="jam">
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
                <label for="sks" style="font-weight: bold; color: #fff">Jumlah SKS</label>
                <select name="sks" id="sks">
                    <option value="" disabled selected>Pilih Jumlah SKS</option>
                    <option value="1">1 SKS</option>
                    <option value="2">2 SKS</option>
                    <option value="3">3 SKS</option>
                    <option value="4">4 SKS</option>
                    <option value="5">5 SKS</option>
                </select>
            </div>
            <div class="form-item">
                <label for="ruangan" style="font-weight: bold; color: #fff">Ruangan</label>
                <select name="ruangan" id="ruangan">
                    <option value="" disabled selected>Pilih Ruangan</option>
                    <option value="ruang1">A10.01.01</option>
                    <option value="ruang2">A10.01.02</option>
                    <option value="ruang3">A10.01.03</option>
                    <option value="ruang4">A10.01.04</option>
                    <option value="ruang5">A10.01.05</option>
                    <option value="ruang6">A10.01.06</option>
                    <option value="ruang7">A10.01.07</option>
                    <option value="ruang8">A10.01.08</option>
                    <option value="ruang9">A10.01.09</option>
                    <option value="ruang10">A10.01.10</option>
                    <option value="ruang11">A10.01.11</option>
                    <option value="ruang12">A10.01.12</option>
                    <option value="ruang13">A10.01.13</option>
                    <option value="ruang14">A10.01.14</option>
                    <option value="ruang15">A10.01.15</option>
                </select>
            </div>
            <div class="form-item">
                <label for="matkul" style="font-weight: bold; color: #fff">Mata Kuliah</label>
                <select name="matkul" id="matkul">
                    <option value="" disabled selected>Pilih Mata Kuliah</option>
                    <?php 
                    if (!empty($matakuliah_list)) {
                        foreach($matakuliah_list as $matkul) {
                            echo '<option value="' . $matkul['id_matkul'] . '">';
                            echo $matkul['nama_matkul'];
                            echo '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>Tidak ada mata kuliah yang tersedia</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-item">
                <label for="kelas" style="font-weight: bold; color: #fff">Kelas</label>
                <input type="text" id="kelas" placeholder="Contoh: TI23C" />
            </div>
        </div>
        <script>
            // Set min date ke hari ini
            document.addEventListener("DOMContentLoaded", function() {
                const today = new Date().toISOString().split("T")[0];
                document.getElementById("tanggal").setAttribute("min", today);
            });
        </script>
        <div style="text-align: center; margin-top: 20px">
            <button
                style="
            padding: 10px 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
          ">
                Booking Sekarang
            </button>
        </div>
    </div>
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 SiBookan. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>