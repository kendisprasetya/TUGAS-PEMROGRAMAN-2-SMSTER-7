PERANCANGAN SISTEM - CMS WEBSITE PROFIL SEKOLAH LENGKAP

1.  Hirarki Menu (Sidebar / Navbar)
    A. Frontend (Website Utama / Publik)

        Beranda (Home):

            Banner / Slider Utama

            Sambutan Kepala Sekolah

            Berita & Pengumuman Terbaru

            Profil Singkat & Statistik Sekolah (Jumlah Siswa, Guru, Kelas, Prestasi)

        Profil:

            Sejarah Singkat Sekolah

            Visi & Misi

            Struktur Organisasi

            Data Guru & Staf

        Akademik:

            Program Studi / Jurusan

            Kalender Akademik

            Kurikulum Sekolah

        Fasilitas (Baru):

            Daftar & Galeri Ruang Kelas, Lab, Perpustakaan, Lapangan, dll.

        Kesiswaan & Galeri:

            Ekstrakurikuler

            Prestasi Siswa / Sekolah

            Galeri Foto & Video Kegiatan

        PPDB / Pendaftaran (Baru):

            Informasi & Alur Pendaftaran (Syarat, Kuota, Jadwal)

            Formulir Pendaftaran Siswa Baru Online

            Cek Status Kelulusan / Pendaftaran

        Alumni (Baru):

            Direktori / Tracer Study Alumni

        Unduhan / Download Center (Baru):

            Pusat unduh dokumen publik (Brosur, Formulir, Surat Edaran, Kalender PDF)

        Kontak:

            Formulir Pesan / Kotak Masuk

            Peta Lokasi (Google Maps) & Media Sosial Resmi Sekolah

B. Backend (Admin CMS / Dashboard)

    Dashboard:

        Statistik Pengunjung Website

        Ringkasan Data (Jumlah Berita, Pesan Masuk, & Total Pendaftar PPDB)

    Manajemen Konten:

        Berita / Artikel (Tambah, Edit, Hapus, Kategori)

        Pengumuman Sekolah

        Agenda Kegiatan

    Manajemen Akademik & Sekolah (Baru):

        Manajemen Fasilitas Sekolah

        Manajemen Unduhan (Upload File Publik)

    Manajemen PPDB (Baru):

        Data Pendaftar Masuk (Verifikasi berkas, Ubah Status: Pending/Diterima/Ditolak)

        Pengaturan Gelombang & Pengumuman PPDB

    Manajemen Alumni (Baru):

        Data & Database Kelulusan Alumni

    Manajemen Galeri:

        Upload Foto & Video Kegiatan

    Manajemen Pesan:

        Kotak Masuk (Pesan/Pertanyaan dari Pengunjung)

    Pengaturan Website:

        Identitas Sekolah (Nama, Alamat, Kontak, Sosmed)

        Ganti Logo & Favicon

        Ganti Password Admin

2. Entity Relationship Diagram (ER-D) Full

erDiagram
ADMIN ||--o{ BERITA : mengelola
KATEGORI ||--o{ BERITA : memiliki
ADMIN ||--o{ PENGUMUMAN : mempublikasikan
ADMIN ||--o{ GALERI : mengunggah
ADMIN ||--o{ FASILITAS : mengelola
ADMIN ||--o{ UNDUHAN : mengunggah

    ADMIN {
        int id PK
        string username
        string password
        string nama_lengkap
    }

    KATEGORI {
        int id PK
        string nama_kategori
    }

    BERITA {
        int id PK
        string judul
        text konten
        string gambar
        int kategori_id FK
        int admin_id FK
        datetime tanggal_posting
    }

    PENGUMUMAN {
        int id PK
        string judul
        text isi_pengumuman
        int admin_id FK
        date tanggal
    }

    GALERI {
        int id PK
        string judul_foto
        string file_path
        int admin_id FK
    }

    PESAN {
        int id PK
        string nama_pengirim
        string email
        text isi_pesan
        datetime tanggal_kirim
    }

    PPDB_PENDAFTAR {
        int id PK
        string nomor_pendaftaran
        string nama_lengkap
        string nisn
        string jenis_kelamin
        string tempat_tanggal_lahir
        text alamat
        string no_hp
        string pilihan_jurusan
        string status_pendaftaran "Pending/Diterima/Ditolak"
        datetime tanggal_daftar
    }

    FASILITAS {
        int id PK
        string nama_fasilitas
        text deskripsi
        string foto
        int admin_id FK
    }

    ALUMNI {
        int id PK
        string nama_lengkap
        int tahun_lulus
        string jurusan
        string pekerjaan_sekarang
        string kontak
    }

    UNDUHAN {
        int id PK
        string judul_file
        string file_path
        int jumlah_download
        int admin_id FK
        datetime tanggal_upload
    }

3. Penjelasan Relasi & Alur Entitas Utama

   ADMIN: Bertindak sebagai super-user yang memiliki hak akses penuh untuk mengontrol berita, pengumuman, galeri, fasilitas, dan file unduhan.

   BERITA & KATEGORI: Berita dikelompokkan ke dalam kategori tertentu (misal: Kegiatan, Akademik, Prestasi), serta dicatat siapa admin yang mempostingnya.

   PPDB_PENDAFTAR: Berdiri sendiri untuk menampung data calon siswa baru yang masuk secara mandiri melalui form publik, memudahkan panitia sekolah dalam melakukan verifikasi seleksi.

   FASILITAS, ALUMNI, & UNDUHAN: Entitas penunjang informasi institusi yang membuat website sekolah tampak jauh lebih kredibel, interaktif, dan informatif bagi masyarakat luas.

4. DESIGN stitch google / FIGMA
   https://www.figma.com/design/bSoaTk0WRSCIzizlXBkFem/Untitled?node-id=0-1&t=FK2fqMopcePrgoDD-1
