# Panduan Test Manual — Perbaikan Alur Stok (Transaction, StockAdjustment, Purchase)

Dokumen ini berisi skenario uji manual yang harus dijalankan setelah perbaikan observer & service stok. Tujuan: memastikan setiap aksi edit menghasilkan stok akhir yang benar dan tidak ada jejak movement yang menggantung.

## Persiapan Awal (sekali sebelum testing)

1. Login sebagai owner (peran yang berhak edit dokumen completed/posted).
2. Pastikan minimal ada 2 produk part (track stock) pada 1 bengkel:
   - Produk A — stok awal 50
   - Produk B — stok awal 30
3. Pastikan ada minimal 1 supplier untuk transaksi Purchase.
4. Pastikan `CashFlowCategory` system sudah terdaftar:
   - "Penjualan" → tipe `income`, `is_system = true`
   - "Pembelian Stok" → tipe `expense`, `is_system = true`
5. Sebelum tiap skenario, catat stok awal produk yang akan disentuh. Setelah aksi, bandingkan dengan ekspektasi.

## Cara Cek Cepat

- **Stok produk** → menu Produk → Stok per Bengkel.
- **Movement** → via `php artisan tinker`:
  ```php
  \App\Models\ProductMovement::latest()
      ->take(10)
      ->get(['product_id','store_id','movement_type','quantity','movementable_type','occurred_at']);
  ```
- **CashFlow** → menu CashFlow / Laporan Kas.

---

## A. Transaction (POS) — Edit Item & Status

### A1. Edit qty naik pada transaksi completed

1. Buat transaksi completed berisi Produk A qty 3. Stok A turun 50 → 47.
2. Edit transaksi: ubah qty A jadi 5. Simpan.

**Ekspektasi:** stok A = 45 (turun 2 lagi). `product_movements` untuk item itu ber-qty 5.

### A2. Edit qty turun

1. Lanjut dari A1 (qty 5, stok 45). Edit qty A jadi 2. Simpan.

**Ekspektasi:** stok A = 48 (kembali 3). Movement qty = 2.

### A3. Edit qty naik tapi stok kurang

1. Buat transaksi qty Produk B = 25 (stok B sisa 5). Edit qty B jadi 50.

**Ekspektasi:** error "Stok tidak cukup untuk mengubah qty transaksi.", simpan gagal, stok tidak berubah.

### A4. Ganti produk di item (A → B)

1. Buat transaksi completed: Produk A qty 4 (stok A: 50 → 46).
2. Edit transaksi: ganti Produk A → Produk B, qty 4. Simpan.

**Ekspektasi:** stok A kembali 50 (rollback penuh), stok B turun 4. Movement berpindah ke `product_id` B, qty 4.

### A5. Hapus item dari transaksi completed

1. Buat transaksi 2 item: A qty 3, B qty 2 (stok A:47, B:28).
2. Edit: hapus item B. Simpan.

**Ekspektasi:** stok B kembali 30. Movement untuk B terhapus. Item A tetap.

### A6. Tambah item baru saat edit

1. Lanjut A5. Edit: tambah baris baru Produk B qty 1. Simpan.

**Ekspektasi:** stok B 30 → 29. Movement baru tercatat.

### A7. Status `draft → completed`

1. Buat transaksi status=draft, item A qty 5. Simpan.
   - Verifikasi: stok A tidak berubah, tidak ada CashFlow.
2. Edit transaksi: ubah status → completed. Simpan.

**Ekspektasi:** stok A turun 5. CashFlow income "Penjualan #..." muncul dengan `amount = grand_total`. Movement out untuk item dibuat.

### A8. Status `completed → void`

1. Lanjut A7 (status completed). Edit status → void. Simpan.

**Ekspektasi:** stok A kembali (naik 5). CashFlow terkait dihapus. Movement terhapus.

### A9. Status `void → completed`

1. Lanjut A8. Edit status → completed.

**Ekspektasi:** stok A turun 5 lagi. CashFlow baru muncul.

### A10. Hapus transaksi completed

1. Buat transaksi completed item A qty 2. Hapus dari list/detail.

**Ekspektasi:** stok A kembali, CashFlow ikut terhapus (cascade — verifikasi).

### A11. Field `store_id` saat edit terkunci

1. Buka edit transaksi apa pun.

**Ekspektasi:** field "Bengkel" disabled (tidak bisa diubah).

---

## B. Stock Adjustment — Item-Level Edits

### B1. Create adjustment `increase`

1. Buat adjustment item: Produk A, type=Masuk (increase), qty 10. Simpan.

**Ekspektasi:** stok A naik 10. Movement `in` qty 10 tercatat.

### B2. Edit qty di item adjustment

1. Lanjut B1. Edit adjustment: ubah qty item A jadi 4. Simpan.

**Ekspektasi:** rollback qty 10 dulu (stok turun 10), apply qty 4 (stok naik 4). Net dari titik sebelum edit: stok bertambah 4 (bukan 10). Movement qty=4.

### B3. Ganti type `increase → decrease`

1. Buat adjustment item A type=increase qty 5 (stok +5).
2. Edit: ubah type → decrease, qty 5.

**Ekspektasi:** rollback efek lama (stok -5), apply efek baru (stok -5 lagi). Net dari titik sebelum edit: stok turun 10 total. Movement `type = out`.

### B4. Ganti produk di item adjustment

