<?php
include "database.php";

session_start();

// Cek apakah user sudah login
if (isset($_POST['login'])) {
    $password = $_POST['password'];
    $username = $_POST['username'];

    $login = "SELECT * FROM dosen WHERE username='$username' AND password = '$password'";
    $masuk = $db->query($login);

    if ($masuk->num_rows > 0) {
        $tabel = $masuk->fetch_assoc();
        $_SESSION["username"] = $tabel["username"];
        $_SESSION["nama"] = $tabel["nama_dosen"];
        $_SESSION["is_login"] = true;
        header("Location: daftarpj.php");
        exit();
    } else {
        $error_message = "Username atau password salah!";
    }
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_pj'])) {
        $nama = mysqli_real_escape_string($db, $_POST['nama']);
        $nim = mysqli_real_escape_string($db, $_POST['nim']);
        $password = mysqli_real_escape_string($db, $_POST['password']);
        $kelas = mysqli_real_escape_string($db, $_POST['kelas']);
        $matkul = mysqli_real_escape_string($db, $_POST['matakuliah']);
        $dosen = mysqli_real_escape_string($db, $_SESSION["nama"]);
        
        $sql = "INSERT INTO penanggung_jawab (nama, nim, password, kelas, matkul, dosen) VALUES ('$nama', '$nim', '$password', '$kelas', '$matkul', '$dosen')";
        
        if (mysqli_query($db, $sql)) {
            echo "<script>alert('PJ berhasil ditambahkan!');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($db) . "');</script>";
        }
    }
    
    if (isset($_POST['delete_pj'])) {
        $nim = mysqli_real_escape_string($db, $_POST['deleteNim']);
        $dosen = mysqli_real_escape_string($db, $_SESSION["nama"]);
        
        $sql = "DELETE FROM penanggung_jawab WHERE nim = '$nim' AND dosen = '$dosen'";
        
        if (mysqli_query($db, $sql)) {
            echo "<script>alert('PJ berhasil dihapus!');</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($db) . "');</script>";
        }
    }
}

// Fetch data from penanggung_jawab table for logged in dosen only
$dosen = mysqli_real_escape_string($db, $_SESSION["nama"]);
$sql = "SELECT * FROM penanggung_jawab WHERE dosen = '$dosen'";
$result = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SiBookan UNESA - Sistem Booking Ruangan Gedung A10</title>

    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="shortcut icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
            overflow-x: hidden;
            width: 100%;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3464, #3668c0);
            color: #333;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        .main-content {
            flex: 1 0 auto;
            display: flex;
            flex-direction: column;
            width: 100%;
            padding-bottom: 2rem;
        }

        .container {
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

        .container h1 {
            margin-bottom: 1rem;
            font-size: 2rem;
            font-weight: 600;
            color: #429ebd;
            text-align: center;
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

        footer {
            flex-shrink: 0;
            background: #1e3c72;
            color: white;
            padding: 2rem 0;
            text-align: center;
            width: 100%;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-content p {
            opacity: 0.8;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
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
            width: 20%;
        }

        /* Nama */
        th:nth-child(2),
        td:nth-child(2) {
            width: 15%;
        }

        /* NIM */
        th:nth-child(3),
        td:nth-child(3) {
            width: 15%;
        }

        /* Kelas */
        th:nth-child(4),
        td:nth-child(4) {
            width: 20%;
        }

        /* Matkul */
        th:nth-child(5),
        td:nth-child(5) {
            width: 20%;
        }

        /* Dosen */
        th:nth-child(6),
        td:nth-child(6) {
            width: 10%;
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
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .button:hover {
            background-color: #1e3c72;
            transform: translateY(-2px);
        }

        /* Mobile Navigation */
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

        /* Responsive Styles */
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

            .hamburger {
                display: block;
            }

            .nav-buttons {
                display: none;
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

            .table-wrapper {
                margin: 0 -1rem;
                padding: 0 1rem;
            }

            table {
                min-width: 800px;
                margin: 0;
            }
        }

        @media screen and (max-width: 480px) {
            .logo {
                font-size: 1.2rem;
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

        /* Form Styles */
        #pjForm, #deleteForm {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        #pjForm form, #deleteForm form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #666;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2a5298;
            box-shadow: 0 0 0 2px rgba(42, 82, 152, 0.1);
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

    <!-- Mobile Navigation -->
    <div class="mobile-nav" id="mobileNav">
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

    <div class="container">
        <h1>Daftar <span style="color: #f7ad19;">PJ</span></h1>
        <h2>Dosen: <?php echo $_SESSION["nama"]; ?></h2>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>
                            <div class="header-cell">
                                <span class="material-icons">person</span>
                                Nama
                            </div>
                        </th>
                        <th>
                            <div class="header-cell">
                                <span class="material-icons">badge</span>
                                NIM
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
                                <span class="material-icons">menu_book</span>
                                Mata Kuliah
                            </div>
                        </th>
                        <th>
                            <div class="header-cell">
                                <span class="material-icons">key</span>
                                Password
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nim']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['kelas']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['matkul']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align: center;'>Tidak ada data penanggung jawab</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="button-container">
            <button class="button" type="button" onclick="togglePJForm()">Tambah PJ</button>
            <button class="button" type="button" onclick="toggleDeleteForm()">Hapus PJ</button>
        </div>
    </div>

    <!-- Form Tambah PJ -->
    <div id="pjForm">
        <form method="POST" action="">
            <button type="button" class="close-btn" onclick="togglePJForm()">&times;</button>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="nim">NIM:</label>
                <input type="text" id="nim" name="nim" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="kelas">Kelas:</label>
                <input type="text" id="kelas" name="kelas" required>
            </div>
            <div class="form-group">
                <label for="matakuliah">Mata Kuliah:</label>
                <input type="text" id="matakuliah" name="matakuliah" required>
            </div>
            <button type="submit" name="submit_pj" class="button">Submit</button>
        </form>
    </div>

    <!-- Form Hapus PJ -->
    <div id="deleteForm">
        <form method="POST" action="">
            <button type="button" class="close-btn" onclick="toggleDeleteForm()">&times;</button>
            <div class="form-group">
                <label for="deleteNim">NIM PJ yang akan dihapus:</label>
                <input type="text" id="deleteNim" name="deleteNim" required>
            </div>
            <button type="submit" name="delete_pj" class="button">Hapus</button>
        </form>
    </div>

    <footer>
        <div class="footer-content">
            <p>&copy; 2025 SiBookan. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function togglePJForm() {
            const form = document.getElementById('pjForm');
            const deleteForm = document.getElementById('deleteForm');
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'flex';
                deleteForm.style.display = 'none';
            } else {
                form.style.display = 'none';
            }
        }

        function toggleDeleteForm() {
            const deleteForm = document.getElementById('deleteForm');
            const form = document.getElementById('pjForm');
            if (deleteForm.style.display === 'none' || deleteForm.style.display === '') {
                deleteForm.style.display = 'flex';
                form.style.display = 'none';
            } else {
                deleteForm.style.display = 'none';
            }
        }

        // Hamburger Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
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
    </script>
</body>

</html>