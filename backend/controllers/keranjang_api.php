<?php
require_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['idCustomer'])) {
        $idCustomer = $_GET['idCustomer'];

        $sql = "SELECT * FROM keranjang WHERE idCustomer = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idCustomer);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        echo json_encode(["status" => "success", "data" => $items]);
    } else {
        echo json_encode(["status" => "error", "message" => "idCustomer diperlukan"]);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['idCustomer'], $data['nomorProduk'], $data['jumlah'], $data['harga'])) {
        $idCustomer = $data['idCustomer'];
        $nomorProduk = $data['nomorProduk'];
        $jumlah = $data['jumlah'];
        $harga = $data['harga'];

        $sql = "INSERT INTO keranjang (idCustomer, nomorProduk, jumlah, harga) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiid", $idCustomer, $nomorProduk, $jumlah, $harga);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Produk berhasil ditambahkan ke keranjang"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menambahkan produk"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['idKeranjang'], $data['jumlah'])) {
        $idKeranjang = $data['idKeranjang'];
        $jumlah = $data['jumlah'];

        $sql = "UPDATE keranjang SET jumlah = ? WHERE idKeranjang = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $jumlah, $idKeranjang);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Jumlah produk diperbarui"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal memperbarui jumlah produk"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Data tidak lengkap"]);
    }
}
if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['idKeranjang'])) {
        $idKeranjang = $data['idKeranjang'];

        $sql = "DELETE FROM keranjang WHERE idKeranjang = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $idKeranjang);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Produk dihapus dari keranjang"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menghapus produk"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "idKeranjang diperlukan"]);
    }
}
?>