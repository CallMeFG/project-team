<?php
require_once __DIR__ . '/../config/db.php';

// Fungsi untuk mendapatkan semua produk
function get_products()
{
    global $conn;
    $query = "SELECT * FROM produk";
    $result = $conn->query($query);
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Fungsi untuk mendapatkan produk berdasarkan nomorProduk
function get_product($nomorProduk)
{
    global $conn;
    $query = "SELECT * FROM produk WHERE nomorProduk = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $nomorProduk);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Fungsi untuk menambahkan produk baru
function create_product($data)
{
    global $conn;

    // Validasi input
    if (empty($data['namaProduk'])) {
        return "Nama produk tidak boleh kosong";
    }
    if ($data['hargaProduk'] <= 0) {
        return "Harga produk harus lebih dari nol";
    }

    // Pastikan idKategori ada di tabel kategori
    $check_kategori = "SELECT idKategori FROM kategori WHERE idKategori = ?";
    $stmt_kategori = $conn->prepare($check_kategori);
    $stmt_kategori->bind_param("i", $data['idKategori']);
    $stmt_kategori->execute();
    $result_kategori = $stmt_kategori->get_result();

    if ($result_kategori->num_rows == 0) {
        return "Kategori tidak ditemukan";
    }

    $query = "INSERT INTO produk (namaProduk, hargaProduk, idKategori, deskripsi, stokProduk) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sdisi",
        $data['namaProduk'],
        $data['hargaProduk'],
        $data['idKategori'],
        $data['deskripsi'],
        $data['stokProduk'] // Pastikan data ini dikirim dari frontend
    );
    return $stmt->execute() ? true : $stmt->error;
}

// Fungsi untuk mengupdate produk
function update_product($nomorProduk, $data)
{
    global $conn;

    // Validasi input
    if (empty($data['namaProduk'])) {
        return "Nama produk tidak boleh kosong";
    }
    if ($data['hargaProduk'] <= 0) {
        return "Harga produk harus lebih dari nol";
    }

    // Pastikan idKategori ada di tabel kategori
    $check_kategori = "SELECT idKategori FROM kategori WHERE idKategori = ?";
    $stmt_kategori = $conn->prepare($check_kategori);
    $stmt_kategori->bind_param("i", $data['idKategori']);
    $stmt_kategori->execute();
    $result_kategori = $stmt_kategori->get_result();

    if ($result_kategori->num_rows == 0) {
        return "Kategori tidak ditemukan";
    }

    // Perbaiki query dengan mengganti idProduk menjadi nomorProduk
    $query = "UPDATE produk SET namaProduk = ?, hargaProduk = ?, idKategori = ?, deskripsi = ? WHERE nomorProduk = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sdiss", $data['namaProduk'], $data['hargaProduk'], $data['idKategori'], $data['deskripsi'], $nomorProduk);

    return $stmt->execute() ? true : $stmt->error;
}

// Fungsi untuk menghapus produk
function delete_product($nomorProduk)
{
    global $conn;
    $query = "DELETE FROM produk WHERE nomorProduk = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $nomorProduk);
    return $stmt->execute() ? true : $stmt->error;
}

// CRUD API
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $result = create_product($data);
    echo json_encode(["status" => $result === true ? "success" : "error", "message" => $result === true ? "Produk berhasil ditambahkan" : $result]);
}

if ($method === 'GET') {
    echo json_encode(["status" => "success", "data" => get_products()]);
}

if ($method === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);

    // Mengubah idProduk menjadi nomorProduk
    $nomorProduk = $data['nomorProduk'] ?? '';

    if (!empty($nomorProduk)) {
        $result = update_product($nomorProduk, $data);
        echo json_encode([
            "status" => $result === true ? "success" : "error",
            "message" => $result === true ? "Produk berhasil diperbarui" : $result
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Nomor Produk wajib diisi"
        ]);
    }
}

if ($method === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $nomorProduk = $data['nomorProduk'] ?? '';
    if (!empty($nomorProduk)) {
        $result = delete_product($nomorProduk);
        echo json_encode(["status" => $result === true ? "success" : "error", "message" => $result === true ? "Produk berhasil dihapus" : $result]);
    } else {
        echo json_encode(["status" => "error", "message" => "Nomor Produk tidak boleh kosong"]);
    }
}
