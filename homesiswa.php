<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SiBookan UNESA - Sistem Booking Ruangan Gedung A10</title>

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

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3464, #3668c0);
            color: #333;
        }

        .container {
            background: white;
            border-radius: 10px;
            max-width: 1500px;
            margin: 0 auto;
            padding: 2rem;
        }

        header {
            background: white;
            padding: 1.5rem 0;
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
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .kelas-btn:hover {
            background: rgb(210, 145, 16);
        }

        .pilih-button {
            background: #f7ad19;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            border-color: transparent;
            text-decoration: none;
        }

        .pilih-button:hover {
            background: rgb(200, 137, 10);
        }

        select {
            background: #f7ad19;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            border: none;
            font-family: "Poppins", sans-serif;
            cursor: pointer;
            width: 200px;
        }

        select:hover {
            background: rgb(200, 137, 10);
        }

        select option {
            background: white;
            color: #333;
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
            margin-bottom: 0rem;
        }

        .hero p {
            font-size: 1.5rem;
            max-width: 1200px;
            margin: 0 auto;
            opacity: 0.9;
            margin-bottom: 1rem;
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

        .feature-card h3 {
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

        table {
            background: #ffffff;
            width: 100%;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }

        table th,
        table td {
            padding: 15px;
            text-align: left;
        }

        table th {
            background-color: #2a5298;
            color: #ffffff;
            font-weight: 500;
        }

        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        table tr:hover {
            background-color: #f2f2f2;
            transition: background-color 0.3s ease;
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

        .header-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-content">
            <a href="index.php" class="logo">
                <span class="material-icons">school</span>
                SiBookan
            </a>
            <div class="nav-buttons">
            </div>
        </div>
    </header>

    <container>
        <section class="hero">
            <h1>Daftar Ruangan Gedung A10 Hari Ini</h1>

        </section>

        <div class="container">
            <select name="jam" id="jam">
                <option value="" disabled selected>Pilih Waktu</option>
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
            <table>
                <thead>
                    <tr>
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
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>A10.01</td>
                        <td>Tersedia</td>
                        <td>Kelas A</td>
                        <td>Statistika</td>
                    </tr>
                    <tr>

                        <td>A10.02</td>
                        <td>Digunakan</td>
                        <td>Kelas B</td>
                        <td>Matematika Diskrit</td>
                    </tr>
                    <tr>
                        <td>A10.03</td>
                        <td>Tersedia</td>
                        <td>Kelas C</td>
                        <td>Sains Komputasi</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <footer style="position: fixed; bottom: 0; width: 100%;">
            <div class="footer-content">
                <p>&copy; 2025 SiBookan. All rights reserved.</p>
            </div>
        </footer>



</body>

</html>