1. Buat adjustment item A increase qty 3 (stok A +3).
2. Edit: ganti produk → B, type=increase, qty 3.

**Ekspektasi:** stok A kembali -3, stok B +3. Movement berpindah ke produk B.

### B5. Hapus salah satu item dari adjustment (multi-item)

1. Buat adjustment 2 item: A increase 5, B decrease 2.
2. Edit: hapus item B.

**Ekspektasi:** efek B di-rollback (stok B kembali +2). Item A tetap. Movement B terhapus.

### B6. Tambah item baru saat edit adjustment

1. Edit adjustment yang sudah ada. Tambah baris baru: Produk B decrease qty 1. Simpan.

**Ekspektasi:** stok B turun 1 segera saat simpan. Movement baru muncul.

### B7. Hapus seluruh adjustment

1. Buat adjustment item A increase 7 (stok A +7). Hapus adjustment.

**Ekspektasi:** stok A kembali -7. Semua movement terkait terhapus. Item adjustment terhapus.

### B8. Edit `occurred_at` adjustment

1. Edit adjustment, ubah tanggal `occurred_at`. Simpan.

**Ekspektasi:** stok tidak berubah. `product_movements.occurred_at` ikut update.

### B9. Field `store_id` adjustment terkunci

1. Buka create dan edit adjustment.

**Ekspektasi:** field "Bengkel" tetap disabled di kedua mode.

---

## C. Purchase

### C1. Create purchase

1. Buat purchase item: A qty 20, B qty 10, price total 500000. Simpan.

**Ekspektasi:** stok A +20, B +10. Movement `in` per item. CashFlow expense "Pembelian #..." dengan `amount=500000` muncul.

### C2. Edit qty item purchase (naik)

1. Lanjut C1. Edit: A qty 20 → 25.

**Ekspektasi:** stok A naik 5 lagi (delta). Movement A qty=25.

### C3. Edit qty item purchase (turun)

1. Lanjut C2. Edit: A qty 25 → 18.

**Ekspektasi:** stok A turun 7 (delta). Movement qty=18.

### C4. Ganti produk di item purchase

1. Edit item A jadi produk C (qty sama). Simpan.

**Ekspektasi:** stok A turun sebanyak qty lama, stok C naik sebanyak qty baru. Movement berpindah ke produk C.

### C5. Tambah item baru saat edit purchase

1. Edit purchase, tambah baris baru produk D qty 5. Simpan.

**Ekspektasi:** stok D naik 5. Movement baru tercatat.

> Sebelum perbaikan, stok D **tidak** naik — bug yang sekarang sudah diperbaiki.

### C6. Hapus salah satu item purchase

1. Edit purchase, hapus baris produk B (qty terakhir 10). Simpan.

**Ekspektasi:** stok B turun 10 (rollback). Movement B terhapus.

> Sebelum perbaikan, stok B **tidak** turun — bug yang sekarang sudah diperbaiki.

### C7. Edit `price` purchase

1. Edit purchase, ubah `price` dari 500000 → 600000. Simpan.

**Ekspektasi:** CashFlow terkait ter-update `amount=600000`.

### C8. Edit `purchase_date`

1. Edit `purchase_date`.

**Ekspektasi:** CashFlow `date` ikut update.

### C9. Hapus purchase

1. Hapus purchase (multi item).

**Ekspektasi:** semua stok yang sebelumnya naik, turun kembali sesuai qty. Movement terkait terhapus. CashFlow purchase terhapus.

### C10. Field `store_id` purchase saat edit terkunci

1. Buka edit purchase.

**Ekspektasi:** field "Bengkel" disabled.

---

## D. Smoke Test Integrasi

### D1. Tidak ada error log

Setelah jalankan A-C di atas, buka `storage/logs/laravel.log`.

**Ekspektasi:** tidak ada `BadMethodCallException: recomputeForMovement` (bug lama yang sudah fix).

### D2. Konsistensi `product_stocks` vs `product_movements`

1. Pilih 1 produk uji.
2. Hitung manual: stok awal + total movement `in` − total movement `out` untuk produk+bengkel itu.

**Ekspektasi:** hasil hitung = `product_stocks.quantity` saat ini.

### D3. Tidak ada movement orphan

Jalankan query (ulangi untuk `purchase_items`, `stock_adjustment_items`):

```sql
SELECT COUNT(*) FROM product_movements
WHERE movementable_type = 'App\\Models\\TransactionItem'
  AND movementable_id NOT IN (SELECT id FROM transaction_items);
```

**Ekspektasi:** hasil 0 untuk semua morph type.

---

## Prioritas Test (Jika Waktu Terbatas)

1. **C5, C6** — bug paling fatal sebelum fix (purchase edit bocor stok).
2. **B2, B3, B7** — bug enum + observer kosong sebelum fix.
3. **A4, A7, A8** — pergantian produk + transisi status (logika paling baru).
4. **A11, B9, C10** — kunci `store_id`.
5. Sisanya untuk regression cover.

---

## Format Laporan dari User

Untuk tiap skenario, mohon laporkan:

| ID | Status | Catatan |
|----|--------|---------|
| A1 | PASS / FAIL | (jika FAIL: stok awal, stok akhir, langkah pengulangan, screenshot/log) |
| ... | | |

FAIL menyertakan:
- Stok awal (nilai sebelum aksi)
- Stok akhir aktual
- Langkah reproduksi
- Screenshot UI atau cuplikan `storage/logs/laravel.log` jika ada error
