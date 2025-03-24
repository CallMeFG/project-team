<?php
// Include file koneksi database
require_once __DIR__ . '/../config/db.php';

// Ambil data dari request (POST) dengan null coalescing operator
$nama = $_POST['namaCustomer'] ?? '';
$username = $_POST['usernameCustomer'] ?? '';
$password = $_POST['passwordCustomer'] ?? '';
$email = $_POST['emailCustomer'] ?? '';
$nomorHp = $_POST['nomorHpCustomer'] ?? '';
$alamat = $_POST['alamatCustomer'] ?? '';

echo "<br>";
echo "nama:".$nama ."username:". $username ."password:". $password ."email:".$email ."hp:".$nomorHp ."alamat:".$alamat;
echo "<br>";
echo "<br>";
// Validasi data (sebelum disimpan ke database)
// if (empty($nama) || empty($username) || empty($password) || empty($email) || empty($nomorHp) || empty($alamat)) {
//     $response = [
//         'status' => 'error',
//         'message' => 'Data tidak lengkap!'
//     ];
//     header('Content-Type: application/json');
//     echo json_encode($response);
//     echo "<br>";
//     echo "nama:".$nama ."username:". $username ."password:". $password ."email:".$email ."hp:".$nomorHp ."alamat:".$alamat;
//     echo "<br>";
//     exit; // Berhenti eksekusi kode selanjutnya
// }

// Hash password untuk keamanan
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo "<br>";
echo $email;
echo''. $nama .''. $username .''. $password ;
echo "<br>";
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


// error_reporting(E_ALL); // Sebaiknya dinonaktifkan di produksi
// ini_set('display_errors', 1); // Sebaiknya dinonaktifkan di produksi
?>