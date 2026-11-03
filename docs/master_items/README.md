# Dokumentasi Menu Master Items

## 1. Gambaran Umum
Menu **Master Items** berfungsi untuk mengelola data barang/item (CRUD: Tambah, Lihat Daftar, Pencarian/Filter, Detail Item, Edit, dan Hapus dengan SoftDeletes), dilengkapi fitur upload foto lokal, relasi Many-to-Many dengan **Kategori Items** menggunakan Select2 Multiple, serta fitur **Download Data Master Items ke format Excel (.xls)**.

## 2. Struktur File Terkait
- **Controller**: `app/Http/Controllers/MasterItemsController.php` (memiliki method `downloadExcel`)
- **Model**: `app/Models/MasterItem.php` (memiliki relasi `belongsToMany` `kategoriItems()`)
- **Migrasi Database**:
  - `database/migrations/2022_11_05_005605_create_master_items_table.php`
  - `database/migrations/2026_11_03_000000_add_foto_to_master_items_table.php`
  - `database/migrations/2026_11_03_000002_create_kategori_item_master_item_table.php`
- **Views**:
  - `resources/views/master_items/index/index.blade.php` (Halaman utama daftar item, memiliki tombol `+ Master Items Baru`)
  - `resources/views/master_items/index/filter.blade.php` (Filter pencarian item beserta tombol Filter, Reset, dan Download Excel)
  - `resources/views/master_items/index/table.blade.php` (Tabel DataTables daftar item termasuk kolom Kategori)
  - `resources/views/master_items/index/js.blade.php` (Script AJAX DataTables, memformat harga ke Rupiah `Rp ...` dan menggabungkan nama kategori dengan koma)
  - `resources/views/master_items/form/index.blade.php` (Wrapper halaman form tambah/edit + inisialisasi Select2)
  - `resources/views/master_items/form/form.blade.php` (Form input tambah/edit beserta Select2 Kategori Items dan upload foto)
  - `resources/views/master_items/single/index.blade.php` (Halaman detail single item beserta daftar nama kategori berpisah koma, currency Rupiah, dan preview foto)

## 3. Alur Fungsi Utama
1. **Daftar, Pencarian & Reset Filter (`index` & `search`)**:
   - Mengambil data dari tabel `master_items` via AJAX (`GET /master-items/search`) dengan relasi `kategoriItems`.
   - Filter `hargamin` dan `hargamax` diproses secara independen menggunakan `$request->filled()` sehingga:
     - Bisa memfilter hanya `Harga Min` (harga >= min).
     - Bisa memfilter hanya `Harga Max` (harga <= max).
     - Bisa memfilter rentang harga (keduanya diisi).
     - Mendukung nilai `0`.
   - Tersedia tombol **Filter** dan tombol **Reset** (warna hijau) untuk mengosongkan semua isian filter dan memuat ulang tabel.
   - Menampilkan kolom **Kategori** yang memuat seluruh nama kategori terkait yang dipisahkan dengan tanda koma (contoh: *Obat Bebas, Suplemen*).
   - Menampilkan kolom **Harga Beli** dan **Harga Jual** dalam format currency Indonesia (`Rp xx.xxx`).
2. **Download Data Excel (`downloadExcel`)**:
   - Menu/tombol **Download Excel** tersedia di halaman daftar Master Items (`GET /master-items/download-excel`).
   - Menghasilkan file Excel (`.xls`) dengan kolom:
     1. No
     2. Nama kategori (terpisah koma)
     3. Nama items
     4. Nama supplier
     5. Harga
     6. Laba
     7. Harga jual (menggunakan format currency Rupiah: `Rp xx.xxx`)
3. **Tambah & Edit Item (`formView` & `formSubmit`)**:
   - Menampilkan form (`GET /master-items/form/{method}/{id?}`) beserta daftar `KategoriItem` untuk pilihan Select2.
   - Menyimpan data (`POST /master-items/form/{method}/{id?}`).
   - Menyinkronkan relasi kategori menggunakan `$data_item->kategoriItems()->sync($request->kategori_ids ?? [])`.
   - Mendukung upload gambar/foto barang yang disimpan secara lokal di `public/uploads/master_items/` dan menyimpan nama file gambarnya pada kolom `foto` di tabel `master_items`.
4. **Detail Item (`singleView`)**:
   - Menampilkan informasi lengkap item termasuk harga beli & harga jual dalam format Rupiah, daftar nama kategori (dipisah koma), dan preview foto barang (`GET /master-items/view/{kode}`).
5. **Hapus Item (`delete`)**:
   - Menghapus item menggunakan fitur `SoftDeletes` (`GET /master-items/delete/{id}`).
