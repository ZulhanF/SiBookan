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

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3464, #3668c0);
            color: #333;
            display: flex;
            flex-direction: column;
        }

        .container {
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
            background: #1e3c72;
            color: white;
            padding: 2rem 0;
            text-align: center;
            width: 100%;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .footer-content p {
            opacity: 0.8;
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

        table th,
        table td {
            padding: 12px 15px;
            text-align: left;
        }

        table th {
            background-color: #1e3c72;
            color: #ffffff;
            font-weight: 500;
            font-style: bold;
        }

        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tr:hover {
            background-color: #f2f2f2;
            transition: background-color 0.3s ease;
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

        #pjForm {
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

        #pjForm form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            position: relative;
        }

        #deleteForm {
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

        #deleteForm form {
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
    <section>
        <div class="container">

            <h1>Daftar <span style="color: #f7ad19;">PJ</span></h1>
            <h2>Dosen: <?php echo $_SESSION["nama"]; ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIM</th>
                        <th>Password</th>
                        <th>Kelas</th>
                        <th>Mata Kuliah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['nama'] . "</td>";
                            echo "<td>" . $row['nim'] . "</td>";
                            echo "<td>" . $row['password'] . "</td>";
                            echo "<td>" . $row['kelas'] . "</td>";
                            echo "<td>" . $row['matkul'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' style='text-align: center;'>Tidak ada data PJ</td></tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="button-container">
                <button class="button" onclick="toggleForm()">Tambah PJ</button>
                <button class="button" onclick="toggleDeleteForm()">Hapus PJ</button>
            </div>

            <div id="pjForm" style="display: none;">
                <form method="POST" action="">
                    <button type="button" class="close-btn" onclick="toggleForm()">&times;</button>
                    <div style="margin-bottom: 15px;">
                        <label for="nama" style="display: block; margin-bottom: 5px;">Nama:</label>
                        <input type="text" id="nama" name="nama" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="nim" style="display: block; margin-bottom: 5px;">NIM:</label>
                        <input type="text" id="nim" name="nim" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="password" style="display: block; margin-bottom: 5px;">Password:</label>
                        <input type="password" id="password" name="password" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="kelas" style="display: block; margin-bottom: 5px;">Kelas:</label>
                        <input type="text" id="kelas" name="kelas" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label for="matakuliah" style="display: block; margin-bottom: 5px;">Mata Kuliah:</label>
                        <input type="text" id="matakuliah" name="matakuliah" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>

                    <button type="submit" name="submit_pj" class="button">Submit</button>
                </form>
            </div>

            <div id="deleteForm" style="display: none;">
                <form method="POST" action="">
                    <button type="button" class="close-btn" onclick="toggleDeleteForm()">&times;</button>
                    <div style="margin-bottom: 15px;">
                        <label for="deleteNim" style="display: block; margin-bottom: 5px;">NIM PJ yang akan dihapus:</label>
                        <input type="text" id="deleteNim" name="deleteNim" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <button type="submit" name="delete_pj" class="button">Hapus</button>
                </form>
            </div>

            <script>
                function toggleForm() {
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
            </script>

        </div>
    </section>
    <section>
        <footer>
            <div class="footer-content">
                <p>&copy; 2025 SiBookan. All rights reserved.</p>
            </div>
        </footer>
    </section>



</body>

</html>