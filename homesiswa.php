<!DOCTYPE html>
<html lang="en">

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
            display: flex;
            flex-direction: column;
            position: relative;
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
            max-width: 1200px;
            width: 90%;
            flex: 1 0 auto;
        }

        h1 {
            margin-bottom: 1rem;
            font-size: 2rem;
            font-weight: 600;
            color: #429ebd;
            text-align: center;
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
            width: 100%;
            margin-top: auto;
            flex-shrink: 0;
            position: relative;
            bottom: 0;
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
            body {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            .container {
                margin: 6rem 1rem 2rem 1rem;
                padding: 1rem;
                flex: 1 0 auto;
            }

            h1 {
                font-size: 1.5rem;
            }

            select {
                width: 100%;
                margin-bottom: 1rem;
            }

            .header-content {
                padding: 0 1rem;
            }

            .logo {
                font-size: 1.2rem;
            }

            .material-icons {
                font-size: 20px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            /* Hide table columns on mobile */
            table th:nth-child(3),
            table th:nth-child(4),
            table td:nth-child(3),
            table td:nth-child(4) {
                display: none;
            }

            /* Adjust table for mobile */
            table {
                font-size: 0.9rem;
            }

            table th,
            table td {
                padding: 10px;
            }

            /* Make table full width on mobile */
            table {
                width: 100%;
                margin: 10px 0;
            }
        }

        .header-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .status-tersedia {
            color: #28a745;
        }

        .status-dipakai {
            color: #dc3545;
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

        <div class="container">
            <h1>Daftar Ruangan <span style="color: #f7ad19;">Gedung A10 Hari Ini</span></h1>
            <?php
            include "database.php";

            // Ambil waktu yang dipilih
            $selected_time = isset($_GET['jam']) ? $_GET['jam'] : date('H:i');

            // Query untuk mengambil status ruangan pada waktu yang dipilih
            $query = "SELECT br.*, mk.nama_matkul, d.nama_dosen 
                      FROM booking_ruangan br 
                      LEFT JOIN mata_kuliah mk ON br.id_matkul = mk.id_matkul 
                      LEFT JOIN dosen d ON br.id_dosen = d.id_dosen 
                      WHERE br.tanggal = CURDATE() 
                      AND (
                          (br.jam_mulai <= '$selected_time' AND DATE_ADD(br.jam_mulai, INTERVAL br.durasi MINUTE) > '$selected_time')
                      )";
            $result = mysqli_query($db, $query);

            // Buat array untuk menyimpan status ruangan
            $ruangan_status = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $ruangan_status[$row['nomor_ruangan']] = array(
                    'status' => 'Dipakai',
                    'kelas' => $row['kelas'],
                    'matkul' => $row['nama_matkul']
                );
            }
            ?>
            <form method="GET" action="" style="margin-bottom: 20px;">
                <select name="jam" id="jam" onchange="this.form.submit()">
                    <option value="" disabled selected>Pilih Waktu</option>
                    <option value="07:00" <?php echo $selected_time == '07:00' ? 'selected' : ''; ?>>07:00</option>
                    <option value="07:50" <?php echo $selected_time == '07:50' ? 'selected' : ''; ?>>07:50</option>
                    <option value="08:40" <?php echo $selected_time == '08:40' ? 'selected' : ''; ?>>08:40</option>
                    <option value="09:30" <?php echo $selected_time == '09:30' ? 'selected' : ''; ?>>09:30</option>
                    <option value="10:20" <?php echo $selected_time == '10:20' ? 'selected' : ''; ?>>10:20</option>
                    <option value="11:10" <?php echo $selected_time == '11:10' ? 'selected' : ''; ?>>11:10</option>
                    <option value="12:00" <?php echo $selected_time == '12:00' ? 'selected' : ''; ?>>12:00</option>
                    <option value="12:50" <?php echo $selected_time == '12:50' ? 'selected' : ''; ?>>12:50</option>
                    <option value="13:40" <?php echo $selected_time == '13:40' ? 'selected' : ''; ?>>13:40</option>
                    <option value="14:30" <?php echo $selected_time == '14:30' ? 'selected' : ''; ?>>14:30</option>
                    <option value="15:20" <?php echo $selected_time == '15:20' ? 'selected' : ''; ?>>15:20</option>
                    <option value="16:10" <?php echo $selected_time == '16:10' ? 'selected' : ''; ?>>16:10</option>
                </select>
            </form>
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
                    <?php
                    // Tampilkan semua ruangan
                    for ($i = 1; $i <= 15; $i++) {
                        $nomor_ruangan = sprintf("A10.01.%02d", $i);
                        $status = isset($ruangan_status[$nomor_ruangan]) ? $ruangan_status[$nomor_ruangan]['status'] : 'Tersedia';
                        $kelas = isset($ruangan_status[$nomor_ruangan]) ? $ruangan_status[$nomor_ruangan]['kelas'] : '-';
                        $matkul = isset($ruangan_status[$nomor_ruangan]) ? $ruangan_status[$nomor_ruangan]['matkul'] : '-';

                        // Tambahkan class untuk styling status
                        $status_class = $status === 'Tersedia' ? 'status-tersedia' : 'status-dipakai';

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($nomor_ruangan) . "</td>";
                        echo "<td class='$status_class'>" . htmlspecialchars($status) . "</td>";
                        echo "<td>" . htmlspecialchars($kelas) . "</td>";
                        echo "<td>" . htmlspecialchars($matkul) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <footer>
            <div class="footer-content">
                <p>&copy; 2025 SiBookan. All rights reserved.</p>
            </div>
        </footer>



</body>

</html>