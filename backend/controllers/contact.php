<?php

require_once __DIR__ . '/../config/db.php';

// Fungsi untuk membersihkan input
function cleanInput($data)
{
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($data)));
}

// Fungsi untuk mengirim respon JSON
function sendResponse($status, $message)
{
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse('error', 'Metode request tidak valid.');
}

$name = cleanInput($_POST['name']);
$email = cleanInput($_POST['email']);
$message = cleanInput($_POST['message']);

// Validasi input (opsional, bisa disesuaikan)
if (empty($name) || empty($email) || empty($message)) {
    sendResponse('error', 'Semua field harus diisi.');
}
// Validasi email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse('error', 'Format email tidak valid.');
}
$sql = "INSERT INTO kontak (name, email, pesan) VALUES ('$name', '$email', '$message')";

if ($conn->query($sql) === TRUE) {
    sendResponse('success', 'Pesan Anda telah terkirim.');
} else {
    sendResponse('error', 'Terjadi kesalahan saat menyimpan pesan: ' . $conn->error);
}

$conn->close();
?>