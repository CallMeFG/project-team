<?php
// Koneksi ke database
$host = "localhost";
$user = "root"; // Ganti jika menggunakan username database yang berbeda
$password = ""; // Ganti jika ada password untuk database
$dbname = "mone_cafe";

$conn = new mysqli($host, $user, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel kategori
$sql = "SELECT * FROM kategori";
$result = $conn->query($sql);

// Periksa apakah ada data
if ($result->num_rows > 0) {
    // Menampilkan data dalam format JSON
    $kategori = [];
    while ($row = $result->fetch_assoc()) {
        $kategori[] = $row;
    }
    echo json_encode($kategori);
} else {
    echo json_encode(["message" => "Tidak ada data pada tabel 'kategori'."]);
}

// Tutup koneksi
$conn->close();
