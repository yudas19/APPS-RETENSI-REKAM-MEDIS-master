jalankan di pc windows server lokal

install laragon
jalankan semua layanan laragon
pastikan Apache / Nginx & mysql running ( lampu hijau)
copy folde APP RETENSI RM ke folder C:/laragon/www/APP RETENSI RM

setup database
bula laragon -> klik database -> phpmyadmin
login : user : root password : "", (kosongkan)
buat db baru dp_retensi_rm.sql
import file sql nya jalankan di db
edit database.php ( pasword kosong)
test http://localhost/APP RETENSI RM
cari dan catat ip komputer windows (ipconfig)
ping dari komputer lain
matikan firewall allow nya 