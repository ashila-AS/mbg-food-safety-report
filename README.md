# Pelaporan dan Pemantauan Keamanan Makanan MBG

Mini Project Proyek 3, Kasus A, D3 Teknik Informatika.

## 1. Identitas

|            |                                                     |
|------------|-----------------------------------------------------|
| Nama       | Ashila Aulia Salwa                                  |
| NIM        | 251511035                                           |
| Kelas      | 2B                                                  |
| Judul      | MBG Food Safety Report                              |
| Repository | https://github.com/ashila-AS/mbg-food-safety-report |

## 2. Tentang Aplikasi

Aplikasi web untuk membantu masyarakat melaporkan kejadian gangguan kesehatan setelah mengonsumsi makanan program MBG, lalu memantau perkembangannya. Setiap laporan dikaitkan dengan **sekolah** dan **distribusi makanan** tertentu, sehingga laporan yang berkaitan dengan distribusi yang sama bisa dikenali.

Aplikasi ini hanya mencatat dan meneruskan informasi. Aplikasi **tidak** melakukan diagnosis, menentukan penyebab, atau menetapkan pihak yang bertanggung jawab. Nama status juga sengaja menggambarkan langkah proses, bukan kesimpulan.

Fitur utama:

- Login dan logout dengan tiga role
- Pembuatan laporan dengan validasi dan satu file bukti (JPG, JPEG, PNG, atau PDF, maksimal 2 MB, disimpan privat)
- Dropdown distribusi yang terisi lewat `fetch()` ketika sekolah dipilih
- Peninjauan oleh Pemantau, catatan, pengembalian laporan untuk dilengkapi, dan penandaan untuk diteruskan
- Pencatatan penerusan informasi oleh Koordinator
- Pencarian, filter, dan pagination pada daftar laporan
- Pencatatan perubahan status memakai Eloquent Observer (tabel `riwayat_laporan`)
- Halaman rekap sederhana
- Authorization di sisi server (Policy dan middleware role)

## 3. Role dan Kewenangan

1. **Pelapor**     : Membuat laporan dan mengunggah bukti, melihat dan mengubah laporan miliknya (selama status Dikirim atau Dikembalikan).
2. **Pemantau**    : Melihat semua laporan (tanpa identitas pelapor), melihat laporan terkait pada distribusi yang sama, mulai meninjau, menulis catatan, mengembalikan laporan, menandai laporan untuk diteruskan, melihat rekap.
3. **Koordinator** : Melihat semua laporan (tanpa identitas pelapor), mencatat penerusan informasi kepada pihak terkait, melihat rekap.

Pihak terkait (sekolah, SPPG, fasilitas kesehatan) bukan pengguna aplikasi. Mereka hanya dicatat sebagai tujuan penerusan.

## 4. Status Laporan

|           Status          |                             Artinya                          |
|---------------------------|--------------------------------------------------------------|
| Dikirim                   | Laporan baru dibuat pelapor                                  |
| Ditinjau                  | Pemantau mulai memeriksa                                     |
| Dikembalikan              | Pemantau meminta pelapor melengkapi laporan                  |
| Ditandai untuk diteruskan | Pemantau menilai informasi perlu diteruskan ke pihak terkait |
| Diteruskan                | Koordinator mencatat bahwa informasi sudah diteruskan        |

Alur: `Dikirim → Ditinjau → Ditandai untuk diteruskan → Diteruskan`, dengan cabang `Ditinjau → Dikembalikan → (pelapor melengkapi) → Dikirim`.

## 5. Business Rule

1.  BR-01 : Laporan hanya dapat diubah oleh pelapor pemiliknya saat status Dikirim atau Dikembalikan.
2. BR-02 : Perubahan status hanya mengikuti alur yang diizinkan. Mengembalikan atau menandai laporan mensyaratkan minimal satu catatan pemantau.
3. BR-03 : Penerusan hanya dapat dicatat saat status Ditandai untuk diteruskan atau Diteruskan, pihaknya dari daftar tetap, tidak boleh dua kali ke pihak yang sama, dan status menjadi Diteruskan setelah penerusan pertama.

Logika alur status dan syarat transisinya ada di `app/Services/LaporanWorkflow.php`. Pembatasan akses ada di `app/Policies/LaporanPolicy.php` dan `app/Http/Middleware/RoleMiddleware.php`.

## 6. Kebutuhan Environment

- PHP 8.3 atau lebih baru (dengan ekstensi umum Laravel: mbstring, openssl, pdo_mysql, fileinfo)
- Composer
- MySQL atau MariaDB
- Git
- Node.js tidak diperlukan (tampilan memakai CSS di dalam layout)

