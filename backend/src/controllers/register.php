<?php
// Include file koneksi database
require_once('../config/db.php');

// Ambil data dari request (POST)
$nama = $_POST['namaCustomer'];
$username = $_POST['usernameCustomer'];
$password = $_POST['passwordCustomer'];
$email = $_POST['emailCustomer'];
$nomorHp = $_POST['nomorHpCustomer'];
$alamat = $_POST['alamatCustomer'];

// Hash password untuk keamanan
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// SQL query untuk menyimpan data ke tabel customer
$sql = "INSERT INTO customer (namaCustomer, usernameCustomer, passwordCustomer, emailCustomer, nomorHpCustomer, alamatCustomer) 
        VALUES (?, ?, ?, ?, ?, ?)";

// Persiapan statement
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $nama, $username, $hashedPassword, $email, $nomorHp, $alamat);

// Eksekusi query
if ($stmt->execute()) {
    $response = [
        'status' => 'success',
        'message' => 'Registrasi berhasil'
    ];
} else {
    $response = [
        'status' => 'error',
        'message' => 'Registrasi gagal: ' . $stmt->error
    ];
}

// Tutup koneksi
$stmt->close();
$conn->close();

// Return response dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
