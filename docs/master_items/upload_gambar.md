# Dokumentasi Fitur Upload & Edit Gambar (Master Items)

## 1. Deskripsi Fitur
Fitur ini memungkinkan pengguna untuk mengunggah (upload) gambar/foto barang saat membuat Master Item baru maupun memperbarui (edit) gambar saat mengedit Master Item.

## 2. Penyimpanan & Database
- **Tabel**: `master_items`
- **Kolom**: `foto` (`VARCHAR(255)`, `NULLABLE`) — hanya menyimpan **nama file gambar** (contoh: `1730611920_paracetamol.jpg`).
- **Path Penyimpanan Lokal**: `public/uploads/master_items/`
- **URL Akses Public**: `asset('uploads/master_items/' . $item->foto)`

## 3. Alur Kerja (Flow)
### A. Saat Tambah Data Baru (`method == 'new'`)
1. Form menggunakan atribut `enctype="multipart/form-data"`.
2. Jika pengguna memilih file pada input `name="foto"`, `MasterItemsController@formSubmit` akan:
   - Memastikan direktori `public/uploads/master_items` tersedia.
   - Membuat nama file unik dengan format `time() . '_' . $file->getClientOriginalName()`.
   - Memindahkan file ke `public/uploads/master_items/`.
   - Menyimpan nama file ke kolom `foto` di tabel `master_items`.

### B. Saat Edit Data (`method == 'edit'`)
1. Pada `resources/views/master_items/form/form.blade.php`, jika `$item->foto` sudah ada, ditampilkan preview gambar saat ini beserta nama filenya.
2. Jika pengguna mengunggah gambar baru:
   - File gambar lama di `public/uploads/master_items/` akan dihapus terlebih dahulu (jika ada).
   - File gambar baru disimpan ke `public/uploads/master_items/` dan kolom `foto` diperbarui dengan nama file baru.
3. Jika pengguna membiarkan input file kosong saat edit, foto lama tetap dipertahankan.