Pengembangan dilakukan dengan Laravel 13 dan Laragon (MySQL).

## 7. Instalasi

```
git clone https://github.com/ashila-AS/mbg-food-safety-report.git
cd mbg-food-safety-report
composer install
copy .env.example .env
php artisan key:generate
```

(Di Linux atau macOS, gunakan `cp .env.example .env`.)

## 8. Konfigurasi Database

1. Buat database kosong bernama `mbg_report` (collation `utf8mb4_unicode_ci`), misalnya lewat phpMyAdmin atau HeidiSQL.
2. Buka file `.env`, lalu sesuaikan bagian database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mbg_report
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan MySQL di komputer penguji. File `.env` tidak disertakan di repository.

## 9. Migration dan Seeding

```
php artisan migrate:fresh --seed
```

Seeder membuat 4 sekolah, 9 distribusi, 4 akun demo, dan 28 laporan dalam kelima status (lengkap dengan catatan, penerusan, dan riwayat perubahan status), sehingga aplikasi bisa langsung diuji.

## 10. Menjalankan Aplikasi

```
php artisan serve
```

Buka `http://127.0.0.1:8000`. Pastikan MySQL sudah menyala. Batas unggah PHP (`upload_max_filesize`) minimal 2 MB agar uji upload berjalan sesuai ketentuan.

## 11. Akun Demo

Semua akun memakai password `password`.

|     Role    |   Nama   |         Email        |
|-------------|----------|----------------------|
| Pelapor     | Bu Rina  | pelapor1@mbg.test    |
| Pelapor     | Pak Budi | pelapor2@mbg.test    |
| Pemantau    | Pak Dedi | pemantau@mbg.test    |
| Koordinator | Bu Sari  | koordinator@mbg.test |

Ada dua akun pelapor supaya uji kepemilikan (satu pelapor mencoba membuka laporan pelapor lain) bisa dilakukan. Gunakan jendela Incognito atau profil browser berbeda saat berganti role.

## 12. Skenario Uji Cepat

ID berikut berlaku setelah `php artisan migrate:fresh --seed`.

1. Laporan #1
- Status: Dikirim.
- Pemilik: pelapor2 (Pak Budi).
- Pengujian: Pelapor1 membuka laporan ini melalui URL secara langsung → Ditolak (403).
2. Laporan #2
- Status: Ditinjau, tanpa catatan.
- Pemilik: pelapor1 (Bu Rina).
- Pengujian: Pemantau mencoba mengembalikan atau menandai laporan tanpa catatan → Ditolak.
3. Laporan #3
- Status: Dikembalikan.
- Pemilik: pelapor1 (Bu Rina).
- Pengujian: Pelapor1 mengubah laporan → Diterima, status kembali menjadi Dikirim.
4. Laporan #4
- Status: Ditandai untuk diteruskan.
- Pemilik: pelapor2 (Pak Budi).
- Pengujian: Koordinator mencatat penerusan → Diterima.
5. Laporan #5
- Status: Diteruskan (sudah ke Sekolah dan SPPG).
- Pemilik: pelapor1 (Bu Rina).
- Pengujian:
  - Koordinator meneruskan ke SPPG lagi → Ditolak.
  - Koordinator meneruskan ke Fasilitas Kesehatan → Diterima.
  - Pelapor1 mencoba mengubah laporan → Ditolak.

Fitur lain:

- **Fetch distribusi:** Pelapor, menu Buat laporan, pilih sekolah, lalu lihat request di DevTools, tab Network.
- **Search, filter, pagination:** Pemantau atau Koordinator, menu Daftar laporan (28 laporan, 3 halaman).
- **Observer:** ubah status satu laporan, lalu periksa tabel `riwayat_laporan` di database.
- **Rekap:** Pemantau atau Koordinator, menu Rekap.

## 13. Struktur Singkat

1. Migration : `database/migrations` 
2. Seeder : `database/seeders` 
3. Model dan relasi : `app/Models` 
4. Controller : `app/Http/Controllers` 
5. Validasi form : `app/Http/Requests` 
6. Alur status & business rule : `app/Services/LaporanWorkflow.php` 
7. Authorization : `app/Policies/LaporanPolicy.php`, `app/Http/Middleware/RoleMiddleware.php` 
8. Observer : `app/Observers/LaporanObserver.php` 
9. Tampilan : `resources/views` 
10. Dokumen desain & ERD : `docs` 

## 14. Catatan Data

Semua data pada seeder adalah data dummy. Tidak ada identitas siswa, data kesehatan, foto, atau kejadian nyata.