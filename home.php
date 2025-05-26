<?php
session_start();
include "database.php";
if (!isset($_SESSION["is_login"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>Sudah berhasil login</h1>
    <h2>Selamat datang <?php echo $_SESSION["nama"]; ?></h2>
</body>

</html>