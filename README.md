## E.L.V FILEMANAGER v13.0 
`cyberpunk metrocity night edition`

<img src="https://raw.githubusercontent.com/NONAME-ELV/Filemanager/refs/heads/main/ELV.png" width="500">


### Project Profile
* **Project Name**: E.L.V FILEMANAGER
* **Version**: 13.0
* **Author**: HxN
* **Theme**: Cyberpunk Metrocity Night
* **Telegram**: @HxNoname

---

### Description
- *Default Password* :`MrHxN0N4M3@ELV`
- **E.L.V Filemanager v13.0** adalah *next-level web-based file management system* yang dirancang khusus untuk operasional di lingkungan *stealth*. Dengan antarmuka bertema *Cyberpunk Metrocity Night*, tool ini tidak hanya sekadar mengelola file, tetapi dirancang sebagai *cyberdeck* untuk efisiensi tinggi, navigasi file yang presisi, dan *post-exploitation* yang elegan.

### Core Features
- **Stealth API Mode**: Mendukung eksekusi perintah via HTTP Header (`X-ELV-RUN`) untuk bypass deteksi *payload* standar.
- **Auto-Register Stream Wrapper**: Implementasi `elvmem://` untuk eksekusi file langsung dari memori.
- **Cyber-Terminal Interface**: Visualisasi *background terminal* berbasis canvas dengan skema warna neon yang dinamis (Cyan, Purple, Magenta, Green, etc.).
- **Tactical Mass Deployment**: Fitur *mass inject* untuk menyebarkan *payload* ke seluruh sektor/folder dalam satu eksekusi.
- **WP Admin Bypass**: Akses langsung ke panel administrator WordPress `untuk penggunaan pergi ke public_html wwordpressnya buka ELV Engine kemudian cari WP ADMIN tekan oke 2x`  anda akan redirect ke halaman admin dashboard (*berlaku utk writeable atau no writeable public_html*)
- **Secure Authentication**: Menggunakan `password_verify` dengan *auth hash* terintegrasi.
- **Reverse Connection**: Modul bawaan untuk *dispatching reverse shell* langsung ke target port.

### Requirements
- **PHP Version**: 7.x - 8.x
- **Environment**: Linux-based server (Debian/Ubuntu recommended)
- **Permissions**: *Writable* directory access.

### Security Warning
Tool ini dirancang untuk tujuan **Authorized Cyber Security Research** dan **Penetration Testing**. Penggunaan tool ini pada sistem tanpa izin adalah ilegal. Pembuat (HxN) tidak bertanggung jawab atas penyalahgunaan oleh pihak ketiga.

---

### Command & Usage
- **Auth**: Gunakan *Access Key* yang telah dikonfigurasi dalam `fm.php`.
- **System Info**: Lihat `Kernel`, `Server IP`, dan `PHP Version` langsung dari dasbor utama.
- **Tactical Actions**:
  - `Copy/Paste`: Gunakan fungsi memori untuk memindahkan *payload* antar direktori.
  - `Unzip`: Ekstraksi paket *file* langsung di server untuk *deployment* cepat.
  - `Mass Deploy`: Gunakan fitur ini untuk melakukan *injeksi* masal pada *server directory* yang luas.
  

*"NEON MODE: CYBERPUNK | GRID: OVERDRIVE | COLOR_SCHEME: NIGHT"*
**E.L.V Engine v1.0**
---

```bash
git clone https://github.com/NONAME-ELV/Filemanager.git
