<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$db   = "canteengo_db";

/** @var mysqli $conn */
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: (" . mysqli_connect_errno() . ") " . mysqli_connect_error());
}
?>

