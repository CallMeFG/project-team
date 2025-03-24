<?php

require_once __DIR__ . '/../config/db.php';

// Fungsi untuk membuat pesanan
function create_pesanan($data)
{
    global $conn;

    // Validasi data
    if (empty($data['idCustomer']) || empty($data['totalHarga']) || empty($data['metodePembayaran'])) {
        return ["status" => "error", "message" => "Data tidak lengkap"];
    }

    $status = 'pending';
    $query = "INSERT INTO pesanan (idCustomer, totalHarga, metodePembayaran, status, tanggalPesanan) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("idss", $data['idCustomer'], $data['totalHarga'], $data['metodePembayaran'], $status);

    if ($stmt->execute()) {
        return ["status" => "success", "message" => "Pesanan berhasil dibuat", "nomorPesanan" => $stmt->insert_id];
    } else {
        return ["status" => "error", "message" => $stmt->error]; // Mengembalikan pesan error dari database
    }
}

// Fungsi untuk mendapatkan status pesanan
function get_pesanan($nomorPesanan)
{
    global $conn;
    $query = "SELECT status FROM pesanan WHERE nomorPesanan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $nomorPesanan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return ["status" => "success", "data" => $result->fetch_assoc()]; // Mengembalikan data pesanan
    } else {
        return ["status" => "error", "message" => "Pesanan tidak ditemukan"];
    }
}

// Fungsi untuk memperbarui status pesanan
function update_pesanan($nomorPesanan, $data)
{
    global $conn;

    // Validasi data
    if (!isset($data['status']) || !in_array($data['status'], ['pending', 'success', 'failed'])) {
        return ["status" => "error", "message" => "Status pembayaran tidak valid"];
    }

    $query = "UPDATE pesanan SET status = ? WHERE nomorPesanan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $data['status'], $nomorPesanan);

    if ($stmt->execute()) {
        return ["status" => "success", "message" => "Status pembayaran diperbarui"];
    } else {
        return ["status" => "error", "message" => $stmt->error]; // Mengembalikan pesan error dari database
    }
}

// API
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data === null) {
            echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
            exit;
        }
        $result = create_pesanan($data);
        echo json_encode($result); // Mengembalikan hasil dari fungsi create_pesanan
        break;

    case 'GET':
        if (isset($_GET['nomorPesanan'])) {
            $result = get_pesanan($_GET['nomorPesanan']);
            echo json_encode($result); // Mengembalikan hasil dari fungsi get_pesanan
        } else {
            echo json_encode(["status" => "error", "message" => "Nomor pesanan wajib diisi"]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data === null) {
            echo json_encode(["status" => "error", "message" => "Invalid JSON data"]);
            exit;
        }

        if (isset($_GET['nomorPesanan'])) {
            $nomorPesanan = $_GET['nomorPesanan'];
            $result = update_pesanan($nomorPesanan, $data);
            echo json_encode($result); // Mengembalikan hasil dari fungsi update_pesanan
        } else {
            echo json_encode(["status" => "error", "message" => "Nomor pesanan wajib diisi"]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Metode tidak valid"]);
        break;
}
