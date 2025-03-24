<?php
// Koneksi ke database
require_once __DIR__ . '/../config/db.php';

if ($conn) {
    // Ambil username dan password dari request (POST) dengan null coalescing operator
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validasi input
    if (empty($username) || empty($password)) {
        echo json_encode([
            "status" => "error",
            "message" => "Harap masukkan username dan password."
        ]);
        exit;
    }

    // Proses login
    $query = "SELECT * FROM customer WHERE usernameCustomer = ?"; // Gunakan usernameCustomer
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['passwordCustomer']; // Ambil passwordCustomer dari database
        echo json_encode("username terdaftar"."<br>");
        // Verifikasi password dengan password_verify()
        if (password_verify($password, $hashedPassword)) {
            echo json_encode([
                "status" => "success",
                "message" => "Login berhasil."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Password salah."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Username tidak ditemukan."
        ]);
    }

    // Tutup koneksi
    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Gagal terhubung ke database."
    ]);
}
?>