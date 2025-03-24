<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

//create/post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? ''; // Ambil data dari form body

    if (!empty($nama)) {
        $query = "INSERT INTO kategori (nama) VALUES ('$nama')";
        if (mysqli_query($conn, $query)) {
            echo json_encode(["status" => "success", "message" => "Kategori berhasil ditambahkan"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menambahkan kategori"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Nama kategori tidak boleh kosong"]);
    }
}
//read/get
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM kategori";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo json_encode([
            "status" => "error",
            "message" => "Gagal mengambil data kategori"
        ]);
    } else {
        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }

        echo json_encode([
            "status" => "success",
            "data" => $categories
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Metode request tidak valid"
    ]);
}

//update/put
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents("php://input"), $data);
    $idkategori = $data['idkategori'] ?? '';
    $nama = $data['nama'] ?? '';

    if (!empty($idkategori) && !empty($nama)) {
        $query = "UPDATE kategori SET nama = '$nama' WHERE idkategori = '$idkategori'";
        if (mysqli_query($conn, $query)) {
            echo json_encode(["status" => "success", "message" => "Kategori berhasil diperbarui"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui kategori"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ID Kategori dan Nama tidak boleh kosong"]);
    }
}
//delete
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    parse_str(file_get_contents("php://input"), $data);
    $idkategori = $data['idkategori'] ?? '';

    if (!empty($idkategori)) {
        // Periksa apakah kategori memiliki produk terkait
        $checkProduk = "SELECT COUNT(*) AS jumlah FROM produk WHERE idkategori = '$idkategori'";
        $result = mysqli_query($conn, $checkProduk);
        $row = mysqli_fetch_assoc($result);

        if ($row['jumlah'] > 0) {
            echo json_encode(["status" => "error", "message" => "Kategori tidak dapat dihapus karena masih digunakan oleh produk"]);
        } else {
            $query = "DELETE FROM kategori WHERE idkategori = '$idkategori'";
            if (mysqli_query($conn, $query)) {
                echo json_encode(["status" => "success", "message" => "Kategori berhasil dihapus"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal menghapus kategori"]);
            }
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ID Kategori tidak boleh kosong"]);
    }
}
