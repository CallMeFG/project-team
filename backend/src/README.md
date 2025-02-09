#Koneksi ke DATABASE
berada di file db.php
dengan penampakan code php seperti berikut
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

berikut adalah penampakan nya code untuk regis:
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

berikut adalah penampakan code untuk login
![image](https://github.com/user-attachments/assets/a19a901e-527c-470a-82f7-6f725ff55832)
