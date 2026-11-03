# Dokumentasi Menu Kategori Items

## 1. Gambaran Umum
Menu **Kategori Items** berfungsi untuk mengelola data kategori barang (`kode` dan `nama`), memiliki relasi **Many-to-Many** dengan **Master Items** melalui tabel pivot `kategori_item_master_item`, navigasi melalui **Navbar**, serta fitur **Printout/Download PDF** laporan detail kategori.

## 2. Struktur File Terkait
- **Navbar Layout**: `resources/views/layouts/app.blade.php` (memuat link navbar `Master Items` dan `Kategori Items`)
- **Controller**: `app/Http/Controllers/KategoriItemsController.php` (termasuk method `downloadPdf`)
- **Model**: `app/Models/KategoriItem.php`
- **Migrasi Database**:
  - `database/migrations/2026_11_03_000001_create_kategori_items_table.php` (Tabel `kategori_items`)
  - `database/migrations/2026_11_03_000002_create_kategori_item_master_item_table.php` (Tabel pivot `kategori_item_master_item`)
- **Views**:
  - `resources/views/kategori_items/index/index.blade.php` (Halaman utama daftar kategori, memiliki tombol `+ Kategori Items Baru`)
  - `resources/views/kategori_items/index/filter.blade.php` (Filter pencarian berdasarkan `kode` dan `nama` beserta tombol Filter dan Reset)
  - `resources/views/kategori_items/index/table.blade.php` (Tabel DataTables daftar kategori)
  - `resources/views/kategori_items/index/js.blade.php` (Script AJAX pencarian DataTables)
  - `resources/views/kategori_items/form/index.blade.php` (Wrapper halaman form tambah/edit kategori)
  - `resources/views/kategori_items/form/form.blade.php` (Form input `kode` dan `nama`)
  - `resources/views/kategori_items/single/index.blade.php` (Halaman detail kategori beserta tabel daftar Master Items dan tombol Download PDF)
  - `resources/views/kategori_items/pdf/single.blade.php` (Template cetak PDF dengan DomPDF)

## 3. Checklist Spesifikasi Soal Bagian 3
- [x] **Point 1**: Kategori memiliki field nama dan kode.
- [x] **Point 2**: Halaman index memiliki filter nama dan kode kategori (dilengkapi tombol Reset).
- [x] **Point 3**: Halaman view single kategori:
  - Menampilkan nama dan kode kategori tersebut.
  - Menampilkan list item yang memiliki kategori tersebut dalam bentuk tabel.
- [x] **Point 4**: Penambahan field "Kategori" pada halaman CRUD Master Items (Index, Form Select2 Multiple, dan Single View).
- [x] **Point 5**: Link untuk mengakses **Kategori Items** dan **Master Items** telah ditambahkan di bagian **Navbar** atas (`layouts/app.blade.php`).
