# Maintenance dan deployment branch ariq

## Temuan produksi (14 September 2026)

- Login admin biasa berhasil. Dashboard, daftar paket, detail paket, berita acara, verifikasi akun, reset password, mutasi, dan profil dapat dibuka.
- Lampiran paket 1 mengembalikan HTTP 404.
- BA paket 1 tampil SELESAI tetapi PDF masih "Belum digenerate". Penyebab yang cocok di kode: status dan tanda tangan disimpan sebelum proses Chromium selesai.
- Pemeriksaan produksi hanya memakai navigasi; tidak mengirim email, membuat paket, mengubah password, atau menandatangani dokumen. Aplikasi mencatat login dan waktu detail paket dibaca admin.

## Perbaikan

- Docker menggunakan Node 24 yang sesuai dengan Puppeteer, memasang Chromium, membuat link storage saat startup, serta menaikkan batas upload PHP untuk seluruh form (maksimum file 20 MB).
- Seeder akun contoh tidak lagi berjalan setiap startup. Seeder memakai Faker (dependensi development), dan pengulangan seeding juga dapat menabrak NIP/email unik.
- `/magic-login` dihapus. Login admin menggunakan NIP dan password melalui `/login`.
- Finalisasi tanda tangan memakai transaksi dan penguncian BA. Kegagalan renderer membatalkan perubahan sehingga tanda tangan bisa diulang.
- QR PDF menggunakan library Endroid/GD yang sudah ada, sehingga tidak bergantung pada ekstensi Imagick. Logo di PDF di-embed sebagai data gambar.
- Paket manual PP memilih PPK penandatangan dan otomatis mempunyai draft BA agar dapat diproses hingga selesai.
- PDF SIRUP digital dibaca langsung sebelum mencoba OCR; file scan tetap menggunakan Ghostscript/Tesseract. File sementara dibersihkan.
- Ikon Font Awesome memakai CSS agar tidak mengganti elemen yang dipakai JavaScript modal dan tombol tema/password.
- Tautan unduh PDF final tersedia bagi admin, PP, dan PPK yang berhak melihat paket.
- Tes memakai SQLite memory, bukan database operasional. Workflow GitHub memeriksa push ke `ariq` dan pull request.

## Sebelum deploy

1. Cadangkan database dan isi `storage/app/public` yang masih tersedia pada deployment lama. File upload tidak disimpan sebagai isi file di database.
2. Pastikan aplikasi mempunyai penyimpanan persisten untuk `/app/storage/app/public`. Volume database tidak otomatis menjadi volume file aplikasi. Jangan memindahkan volume database ke path aplikasi.
3. Cocokkan kuota volume dengan paket Railway Anda. Jika kuota sudah dipakai database, tentukan penyimpanan tambahan yang tersedia sebelum mengandalkan upload lintas deployment. Menambahkan volume adalah pengaturan Railway tersendiri; perubahan Git tidak membuatnya otomatis. Lihat [dokumentasi Railway Volumes](https://docs.railway.com/volumes/reference).
4. Pertahankan `APP_KEY` yang sudah dipakai. Pastikan `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://laravel-lpse-production.up.railway.app`, dan variabel database yang sudah berfungsi tetap terpasang. Untuk cookie HTTPS gunakan `SESSION_SECURE_COOKIE=true`.
5. Pastikan Railway mengambil branch `ariq` dan menggunakan CMD Dockerfile terbaru. Hapus override Start Command lama jika masih menjalankan `db:seed`.
6. Atur mailer dengan kredensial/provider yang valid jika email reset password dan persetujuan akun harus terkirim. Pengiriman email produksi tidak diuji oleh maintenance ini.

## Akun admin

Akun admin yang sudah ada tetap bisa login lewat `/login`. Karena versi lama menyediakan password contoh dan link login terbuka, ganti password melalui terminal **container aplikasi Railway** setelah deploy:

```sh
php artisan admin:reset-password 1234567890123456
```

Perintah meminta password baru secara tersembunyi dan konfirmasi. Ini hanya mereset akun yang sudah berperan admin; tidak membuat akun baru. Jangan menjalankan seeder contoh pada database produksi.

## Memulihkan file yang sudah bermasalah

Untuk lampiran 404, periksa file asli di `storage/app/public/lampiran/...`. Jika file masih ada, `php artisan storage:link --force` memperbaiki link publik. Jika file sudah hilang, pulihkan file asli dari backup dengan path yang sama atau unggah ulang melalui alur aplikasi yang sesuai. Link storage tidak bisa mengembalikan file yang telah hilang.

Untuk BA SELESAI yang **belum pernah** berhasil menghasilkan PDF, cari ID BA (berbeda dari ID paket) di database lalu jalankan:

```sh
php artisan ba:repair-pdf ID_BERITA_ACARA
```

Perintah memerlukan dua tanda tangan yang sudah tersimpan. Perintah menolak mengganti PDF yang telah memiliki path atau hash penerbitan. Jika dokumen pernah diterbitkan lalu filenya hilang, pulihkan PDF asli dari backup agar SHA-256 tetap cocok. Jangan mereset hash untuk memaksa regenerasi dokumen lama.

## Pengecekan ulang

```sh
composer install
npm ci
npm run build
php artisan test
php artisan view:cache
php artisan route:cache
```

Tes PDF menjalankan Chromium nyata. Di Windows, isi `CHROME_PATH` dengan path Chrome yang terpasang. Docker sudah mengisinya `/usr/bin/chromium`. Tes OCR hasil scan membutuhkan Ghostscript dan Tesseract; pengujian otomatis SIRUP memakai PDF digital.

Setelah deploy, cek `/up`, login tiap role, modal tambah BA beserta pergantian mode, upload lampiran, tanda tangan PP lalu PPK, unduh PDF, dan validasi hash. Uji ulang link berkas setelah restart deployment untuk memastikan storage benar-benar persisten.

Docker daemon lokal tidak aktif saat maintenance, sehingga build image Linux dan koneksi MySQL Railway belum diuji ulang secara lokal. Keberhasilan tes SQLite dan Chromium lokal tidak menggantikan pengecekan setelah deploy.

## Upload GitHub

Perubahan disiapkan di branch `ariq`; commit dan push belum dijalankan. Tinjau perubahan, lalu:

```sh
git status
git add .
git commit -m "Fix Railway document storage and PDF workflows"
git push origin ariq
```

Jangan sertakan `.env`, database lokal, atau file upload pada commit. Workflow akan berjalan setelah perubahan di-push.
