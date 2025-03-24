<?php
// Konfigurasi database
$host = 'localhost'; // Nama host
$user = 'root';      // Username database
$password = '';      // Password database
$dbname = 'mone_cafe'; // Nama database yang akan dicek

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mendapatkan daftar tabel
$query = "SHOW TABLES";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    echo "<h2>Daftar Tabel dalam Database '$dbname':</h2>";
    echo "<ul>";
    while ($row = $result->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
} else {
    echo "Tidak ada tabel yang ditemukan di database '$dbname'.";
}
// Query untuk mengambil data dari tabel 'produk'
$sql = "SELECT * FROM produk";
$result = $conn->query($sql);

// Cek apakah ada data dalam tabel
if ($result->num_rows > 0) {
    echo "<h2>Data Tabel 'produk':</h2>";
    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<tr>
            <th>Nomor Produk</th>
            <th>Harga Produk</th>
            <th>Nama Produk</th>
            <th>ID Kategori</th>
            <th>Deskripsi</th>
          </tr>";

    // Looping data
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['nomorProduk'] . "</td>
                <td>" . $row['hargaProduk'] . "</td>
                <td>" . $row['namaProduk'] . "</td>
                <td>" . $row['idkategori'] . "</td>
                <td>" . $row['deskripsi'] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<h2>Tidak ada data pada tabel 'produk'.</h2>";
}

// Menutup koneksi
$conn->close();
