#Koneksi ke DATABASE
berada di file db.php
+dengan penampakan code php seperti berikut
![image](https://github.com/user-attachments/assets/f0f6a78f-0fd3-4ab4-ace9-1cd168191069)

#API REGIS
API untuk proses registrasi pengguna baru.

##fungsi API
-membuat akun untuk pengguna baru agar selanjutnya membisakan mereka untuk login
-mengupdate pengguna baru yang tersedia

##Parameter / nilai input
-namaCustomer
-usernameCustomer
-passwordCustomer
-emailCustomer
-nomorHpCustomer
-alamatCustomer

##respon output
-true:
*status: success
*message: registrasi berhasil
-false:
*status : error
*message : registrasi gagal

+berikut adalah penampakan nya code untuk regis:
![image](https://github.com/user-attachments/assets/4e958353-12a0-4bb9-b3a7-4af2bbae19e5)

#API Login
API untuk melakukan proses login dari user/customer

##fungsi API
untuk mempersilahkan masuk ke website bagi user yang telah melakukan regis

##Parameter / nilai input
-usernameCustomer
-passwordCustomer

##respon output
200 OK: Login berhasil: 
{
    "status": "success",
    "message": "Login berhasil.",

400 Bad Request: Username atau password kosong:
{
    "status": "error",
    "message": "Harap masukkan username dan password."
}
404 Not Found: Username tidak ditemukan:
{
    "status": "error",
    "message": "Username tidak ditemukan."
}
401 Unauthorized: Password salah:
{
    "status": "error",
    "message": "Password salah."
}

+berikut adalah penampakan code untuk login
![image](https://github.com/user-attachments/assets/a19a901e-527c-470a-82f7-6f725ff55832)

#APi Produk
API untuk mengelola produk yang terdapat pada database dengan prose CRUD

##CREATE(post) -> membuat suatu data produk baru ke database

###parameter input
-namaProduk
-harga
-idKategori
-deskripsi
###output
/if
####mengisi semua input
*true : $query = "INSERT INTO produk (namaProduk, hargaProduk, idKategori, deskripsi) VALUES ('$namaProduk', '$harga', '$idKategori','$deskripsi');";
/if
#####menambahkan data
*true : ["status" => "success", "message" => "Produk berhasil ditambahkan"]
*false : ["status" => "error", "message" => "Gagal menambahkan produk"]
/endif
*false :["status" => "error", "message" => "Semua data produk wajib diisi"]
/endif
+berikut penampakan code:
![image](https://github.com/user-attachments/assets/9a8bb143-1e07-424e-b57b-a7aaee12f1d9)

##Read(get) -> meminta / menampilkan data yang ada pada database

###permintaan data ke database :
$query = "SELECT * FROM produk";
###mengecek data yang didapatkan ke variabel:
$result = mysqli_query($conn, $query);
###ouput
/if
####jika data yang disimpan kosong
true:["status" => "error", "message" => "Gagal mengambil data produk"]
false:$products = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        echo json_encode(["status" => "success", "data" => $products]);
/endif
+berikut adalah penampakan code nya:
![image](https://github.com/user-attachments/assets/ed119c47-c606-44a2-ba84-c94bd8e67c2c)

##Update(put) -> mengubah / memperbarui data produk

###parameter input
-nomorProdul
-namaProduk
-harga
-idkategori
-deskripsi

####output
/if
#####jika data yang dikasih ada yang kosong
*true : $query = "UPDATE produk SET namaProduk = '$namaProduk', hargaProduk = '$harga', idkategori = '$idKategori', deskripsi = '$deskripsi' WHERE nomorProduk = '$nomorProduk'";
/if
######jika berhasil dikirim ke database
*true : ["status" => "success", "message" => "Produk berhasil diperbarui"]
*false : ["status" => "error", "message" => "Gagal memperbarui produk"]
/endif
*false : ["status" => "error", "message" => "Semua data produk wajib diisi"]
/endif
+berikut adalah penampakan code nya:
![image](https://github.com/user-attachments/assets/86fac87c-b3f8-4586-a40d-4f0f1b22c5e2)

##Delete(delete) -> menghapus suatu data produk

###parameter input
-nomorProduk

/if
####jika nomor produk ditemukan
*true : $checkPesanan = "SELECT COUNT(*) AS jumlah FROM itempesanan WHERE nomorProduk = '$nomorProduk'";
/if
#####jika produk yang ingin dihapus sedang ada yang memesan
*true : ["status" => "error", "message" => "Produk tidak dapat dihapus karena terkait dengan pesanan"]
*false :$query = "DELETE FROM produk WHERE nomorProduk = '$nomorProduk'";
/if
######jika berhasil menghapus data di database
*true : ["status" => "success", "message" => "Produk berhasil dihapus"]
*false : ["status" => "error", "message" => "Gagal menghapus produk"]
/endif
/endif
*false : ["status" => "error", "message" => "Nomor Produk tidak boleh kosong"]
/endif
+berikut adalah penampakan code nya:
![image](https://github.com/user-attachments/assets/e75d2697-cdbf-4aba-8a75-f03c1b482276)

