<?php
include "database.php";

session_start();

$error_message = '';

if (isset($_POST['login'])) {
    $password = $_POST['password'];
    $username = $_POST['username'];

    // Try dosen login first
    $login = "SELECT * FROM dosen WHERE username='$username' AND password = '$password'";
    $masuk = $db->query($login);

    if ($masuk->num_rows > 0) {
        $tabel = $masuk->fetch_assoc();
        $_SESSION["username"] = $tabel["username"];
        $_SESSION["nama"] = $tabel["nama_dosen"];
        $_SESSION["is_login"] = true;
        $_SESSION["role"] = "dosen";
        header("Location: home.php");
        exit();
    } else {
        // Try penanggung jawab login
        $login_pj = "SELECT * FROM penanggung_jawab WHERE nim='$username' AND password = '$password'";
        $masuk_pj = $db->query($login_pj);

        if ($masuk_pj->num_rows > 0) {
            $tabel_pj = $masuk_pj->fetch_assoc();
            $_SESSION["username"] = $tabel_pj["nim"];
            $_SESSION["nama"] = $tabel_pj["nama"];
            $_SESSION["is_login"] = true;
            $_SESSION["role"] = "pj";
            $_SESSION["kelas"] = $tabel_pj["kelas"];
            $_SESSION["matkul"] = $tabel_pj["matkul"];
            $_SESSION["dosen"] = $tabel_pj["dosen"];
            header("Location: homepj.php");
            exit();
        } else {
            $error_message = "Username atau password salah!";
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
    <meta name="description" content="SiBookan UNESA adalah sistem booking ruangan online untuk Gedung A10 UNESA Ketintang. SiBookan membantu mahasiswa dan dosen melakukan reservasi ruangan secara cepat, efisien, dan tanpa ribet. Fitur pencarian real-time, booking 24/7, dan proses cepat." />
    <meta name="keywords" content="sibookan, sibookan unesa, gedung a10 unesa, booking ruangan unesa, pemesanan ruangan unesa, sistem booking unesa" />
    <meta name="author" content="SiBookan UNESA" />
    <meta name="robots" content="index, follow" />
    <meta name="language" content="Indonesian" />
    <meta name="revisit-after" content="7 days" />
    <meta name="generator" content="SiBookan UNESA" />
    
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="canonical" href="https://sibookan.my.id" />
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
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a3464, #3668c0);
            padding: 1rem;
        }

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 1rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: #1e3c72;
            font-size: clamp(1.5rem, 4vw, 1.8rem);
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #666;
            font-size: clamp(0.8rem, 3vw, 0.9rem);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #333;
            font-weight: 500;
            font-size: clamp(0.9rem, 3vw, 1rem);
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: clamp(0.9rem, 3vw, 1rem);
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1e3c72;
        }

        .login-btn {
            width: 100%;
            padding: 0.8rem;
            background: #2a5298;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: clamp(0.9rem, 3vw, 1rem);
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-btn:hover {
            background: #1e3c72;
        }

        .forgot-password {
            text-align: center;
            margin-top: 1rem;
        }

        .forgot-password p {
            font-size: clamp(0.8rem, 3vw, 0.9rem);
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .forgot-password a {
            color: #1e3c72;
            text-decoration: none;
            font-size: clamp(0.8rem, 3vw, 0.9rem);
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .error-message {
            background-color: #fee2e2;
            border: 1px solid #ef4444;
            color: #dc2626;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            animation: shake 0.5s ease-in-out;
            font-size: clamp(0.8rem, 3vw, 0.9rem);
        }

        .error-message::before {
            content: "⚠️";
            font-size: 1.2rem;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .form-group.error input {
            border-color: #dc2626;
        }

        .back-home {
            text-align: center;
            margin-top: 1rem;
        }

        .back-home a {
            color: #1e3c72;
            text-decoration: none;
            font-size: clamp(0.8rem, 3vw, 0.9rem);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-home a:hover {
            text-decoration: underline;
        }

        /* Media Queries for better mobile responsiveness */
        @media screen and (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                margin: 0.5rem;
            }

            .form-group input {
                padding: 0.7rem;
            }

            .login-btn {
                padding: 0.7rem;
            }
        }

        /* Prevent zoom on input focus for iOS */
        @media screen and (max-width: 480px) {
            input[type="text"],
            input[type="password"] {
                font-size: 16px !important;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>SiBookan</h1>
            <p>Sistem Booking Ruangan untuk Gedung A10</p>
        </div>
        <?php if (!empty($error_message)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group <?php echo !empty($error_message) ? 'error' : ''; ?>">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Masukkan username"
                    required
                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
            </div>
            <div class="form-group <?php echo !empty($error_message) ? 'error' : ''; ?>">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Masukkan password"
                    required />
            </div>
            <button type="submit" name="login" class="login-btn">Sign In</button>
            <div class="forgot-password">
                <p>Belum punya akun?</p>
                <a target="_blank" href="https://api.whatsapp.com/send/?phone=%2B6281946728927&text=Min%2C+info+buat+akun+sibookan&type=phone_number&app_absent=0">Hubungi Pengelola</a>
            </div>
            <div class="back-home">
                <a href="index.php">← Kembali ke Beranda</a>
            </div>
        </form>
    </div>
</body>

</html> 