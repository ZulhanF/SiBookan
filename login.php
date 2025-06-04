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
    <title>Login - SiBookan</title>
    <meta name="description" content="Login ke SiBookan untuk mengakses sistem booking ruangan Gedung A10" />
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
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a3464, #3668c0);
        }

        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header h1 {
            color: #1e3c72;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .login-header p {
            color: #666;
            font-size: 0.9rem;
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

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
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
            font-size: 1rem;
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

        .forgot-password a {
            color: #1e3c72;
            text-decoration: none;
            font-size: 0.9rem;
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
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-home a:hover {
            text-decoration: underline;
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