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

// Query untuk mengambil data booking dosen yang sedang login
$username = $_SESSION["username"];
$query = "SELECT br.*, mk.nama_matkul 
          FROM booking_ruangan br 
          JOIN dosen d ON br.id_dosen = d.id_dosen 
          JOIN mata_kuliah mk ON br.id_matkul = mk.id_matkul 
          WHERE d.username = '$username'
          ORDER BY br.tanggal DESC, br.jam_mulai ASC";
$result = mysqli_query($db, $query);

// --- BACKEND: Ubah Jadwal ---
if (isset($_POST['submit_ubah'])) {
    $id_booking = mysqli_real_escape_string($db, $_POST['ubah_booking_id']);
    $tanggal_baru = mysqli_real_escape_string($db, $_POST['ubah_tanggal']);
    $jam_baru = mysqli_real_escape_string($db, $_POST['ubah_jam']);
    $sks_baru = mysqli_real_escape_string($db, $_POST['ubah_sks']);
    $kelas_baru = mysqli_real_escape_string($db, $_POST['ubah_kelas']);
    $durasi_baru = $sks_baru * 50;

    // Ambil nomor ruangan dari booking yang dipilih
    $q = mysqli_query($db, "SELECT nomor_ruangan FROM booking_ruangan WHERE id_booking='$id_booking'");
    $row = mysqli_fetch_assoc($q);
    $nomor_ruangan = $row['nomor_ruangan'];

    // Cek bentrok booking
    $check_query = "SELECT * FROM booking_ruangan 
                   WHERE nomor_ruangan = '$nomor_ruangan' 
                   AND tanggal = '$tanggal_baru' 
                   AND id_booking != '$id_booking' 
                   AND (
                       (jam_mulai <= '$jam_baru' AND DATE_ADD(jam_mulai, INTERVAL durasi MINUTE) > '$jam_baru')
                       OR 
                       (jam_mulai < DATE_ADD('$jam_baru', INTERVAL $durasi_baru MINUTE) AND jam_mulai >= '$jam_baru')
                   )";
    $check_result = mysqli_query($db, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        header("Location: ruanganku.php?notif=fail_ubah");
        exit();
    } else {
        $update = "UPDATE booking_ruangan SET tanggal='$tanggal_baru', jam_mulai='$jam_baru', jumlah_sks='$sks_baru', durasi='$durasi_baru', kelas='$kelas_baru' WHERE id_booking='$id_booking'";
        if (mysqli_query($db, $update)) {
            header("Location: ruanganku.php?notif=success_ubah");
            exit();
        } else {
            header("Location: ruanganku.php?notif=error_ubah");
            exit();
        }
    }
}
// --- BACKEND: Hapus Jadwal ---
if (isset($_POST['submit_hapus'])) {
    $id_booking = mysqli_real_escape_string($db, $_POST['hapus_booking_id']);
    $delete = "DELETE FROM booking_ruangan WHERE id_booking='$id_booking'";
    if (mysqli_query($db, $delete)) {
        header("Location: ruanganku.php?notif=success_hapus");
        exit();
    } else {
        header("Location: ruanganku.php?notif=error_hapus");
        exit();
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

        /* Table Styles */
        .table-container {
            margin-top: 8rem;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            margin-left: 2rem;
            margin-right: 2rem;
            max-width: 1500px;
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

        .material-icons {
            font-size: 25px;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
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
            width: 20%;
        }

        /* Waktu */
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
            width: 10%;
        }

        /* Status */

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

        .status-tersedia {
            color: #28a745;
            font-weight: bold;
        }

        .status-dipakai {
            color: #dc3545;
            font-weight: bold;
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
                <a href="logout.php" class="nav-btn-logout">
                    <span class="material-icons">logout</span>
                    Logout
                </a>
            </div>
        </div>
    </header>

    <div class="table-container">
        <h1>Daftar <span style="color: #f7ad19;">Ruanganku</span></h1>
        <table>
            <thead>
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
                    <th>
                        <div class="header-cell">
                            <span class="material-icons">info</span>
                            Status
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $status_class = $row['status_booking'] === 'Tersedia' ? 'status-tersedia' : 'status-dipakai';
                        echo "<tr>";
                        echo "<td>" . date('Y-m-d', strtotime($row['tanggal'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nomor_ruangan']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['jam_mulai']) . " (" . $row['jumlah_sks'] . " SKS)</td>";
                        echo "<td>" . htmlspecialchars($row['nama_matkul']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                        echo "<td class='$status_class'>" . htmlspecialchars($row['status_booking']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align: center;'>Tidak ada data booking</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="button-container">
            <button class="button" type="button" onclick="toggleUbahForm()">Ubah Jadwal</button>
            <button class="button" type="button" onclick="toggleHapusForm()">Hapus Jadwal</button>
        </div>
    </div>
    <!-- Pop-up Ubah Jadwal -->
    <div id="ubahForm" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(30,60,114,0.18); z-index:2000; align-items:center; justify-content:center;">
        <form method="POST" action="" style="background:#fff; border-radius:12px; box-shadow:0 4px 24px rgba(30,60,114,0.13); padding:32px 28px; min-width:320px; max-width:95vw; position:relative; display:flex; flex-direction:column; gap:18px;">
            <button type="button" class="close-btn" onclick="toggleUbahForm()" style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:22px; cursor:pointer; color:#666;">&times;</button>
            <h3 style="margin-bottom:8px; color:#1e3c72; text-align:center;">Ubah Jadwal Booking</h3>
            <label for="ubah_booking_id">Pilih Booking:</label>
            <select name="ubah_booking_id" id="ubah_booking_id" required style="padding:8px; border-radius:5px; border:1px solid #bcd0ee;">
                <option value="" disabled selected>Pilih Booking</option>
                <?php
                $result2 = mysqli_query($db, $query); // ulangi query untuk dropdown
                if ($result2 && mysqli_num_rows($result2) > 0) {
                    while ($row2 = mysqli_fetch_assoc($result2)) {
                        $label = date('Y-m-d', strtotime($row2['tanggal'])) . ' | ' . $row2['nomor_ruangan'] . ' | ' . $row2['jam_mulai'] . ' | ' . $row2['nama_matkul'] . ' | ' . $row2['kelas'];
                        echo '<option value="' . $row2['id_booking'] . '">' . htmlspecialchars($label) . '</option>';
                    }
                }
                ?>
            </select>
            <label for="ubah_tanggal">Tanggal Baru:</label>
            <input type="date" name="ubah_tanggal" id="ubah_tanggal" required style="padding:8px; border-radius:5px; border:1px solid #bcd0ee;">
            <label for="ubah_jam">Jam Mulai Baru:</label>
            <select name="ubah_jam" id="ubah_jam" required style="padding:8px; border-radius:5px; border:1px solid #bcd0ee;">
                <option value="" disabled selected>Pilih Jam</option>
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
            <label for="ubah_sks">Jumlah SKS Baru:</label>
            <select name="ubah_sks" id="ubah_sks" required style="padding:8px; border-radius:5px; border:1px solid #bcd0ee;">
                <option value="" disabled selected>Pilih SKS</option>
                <option value="1">1 SKS</option>
                <option value="2">2 SKS</option>
                <option value="3">3 SKS</option>
                <option value="4">4 SKS</option>
                <option value="5">5 SKS</option>
            </select>
            <label for="ubah_kelas">Kelas Baru:</label>
            <input type="text" name="ubah_kelas" id="ubah_kelas" placeholder="Contoh: TI23C" required style="padding:8px; border-radius:5px; border:1px solid #bcd0ee;">
            <button type="submit" name="submit_ubah" class="button" style="margin-top:10px;">Simpan Perubahan</button>
        </form>
    </div>
    <!-- Pop-up Hapus Jadwal -->
    <div id="hapusForm" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(30,60,114,0.18); z-index:2000; align-items:center; justify-content:center;">
        <form method="POST" action="" style="background:#fff; border-radius:12px; box-shadow:0 4px 24px rgba(30,60,114,0.13); padding:32px 28px; min-width:320px; max-width:95vw; position:relative; display:flex; flex-direction:column; gap:18px;">
            <button type="button" class="close-btn" onclick="toggleHapusForm()" style="position:absolute; top:10px; right:10px; background:none; border:none; font-size:22px; cursor:pointer; color:#666;">&times;</button>
            <h3 style="margin-bottom:8px; color:#1e3c72; text-align:center;">Hapus Jadwal Booking</h3>
            <label for="hapus_booking_id">Pilih Booking yang akan dihapus:</label>
            <select name="hapus_booking_id" id="hapus_booking_id" required style="padding:8px; border-radius:5px; border:1px solid #bcd0ee;">
                <option value="" disabled selected>Pilih Booking</option>
                <?php
                $result3 = mysqli_query($db, $query); // ulangi query untuk dropdown
                if ($result3 && mysqli_num_rows($result3) > 0) {
                    while ($row3 = mysqli_fetch_assoc($result3)) {
                        $label = date('Y-m-d', strtotime($row3['tanggal'])) . ' | ' . $row3['nomor_ruangan'] . ' | ' . $row3['jam_mulai'] . ' | ' . $row3['nama_matkul'] . ' | ' . $row3['kelas'];
                        echo '<option value="' . $row3['id_booking'] . '">' . htmlspecialchars($label) . '</option>';
                    }
                }
                ?>
            </select>
            <button type="submit" name="submit_hapus" class="button" style="margin-top:10px; background:#e53935;">Hapus Jadwal</button>
        </form>
    </div>
    <!-- Notifikasi Pop-up -->
    <div id="notif-popup" class="notif-popup" style="display:none;"></div>
    <script>
        function toggleUbahForm() {
            const form = document.getElementById('ubahForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'flex' : 'none';
        }

        function toggleHapusForm() {
            const form = document.getElementById('hapusForm');
            form.style.display = (form.style.display === 'none' || form.style.display === '') ? 'flex' : 'none';
        }
        // Notifikasi pop-up
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const notif = urlParams.get('notif');
            if (notif) {
                const notifPopup = document.getElementById('notif-popup');
                let message = '';
                let icon = '';
                let notifClass = '';
                if (notif === 'success_ubah') {
                    message = 'Jadwal berhasil diubah!';
                    icon = '<span class="material-icons" style="color:#4caf50;">check_circle</span>';
                    notifClass = 'success';
                } else if (notif === 'fail_ubah') {
                    message = 'Ruangan sudah dibooking pada waktu tersebut!';
                    icon = '<span class="material-icons" style="color:#e53935;">error</span>';
                    notifClass = 'fail';
                } else if (notif === 'error_ubah') {
                    message = 'Terjadi kesalahan saat mengubah jadwal.';
                    icon = '<span class="material-icons" style="color:#fbc02d;">warning</span>';
                    notifClass = 'error';
                } else if (notif === 'success_hapus') {
                    message = 'Jadwal berhasil dihapus!';
                    icon = '<span class="material-icons" style="color:#4caf50;">check_circle</span>';
                    notifClass = 'success';
                } else if (notif === 'error_hapus') {
                    message = 'Terjadi kesalahan saat menghapus jadwal.';
                    icon = '<span class="material-icons" style="color:#fbc02d;">warning</span>';
                    notifClass = 'error';
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
                            const url = new URL(window.location);
                            url.searchParams.delete('notif');
                            window.history.replaceState({}, document.title, url.pathname);
                        }
                    }, 500);
                }, 3000);
            }
        });
    </script>
    <footer>
        <div class="footer-content">
            <p>&copy; 2025 SiBookan. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>