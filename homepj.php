<?php
include "database.php";

session_start();

// Check if user is logged in and is a PJ
if (!isset($_SESSION["is_login"]) || $_SESSION["role"] !== "pj") {
    header("Location: login.php");
    exit();
}

$error_message = '';

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
        header("Location: homepj.php?notif=fail_booking");
        exit();
    } else {
        // Insert booking baru
        $insert_query = "INSERT INTO booking_ruangan (tanggal, jam_mulai, durasi, jumlah_sks, nomor_ruangan, id_matkul, kelas, id_dosen, status_booking) 
                        VALUES ('$tanggal', '$jam_mulai', $durasi, '$jumlah_sks', '$nomor_ruangan', '$id_matkul', '$kelas', '$id_dosen', 'Dipakai')";

        if (mysqli_query($db, $insert_query)) {
            header("Location: homepj.php?notif=success_booking");
            exit();
        } else {
            header("Location: homepj.php?notif=error_booking");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home PJ - SiBookan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @media screen and (max-width: 768px) {
            .container {
                margin: 1rem;
                padding: 1rem;
            }

            .header-content {
                padding: 0 1rem;
                flex-direction: column;
                gap: 1rem;
            }

            .nav-buttons {
                width: 100%;
                justify-content: center;
            }

            .nav-btn {
                width: 100%;
                text-align: center;
            }

            .filter-form {
                flex-direction: column;
                padding: 1rem;
            }

            .filter-form label,
            .filter-form input[type="date"],
            .filter-form select,
            .filter-form .button {
                width: 100%;
                margin: 0.5rem 0;
            }

            .booking-form {
                padding: 1rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .submit-btn {
                width: 100%;
            }
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 2rem 0;
        }

        table {
            width: 100%;
            min-width: 800px;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        @media screen and (max-width: 768px) {
            body {
                min-height: 100vh;
                height: 100vh;
                overflow-y: auto;
            }

            .info-box {
                padding: 1rem;
                margin: 1rem 0;
            }

            .info-box p {
                font-size: 0.9rem;
            }

            h1 {
                font-size: 1.5rem;
                margin: 1rem 0;
            }

            h2 {
                font-size: 1.2rem;
                margin: 1rem 0;
            }
        }

        header {
            background: white;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
        }

        .nav-btn {
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-btn-logout {
            background: #e53935;
            color: white;
        }

        .nav-btn-logout:hover {
            background: #b71c1c;
        }

        h1 {
            color: #1e3c72;
            margin-bottom: 2rem;
            text-align: center;
        }

        h2 {
            color: #1e3c72;
            margin: 2rem 0 1.5rem;
            font-size: 1.5rem;
        }

        .booking-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin: 3rem 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .submit-btn {
            background: #2a5298;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            background: #1e3c72;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #1e3c72;
            color: white;
            font-weight: 500;
        }

        tr:nth-child(even) {
            background: #f8f9fa;
        }

        .info-box {
            background: #e3f2fd;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .info-box p {
            margin: 0.8rem 0;
            color: #1e3c72;
            font-size: 1.1rem;
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

        .reset-btn {
            background: #6c757d;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            height: 42px;
            display: inline-flex;
            align-items: center;
        }

        .reset-btn:hover {
            background: #495057;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <a href="homepj.php" class="logo">SiBookan</a>
            <div class="nav-buttons">
                <a href="logout.php" class="nav-btn nav-btn-logout">Logout</a>
            </div>
        </div>
    </header>

    <div class="container">
        <h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION["nama"]); ?></h1>
        
        <div class="info-box">
            <p><strong>Kelas:</strong> <?php echo htmlspecialchars($_SESSION["kelas"]); ?></p>
            <p><strong>Mata Kuliah:</strong> <?php echo htmlspecialchars($_SESSION["matkul"]); ?></p>
            <p><strong>Dosen:</strong> <?php echo htmlspecialchars($_SESSION["dosen"]); ?></p>
        </div>

        <div class="filte-form">
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
        </div>

        <h2>Daftar Booking</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Ruangan</th>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Durasi</th>
                        <th>Kelas</th>
                        <th>Mata Kuliah</th>
                        <th>Dosen</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nomor_ruangan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['tanggal']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['jam_mulai']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['durasi']) . " menit</td>";
                            echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_matkul']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nama_dosen']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status_booking']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align: center; color: #28a745; font-family: 'Poppins', sans-serif; font-weight: bold;'>Semua kelas tersedia pada jam dan tanggal tersebut</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="booking-form">
            <h2>Buat Booking Baru</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="ruangan">Ruangan</label>
                    <select name="ruangan" id="ruangan" required>
                        <option value="A10.01">A10.01</option>
                        <option value="A10.02">A10.02</option>
                        <option value="A10.03">A10.03</option>
                        <option value="A10.04">A10.04</option>
                        <option value="A10.05">A10.05</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" required>
                </div>
                <div class="form-group">
                    <label for="jam">Jam Mulai</label>
                    <select name="jam" id="jam" required>
                        <option value="07:00:00">07:00</option>
                        <option value="07:50:00">07:50</option>
                        <option value="08:40:00">08:40</option>
                        <option value="09:30:00">09:30</option>
                        <option value="10:20:00">10:20</option>
                        <option value="11:10:00">11:10</option>
                        <option value="12:00:00">12:00</option>
                        <option value="12:50:00">12:50</option>
                        <option value="13:40:00">13:40</option>
                        <option value="14:30:00">14:30</option>
                        <option value="15:20:00">15:20</option>
                        <option value="16:10:00">16:10</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="sks">Jumlah SKS</label>
                    <select name="sks" id="sks" required>
                        <option value="1">1 SKS (50 menit)</option>
                        <option value="2">2 SKS (100 menit)</option>
                        <option value="3">3 SKS (150 menit)</option>
                        <option value="4">4 SKS (200 menit)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="matkul">Mata Kuliah</label>
                    <select name="matkul" id="matkul" required>
                        <option value="<?php echo $_SESSION['matkul']; ?>"><?php echo $_SESSION['matkul']; ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="kelas">Kelas</label>
                    <select name="kelas" id="kelas" required>
                        <option value="<?php echo $_SESSION['kelas']; ?>"><?php echo $_SESSION['kelas']; ?></option>
                    </select>
                </div>
                <button type="submit" name="submit_booking" class="submit-btn">Buat Booking</button>
            </form>
        </div>
    </div>
</body>
</html> 