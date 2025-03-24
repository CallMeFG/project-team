<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'mone_cafe'; // Pastikan nama database sesuai dengan yang Anda buat di MySQL Workbench
// Buat koneksi ke database
$conn = new mysqli($host, $user, $password, $db_name);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Koneksi berhasil<br>";
} catch (PDOException $e) {
    echo "Koneksi gagal:" . $e->getMessage();
}
?>  