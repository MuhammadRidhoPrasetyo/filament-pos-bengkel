**Panduan Setup Awal - Filament POS Bengkel**  
Selamat datang di aplikasi Filament POS Bengkel! Agar aplikasi dapat berjalan dengan lancar dan semua fitur transaksi dapat digunakan, ada beberapa langkah setup data awal yang **wajib** dilakukan secara berurutan.  
Urutan ini penting karena beberapa data bergantung pada data lain (contoh: Anda tidak bisa membuat Produk tanpa membuat Kategori dan Satuan terlebih dahulu).  
Berikut adalah panduan langkah demi langkah untuk melakukan inisialisasi data:  
![](data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAnEAAAACCAYAAAA3pIp+AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAANklEQVR4nO3OMQ2AABAAsSPBCj7fFRYQwYwEZiywEZJWQZeZ2ao9AAD+4lyruzq+ngAA8Nr1AMTJBeJDClAyAAAAAElFTkSuQmCC)  
**Tahap 1: Konfigurasi Perusahaan & Toko**  
Langkah pertama adalah mengatur data tempat usaha Anda.  
1. **Setup Toko / Cabang (Stores)**  
- **Menu:** Bengkel  
- **Tindakan:** Masukkan data toko atau bengkel Anda. Jika Anda memiliki banyak cabang, masukkan semuanya di sini.  
- **Kenapa ini penting?** Hampir seluruh transaksi (Penjualan, Servis, Pembelian, Stok) dan pengguna (Kasir) terikat pada data Toko. Pastikan Anda juga mengatur format nomor struk (Receipt Number Format) jika diperlukan.  
2. **Setup Pengguna & Karyawan (Users)**  
- **Menu:** Pengguna  
- **Tindakan:** Tambahkan akun untuk karyawan Anda (Kasir, Mekanik, Admin).  
- **Penting:** Pastikan Anda menempatkan (assign) setiap karyawan ke  **Toko** yang sesuai agar mereka hanya mengelola data di cabangnya masing-masing.  
![](data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAnEAAAACCAYAAAA3pIp+AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAANElEQVR4nO3OUQmAABBAsSeILQSjXgcrmkOs4J8IW4ItM7NXZwAA/MW1Vlt1fBwBAOC9+wEukwQ+V/SggAAAAABJRU5ErkJggg==)  
**Tahap 2: Master Data Dasar (Wajib)**  
Sebelum menginput daftar barang, Anda harus mengisi data-data referensi berikut:  
1. **Satuan (Units)**  
- **Menu:** Satuan  
- **Tindakan:** Masukkan satuan barang yang digunakan.  
- **Contoh:**Pcs, Liter, Botol, Set, Kaleng.  
2. **Merk (Brands)**  
- **Menu:** Merk  
- **Tindakan:** Masukkan daftar merk produk/sparepart yang Anda jual.  
- **Contoh:**Yamaha, Honda, Motul, Castrol, YSS.  
3. **Kategori Produk (Product Categories)**  
- **Menu:** Kategori Produk  
- **Tindakan:** Buat pengelompokan produk.  
- **Penting:** Perhatikan kolom  **Item Type** (Barang/Jasa) dan  **Pricing Mode** (Harga Tetap / Bisa Diubah Kasir).  
- **Contoh:**Sparepart Motor (Barang), Oli & Pelumas (Barang), Jasa Servis (Jasa).  
4. **Tipe Diskon (Discount Types)**  
- **Menu:** Jenis Diskon  
- **Tindakan:** Buat kategori diskon jika Anda berencana mengadakan promo.  
- **Contoh:**Promo Akhir Tahun, Diskon Member, P1.  
![](data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAnEAAAACCAYAAAA3pIp+AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAANklEQVR4nO3OMQ2AABAAsSNBCkJfFEIwwIgHRiywEZJWQZeZ2ao9AAD+4lyruzq+ngAA8Nr1AOHsBegrsOrIAAAAAElFTkSuQmCC)  
**Tahap 3: Master Produk & Jasa**  
Setelah Master Data Dasar siap, Anda bisa mulai mendaftarkan barang dan jasa bengkel.  
1. **Master Produk & Jasa (Products)**  
- **Menu:** Produk  
- **Tindakan:** Masukkan data barang/jasa. Anda sekarang bisa memilih Kategori, Merk, dan Satuan yang telah dibuat di Tahap 2.  
- **Tips:** Untuk Jasa Servis, pastikan memilih Kategori yang berjenis "Jasa/Labor".  
2. **Harga Produk (Product Prices)**  
- **Menu:** Produk & Stok > Harga Produk  
- **Tindakan:** Tentukan Harga Beli, Margin (Markup), dan Harga Jual. Harga ini ditentukan per Toko. Jadi satu barang bisa memiliki harga jual yang berbeda di Cabang A dan Cabang B.  
3. **Stok Awal (Product Stocks)**  
- Jika ini adalah pertama kalinya menggunakan sistem, Anda perlu memasukkan jumlah stok fisik yang ada di bengkel saat ini ke dalam sistem.  
- Anda bisa menggunakan fitur **Penyesuaian Stok (Stock Adjustments)** atau  **Pembelian (Purchases)** dari Supplier.  
![](data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAnEAAAACCAYAAAA3pIp+AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAANklEQVR4nO3OMQ2AABAAsSNhRAF6EPYDLhGADSywEZJWQZeZ2aszAAD+4l6rrTq+ngAA8Nr1AIWsBDYDm5cLAAAAAElFTkSuQmCC)  
**Tahap 4: Relasi Pihak Luar (Supplier & Pelanggan)**  
1. **Metode Pembayaran (Payments)**  
- **Menu:** Master Data > Metode Pembayaran  
- **Tindakan:** Daftarkan cara pembayaran yang diterima bengkel.  
- **Contoh:**Tunai, BCA Transfer, QRIS, Kartu Kredit.  
2. **Supplier**  
- **Menu:** Kontak > Supplier  
- **Tindakan:** Masukkan data distributor atau pemasok tempat Anda membeli sparepart. Ini wajib diisi sebelum Anda bisa mencatat Transaksi Pembelian (Restock).  
3. **Pelanggan & Kendaraan (Customer & Vehicles)** *(Bisa menyusul)*  
- **Menu:** Kontak > Pelanggan  
- **Tindakan:** Anda bisa mendaftarkan data pelanggan tetap beserta data  **Kendaraan (Plat Nomor, Tipe Motor)** milik mereka.  
- **Tips:** Data ini bisa juga diinput langsung oleh Kasir/Admin pada saat membuat  **Service Order** baru.  
4. **Kategori Arus Kas (Cash Flow Categories)**  
- **Menu:** Keuangan > Kategori Arus Kas  
- **Tindakan:** Buat kategori untuk mencatat pengeluaran operasional atau pemasukan lain-lain.  
- **Contoh:**Biaya Listrik & Air, Gaji Karyawan, Uang Kebersihan.  
![](data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAnEAAAACCAYAAAA3pIp+AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAANElEQVR4nO3OQQmAABRAsSdYxKa/jL0MIR7FCt5E2BJsmZmt2gMA4C+Otbqr8+sJAACvXQ85SAYUQNBTfQAAAABJRU5ErkJggg==)  
**Tahap 5: Mulai Beroperasi**  
Jika semua langkah di atas sudah dilakukan, bengkel Anda siap menggunakan sistem secara penuh!  
- **Kasir (POS):** Dapat mulai melayani penjualan retail (langsung).  
- **Service Advisor / Admin:** Dapat mulai membuat  **Service Order** untuk kendaraan yang datang, menugaskan  **Mekanik**, dan menambahkan item Jasa/Sparepart.  
- **Gudang:** Dapat melakukan  **Pembelian (Purchases)** dari supplier untuk menambah stok barang, atau  **Transfer Stok** antar cabang.  
![](data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAnEAAAACCAYAAAA3pIp+AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAANElEQVR4nO3OQQmAABRAsaeILbwZ9Fewo0Gs4E2ELcGWmTmqKwAA/uLeqr06v54AAPDa+gAthwNEfGhnhAAAAABJRU5ErkJggg==)  
