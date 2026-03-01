# Database Schema - Filament POS Bengkel

**Project:** Filament POS Bengkel  
**Generated:** 2026-02-22  
**Purpose:** Dokumentasi lengkap semua table dan field dalam database

---

## Daftar Isi

1. [Authentication & Session](#authentication--session)
2. [Cache & Jobs](#cache--jobs)
3. [Authorization (Permission & Roles)](#authorization-permission--roles)
4. [Master Data](#master-data)
5. [Products & Stock](#products--stock)
6. [Transactions](#transactions)
7. [Purchases & Suppliers](#purchases--suppliers)
8. [Service Management](#service-management)
9. [Stock Management](#stock-management)
10. [Other Tables](#other-tables)
11. [Cash Flow](#cash-flow)

---

## Authentication & Session

### 📋 users

Tabel pengguna sistem.

| Field             | Type      | Attributes                  | Keterangan                       |
| ----------------- | --------- | --------------------------- | -------------------------------- |
| id                | bigint    | PRIMARY KEY, AUTO INCREMENT |                                  |
| name              | string    |                             | Nama pengguna                    |
| email             | string    | UNIQUE                      | Email pengguna                   |
| email_verified_at | timestamp | NULLABLE                    | Waktu verifikasi email           |
| password          | string    |                             | Password terenkripsi             |
| remember_token    | string    | NULLABLE                    | Token untuk fitur "Remember Me"  |
| nik               | string    | UNIQUE, NULLABLE            | Nomor Induk Karyawan             |
| phone             | string    | NULLABLE                    | Nomor telepon                    |
| address           | string    | NULLABLE                    | Alamat                           |
| store_id          | uuid      | NULLABLE, FK (stores)       | Toko tempat kerja                |
| active            | boolean   | DEFAULT: true               | Status aktif pengguna            |
| top_navigation    | boolean   | DEFAULT: false              | Preferensi layout navigasi admin |
| created_at        | timestamp |                             |                                  |
| updated_at        | timestamp |                             |                                  |

---

### 📋 sessions

Tabel sesi pengguna.

| Field         | Type       | Attributes                  | Keterangan               |
| ------------- | ---------- | --------------------------- | ------------------------ |
| id            | string     | PRIMARY KEY                 | ID unik sesi             |
| user_id       | bigint     | NULLABLE, INDEX, FK (users) | Pengguna yang login      |
| ip_address    | string(45) | NULLABLE                    | Alamat IP                |
| user_agent    | text       | NULLABLE                    | User agent browser       |
| payload       | longtext   |                             | Data sesi                |
| last_activity | integer    | INDEX                       | Waktu aktivitas terakhir |

---

### 📋 password_reset_tokens

Tabel token reset password.

| Field      | Type      | Attributes  | Keterangan            |
| ---------- | --------- | ----------- | --------------------- |
| email      | string    | PRIMARY KEY | Email pengguna        |
| token      | string    |             | Token reset           |
| created_at | timestamp | NULLABLE    | Waktu pembuatan token |

---

## Cache & Jobs

### 📋 cache

Tabel penyimpanan cache.

| Field      | Type       | Attributes  | Keterangan                        |
| ---------- | ---------- | ----------- | --------------------------------- |
| key        | string     | PRIMARY KEY | Kunci cache                       |
| value      | mediumtext |             | Nilai cache                       |
| expiration | integer    |             | Waktu kadaluarsa (unix timestamp) |

---

### 📋 cache_locks

Tabel cache locks untuk concurrency control.

| Field      | Type    | Attributes  | Keterangan       |
| ---------- | ------- | ----------- | ---------------- |
| key        | string  | PRIMARY KEY | Kunci lock       |
| owner      | string  |             | Pemilik lock     |
| expiration | integer |             | Waktu kadaluarsa |

---

### 📋 jobs

Tabel antrian job background.

| Field        | Type                | Attributes                  | Keterangan                          |
| ------------ | ------------------- | --------------------------- | ----------------------------------- |
| id           | bigint              | PRIMARY KEY, AUTO INCREMENT |                                     |
| queue        | string              | INDEX                       | Nama queue                          |
| payload      | longtext            |                             | Data job                            |
| attempts     | unsignedTinyInteger |                             | Jumlah usaha eksekusi               |
| reserved_at  | unsignedInteger     | NULLABLE                    | Waktu job direserve                 |
| available_at | unsignedInteger     |                             | Waktu job tersedia untuk dijalankan |
| created_at   | unsignedInteger     |                             |                                     |

---

### 📋 job_batches

Tabel batch jobs.

| Field          | Type       | Attributes  | Keterangan             |
| -------------- | ---------- | ----------- | ---------------------- |
| id             | string     | PRIMARY KEY | ID batch               |
| name           | string     |             | Nama batch             |
| total_jobs     | integer    |             | Total job dalam batch  |
| pending_jobs   | integer    |             | Job yang masih pending |
| failed_jobs    | integer    |             | Job yang gagal         |
| failed_job_ids | longtext   |             | ID job yang gagal      |
| options        | mediumtext | NULLABLE    | Opsi batch (JSON)      |
| cancelled_at   | integer    | NULLABLE    | Waktu batch dibatalkan |
| created_at     | integer    |             |                        |
| finished_at    | integer    | NULLABLE    | Waktu batch selesai    |

---

### 📋 failed_jobs

Tabel job yang gagal.

| Field      | Type      | Attributes                  | Keterangan         |
| ---------- | --------- | --------------------------- | ------------------ |
| id         | bigint    | PRIMARY KEY, AUTO INCREMENT |                    |
| uuid       | string    | UNIQUE                      | UUID unik          |
| connection | text      |                             | Nama koneksi queue |
| queue      | text      |                             | Nama queue         |
| payload    | longtext  |                             | Data job           |
| exception  | longtext  |                             | Exception error    |
| failed_at  | timestamp | DEFAULT: CURRENT_TIMESTAMP  |                    |

---

## Authorization (Permission & Roles)

### 📋 permissions

Tabel permission/izin.

| Field      | Type      | Attributes                  | Keterangan                 |
| ---------- | --------- | --------------------------- | -------------------------- |
| id         | bigint    | PRIMARY KEY, AUTO INCREMENT |                            |
| name       | string    |                             | Nama permission            |
| guard_name | string    |                             | Guard name (web, api, dll) |
| created_at | timestamp |                             |                            |
| updated_at | timestamp |                             |                            |

**Unique Index:** (name, guard_name)

---

### 📋 roles

Tabel role/peran.

| Field            | Type               | Attributes                  | Keterangan               |
| ---------------- | ------------------ | --------------------------- | ------------------------ |
| id               | bigint             | PRIMARY KEY, AUTO INCREMENT |                          |
| team_foreign_key | unsignedBigInteger | NULLABLE, INDEX             | Tim (jika multi-tenancy) |
| name             | string             |                             | Nama role                |
| guard_name       | string             |                             | Guard name               |
| created_at       | timestamp          |                             |                          |
| updated_at       | timestamp          |                             |                          |

**Unique Index:** (team_foreign_key, name, guard_name) atau (name, guard_name)

---

### 📋 model_has_permissions

Tabel relasi model dengan permission (pivot).

| Field            | Type               | Attributes       | Keterangan             |
| ---------------- | ------------------ | ---------------- | ---------------------- |
| permission_id    | unsignedBigInteger | FK (permissions) |                        |
| model_type       | string             |                  | Model type (morphable) |
| model_morph_key  | unsignedBigInteger |                  | ID model (morphable)   |
| team_foreign_key | unsignedBigInteger | NULLABLE, INDEX  | Tim (opsional)         |

**Primary Key:** (team_foreign_key, permission_id, model_morph_key, model_type) atau (permission_id, model_morph_key, model_type)

---

### 📋 model_has_roles

Tabel relasi model dengan role (pivot).

| Field            | Type               | Attributes      | Keterangan             |
| ---------------- | ------------------ | --------------- | ---------------------- |
| role_id          | unsignedBigInteger | FK (roles)      |                        |
| model_type       | string             |                 | Model type (morphable) |
| model_morph_key  | unsignedBigInteger |                 | ID model (morphable)   |
| team_foreign_key | unsignedBigInteger | NULLABLE, INDEX | Tim (opsional)         |

**Primary Key:** (team_foreign_key, role_id, model_morph_key, model_type) atau (role_id, model_morph_key, model_type)

---

### 📋 role_has_permissions

Tabel relasi role dengan permission (pivot).

| Field         | Type               | Attributes       | Keterangan |
| ------------- | ------------------ | ---------------- | ---------- |
| permission_id | unsignedBigInteger | FK (permissions) |            |
| role_id       | unsignedBigInteger | FK (roles)       |            |

**Primary Key:** (permission_id, role_id)

---

## Master Data

### 📋 stores

Tabel toko/cabang.

| Field                 | Type                 | Attributes                                   | Keterangan             |
| --------------------- | -------------------- | -------------------------------------------- | ---------------------- |
| id                    | uuid                 | PRIMARY KEY                                  |                        |
| code                  | string               | UNIQUE                                       | Kode toko, misal: T01  |
| name                  | string               |                                              | Nama toko              |
| phone                 | string               | NULLABLE                                     | Nomor telepon          |
| email                 | string               | NULLABLE                                     | Email                  |
| address               | string               | NULLABLE                                     | Alamat                 |
| city                  | string               | NULLABLE                                     | Kota                   |
| province              | string               | NULLABLE                                     | Provinsi               |
| postal_code           | string               | NULLABLE                                     | Kode pos               |
| receipt_number_format | string               | DEFAULT: '{STORE_CODE}/{YYYY}/{MM}/{NUMBER}' | Format nomor struk     |
| receipt_sequence      | unsignedInteger      | DEFAULT: 0                                   | Counter nomor struk    |
| receipt_sequence_year | unsignedSmallInteger | NULLABLE                                     | Tahun terakhir counter |
| notes                 | text                 | NULLABLE                                     | Catatan                |
| created_at            | timestamp            |                                              |                        |
| updated_at            | timestamp            |                                              |                        |

---

### 📋 suppliers

Tabel supplier/customer.

| Field          | Type      | Attributes                             | Keterangan                   |
| -------------- | --------- | -------------------------------------- | ---------------------------- |
| id             | uuid      | PRIMARY KEY                            |                              |
| code           | string    | UNIQUE                                 | Kode supplier, misal: SUP001 |
| name           | string    |                                        | Nama                         |
| contact_person | string    | NULLABLE                               | Nama PIC                     |
| phone          | string    | NULLABLE                               | Nomor telepon                |
| email          | string    | NULLABLE                               | Email                        |
| address        | text      | NULLABLE                               | Alamat                       |
| city           | string    | NULLABLE                               | Kota                         |
| province       | string    | NULLABLE                               | Provinsi                     |
| postal_code    | string    | NULLABLE                               | Kode pos                     |
| npwp           | string    | NULLABLE                               | NPWP                         |
| bank_name      | string    | NULLABLE                               | Nama bank                    |
| bank_account   | string    | NULLABLE                               | Nomor rekening               |
| type           | enum      | VALUES: 'supplier', 'customer', 'both' | Tipe                         |
| notes          | text      | NULLABLE                               | Catatan                      |
| created_at     | timestamp |                                        |                              |
| updated_at     | timestamp |                                        |                              |

---

### 📋 discount_types

Tabel tipe diskon.

| Field       | Type      | Attributes                  | Keterangan       |
| ----------- | --------- | --------------------------- | ---------------- |
| id          | bigint    | PRIMARY KEY, AUTO INCREMENT |                  |
| name        | string    |                             | Nama tipe diskon |
| description | string    | NULLABLE                    | Deskripsi        |
| created_at  | timestamp |                             |                  |
| updated_at  | timestamp |                             |                  |

---

### 📋 brands

Tabel merk/brand produk.

| Field      | Type      | Attributes                  | Keterangan |
| ---------- | --------- | --------------------------- | ---------- |
| id         | bigint    | PRIMARY KEY, AUTO INCREMENT |            |
| name       | string    |                             | Nama merk  |
| created_at | timestamp |                             |            |
| updated_at | timestamp |                             |            |

---

### 📋 units

Tabel satuan pengukuran.

| Field      | Type      | Attributes                  | Keterangan                      |
| ---------- | --------- | --------------------------- | ------------------------------- |
| id         | bigint    | PRIMARY KEY, AUTO INCREMENT |                                 |
| name       | string    |                             | Nama satuan (Pcs, Liter, Meter) |
| symbol     | string    | NULLABLE                    | Simbol (pcs, L, m)              |
| created_at | timestamp |                             |                                 |
| updated_at | timestamp |                             |                                 |

---

### 📋 product_categories

Tabel kategori produk.

| Field        | Type      | Attributes                                    | Keterangan                     |
| ------------ | --------- | --------------------------------------------- | ------------------------------ |
| id           | bigint    | PRIMARY KEY, AUTO INCREMENT                   |                                |
| name         | string    |                                               | Nama kategori                  |
| pricing_mode | enum      | VALUES: 'fixed', 'editable', DEFAULT: 'fixed' | Mode harga (tetap/bisa diubah) |
| item_type    | enum      | VALUES: 'part', 'labor', DEFAULT: 'part'      | Tipe item (barang/jasa)        |
| created_at   | timestamp |                                               |                                |
| updated_at   | timestamp |                                               |                                |

---

## Products & Stock

### 📋 products

Tabel produk.

| Field               | Type      | Attributes              | Keterangan     |
| ------------------- | --------- | ----------------------- | -------------- |
| id                  | uuid      | PRIMARY KEY             |                |
| product_category_id | bigint    | FK (product_categories) | Kategori       |
| brand_id            | bigint    | NULLABLE, FK (brands)   | Merk           |
| unit_id             | bigint    | NULLABLE, FK (units)    | Satuan         |
| sku                 | string    | UNIQUE, NULLABLE        | SKU produk     |
| name                | string    |                         | Nama produk    |
| type                | string    | NULLABLE                | Tipe           |
| keyword             | string    | NULLABLE                | Keyword        |
| compatibility       | string    | NULLABLE                | Kompatibilitas |
| size                | string    | NULLABLE                | Ukuran         |
| unit                | string    | NULLABLE                | Unit           |
| description         | text      | NULLABLE                | Deskripsi      |
| created_at          | timestamp |                         |                |
| updated_at          | timestamp |                         |                |

---

### 📋 product_prices

Tabel harga produk per toko.

| Field          | Type          | Attributes                                     | Keterangan   |
| -------------- | ------------- | ---------------------------------------------- | ------------ |
| id             | uuid          | PRIMARY KEY                                    |              |
| product_id     | uuid          | FK (products, CASCADE)                         | Produk       |
| store_id       | uuid          | FK (stores, CASCADE)                           | Toko         |
| price_type     | enum          | VALUES: 'toko', 'distributor', DEFAULT: 'toko' | Tipe harga   |
| purchase_price | decimal(12,2) |                                                | Harga beli   |
| markup         | decimal(12,2) | DEFAULT: 0                                     | Markup       |
| markup_type    | enum          | VALUES: 'percent', 'amount', NULLABLE          | Tipe markup  |
| selling_price  | decimal(12,2) |                                                | Harga jual   |
| is_active      | boolean       | DEFAULT: false                                 | Status aktif |
| created_at     | timestamp     |                                                |              |
| updated_at     | timestamp     |                                                |              |

---

### 📋 product_stocks

Tabel stok produk per toko.

| Field            | Type            | Attributes                     | Keterangan           |
| ---------------- | --------------- | ------------------------------ | -------------------- |
| id               | uuid            | PRIMARY KEY                    |                      |
| product_id       | uuid            | FK (products)                  | Produk               |
| store_id         | uuid            | FK (stores)                    | Toko                 |
| quantity         | unsignedInteger |                                | Jumlah stok          |
| is_hidden        | boolean         | DEFAULT: false, AFTER quantity | Tersembunyi dari POS |
| minimum_stock    | integer         | DEFAULT: 0                     | Minimum stok alert   |
| product_price_id | uuid            | NULLABLE, FK (product_prices)  | Harga produk         |
| created_at       | timestamp       |                                |                      |
| updated_at       | timestamp       |                                |                      |

---

### 📋 product_discounts

Tabel diskon produk.

| Field            | Type          | Attributes                  | Keterangan         |
| ---------------- | ------------- | --------------------------- | ------------------ |
| id               | uuid          | PRIMARY KEY                 |                    |
| product_id       | uuid          | FK (products)               | Produk             |
| store_id         | uuid          | FK (stores)                 | Toko               |
| discount_type_id | bigint        | FK (discount_types)         | Tipe diskon        |
| type             | enum          | VALUES: 'percent', 'amount' | Jenis nilai diskon |
| value            | decimal(12,2) |                             | Nilai diskon       |
| created_at       | timestamp     |                             |                    |
| updated_at       | timestamp     |                             |                    |

---

### 📋 product_price_histories

Tabel riwayat perubahan harga produk.

| Field            | Type      | Attributes                   | Keterangan        |
| ---------------- | --------- | ---------------------------- | ----------------- |
| id               | uuid      | PRIMARY KEY                  |                   |
| product_id       | uuid      | FK (products, CASCADE)       | Produk            |
| store_id         | uuid      | FK (stores, CASCADE)         | Toko              |
| product_price_id | uuid      | FK (product_prices, CASCADE) | Harga produk      |
| date             | dateTime  |                              | Tanggal perubahan |
| created_at       | timestamp |                              |                   |
| updated_at       | timestamp |                              |                   |

---

### 📋 product_labels

Tabel template label produk.

| Field               | Type      | Attributes              | Keterangan               |
| ------------------- | --------- | ----------------------- | ------------------------ |
| id                  | uuid      | PRIMARY KEY             |                          |
| product_id          | uuid      | FK (products, CASCADE)  | Produk                   |
| product_category_id | bigint    | FK (product_categories) | Kategori                 |
| brand_id            | bigint    | NULLABLE, FK (brands)   | Merk                     |
| label_sku           | boolean   | NULLABLE                | Tampilkan SKU            |
| label_category      | boolean   | NULLABLE                | Tampilkan kategori       |
| label_brand         | boolean   | NULLABLE                | Tampilkan merk           |
| label_type          | boolean   | NULLABLE                | Tampilkan tipe           |
| label_unit          | boolean   | NULLABLE                | Tampilkan satuan         |
| label_size          | boolean   | NULLABLE                | Tampilkan ukuran         |
| label_keyword       | boolean   | NULLABLE                | Tampilkan keyword        |
| label_compatibility | boolean   | NULLABLE                | Tampilkan kompatibilitas |
| label_description   | boolean   | NULLABLE                | Tampilkan deskripsi      |
| separator           | string    | NULLABLE                | Pemisah field            |
| created_at          | timestamp |                         |                          |
| updated_at          | timestamp |                         |                          |

---

## Transactions

### 📋 transactions

Tabel transaksi (penjualan).

| Field                        | Type          | Attributes                                                             | Keterangan                         |
| ---------------------------- | ------------- | ---------------------------------------------------------------------- | ---------------------------------- |
| id                           | uuid          | PRIMARY KEY                                                            |                                    |
| number                       | string        | UNIQUE                                                                 | Nomor transaksi (POS-20250224-001) |
| store_id                     | uuid          | FK (stores), INDEX                                                     | Toko                               |
| user_id                      | bigint        | FK (users), INDEX                                                      | Kasir                              |
| customer_id                  | uuid          | NULLABLE, FK (suppliers)                                               | Pelanggan                          |
| payment_id                   | uuid          | NULLABLE, FK (payments)                                                | Metode pembayaran                  |
| transaction_date             | dateTime      | INDEX                                                                  | Tanggal transaksi                  |
| type                         | enum          | VALUES: 'retail', 'service', 'internal', 'warranty', DEFAULT: 'retail' | Tipe transaksi                     |
| service_order_id             | uuid          | NULLABLE, FK (service_orders)                                          | Service order (jika tipe service)  |
| subtotal                     | decimal(15,2) | DEFAULT: 0                                                             | Subtotal sebelum diskon item       |
| item_discount_total          | decimal(15,2) | DEFAULT: 0                                                             | Total diskon item                  |
| subtotal_after_item_discount | decimal(15,2) | DEFAULT: 0                                                             | Subtotal setelah diskon item       |
| universal_discount_mode      | enum          | VALUES: 'percent', 'amount', NULLABLE                                  | Mode diskon universal              |
| universal_discount_value     | decimal(12,2) | NULLABLE                                                               | Nilai diskon universal             |
| universal_discount_amount    | decimal(15,2) | DEFAULT: 0                                                             | Nominal diskon universal           |
| tax_total                    | decimal(15,2) | DEFAULT: 0                                                             | Total pajak                        |
| grand_total                  | decimal(15,2) | DEFAULT: 0                                                             | Total yang harus dibayar           |
| paid_amount                  | decimal(15,2) | DEFAULT: 0                                                             | Uang yang diterima                 |
| change_amount                | decimal(15,2) | DEFAULT: 0                                                             | Kembalian                          |
| payment_status               | enum          | VALUES: 'unpaid', 'partial', 'paid', 'refunded', DEFAULT: 'paid'       | Status pembayaran                  |
| total_cost                   | decimal(15,2) | DEFAULT: 0                                                             | Total biaya/modal                  |
| total_profit                 | decimal(15,2) | DEFAULT: 0                                                             | Total laba                         |
| status                       | enum          | VALUES: 'draft', 'completed', 'void', DEFAULT: 'completed'             | Status transaksi                   |
| note                         | text          | NULLABLE                                                               | Catatan                            |
| created_at                   | timestamp     |                                                                        |                                    |
| updated_at                   | timestamp     |                                                                        |                                    |

---

### 📋 transaction_items

Tabel item/detail transaksi.

| Field                | Type            | Attributes                            | Keterangan                        |
| -------------------- | --------------- | ------------------------------------- | --------------------------------- |
| id                   | uuid            | PRIMARY KEY                           |                                   |
| transaction_id       | uuid            | FK (transactions, CASCADE), INDEX     | Transaksi                         |
| product_id           | uuid            | FK (products)                         | Produk                            |
| store_id             | uuid            | FK (stores)                           | Toko (redundan untuk laporan)     |
| product_stock_id     | uuid            | NULLABLE, FK (product_stocks)         | Stok produk (pemberi referensi)   |
| quantity             | unsignedInteger |                                       | Jumlah                            |
| unit_price           | decimal(12,2)   |                                       | Harga per unit                    |
| item_discount_mode   | enum            | VALUES: 'percent', 'amount', NULLABLE | Mode diskon item                  |
| item_discount_value  | decimal(12,2)   | NULLABLE                              | Nilai diskon item                 |
| item_discount_amount | decimal(15,2)   | DEFAULT: 0                            | Nominal diskon item               |
| final_unit_price     | decimal(12,2)   |                                       | Harga per unit setelah diskon     |
| line_subtotal        | decimal(15,2)   |                                       | Subtotal line (qty × unit_price)  |
| line_total           | decimal(15,2)   |                                       | Total line setelah diskon         |
| discount_type_id     | bigint          | NULLABLE, FK (discount_types)         | Tipe diskon yang dipakai          |
| unit_cost            | decimal(12,2)   | DEFAULT: 0                            | Harga modal per unit              |
| line_cost_total      | decimal(15,2)   | DEFAULT: 0                            | Total modal line                  |
| line_profit          | decimal(15,2)   | DEFAULT: 0                            | Laba line                         |
| price_edited         | boolean         | DEFAULT: false                        | Harga diubah manual               |
| pricing_mode         | string          | NULLABLE                              | Mode pricing ('fixed'/'editable') |
| created_at           | timestamp       |                                       |                                   |
| updated_at           | timestamp       |                                       |                                   |

---

### 📋 transaction_payment_attempts

Tabel percobaan pembayaran transaksi.

| Field          | Type          | Attributes                 | Keterangan          |
| -------------- | ------------- | -------------------------- | ------------------- |
| id             | uuid          | PRIMARY KEY                |                     |
| transaction_id | uuid          | FK (transactions, CASCADE) | Transaksi           |
| user_id        | bigint        | NULLABLE, FK (users)       | Pengguna            |
| payment_id     | uuid          | NULLABLE, FK (payments)    | Metode pembayaran   |
| amount         | decimal(15,2) | DEFAULT: 0                 | Nominal pembayaran  |
| amount_given   | decimal(15,2) | NULLABLE                   | Uang yang diberikan |
| change         | decimal(15,2) | NULLABLE                   | Kembalian           |
| paid_at        | timestamp     | NULLABLE                   | Waktu pembayaran    |
| metadata       | json          | NULLABLE                   | Data tambahan       |
| created_at     | timestamp     |                            |                     |
| updated_at     | timestamp     |                            |                     |

---

## Purchases & Suppliers

### 📋 purchases

Tabel pembelian dari supplier.

| Field          | Type          | Attributes                            | Keterangan             |
| -------------- | ------------- | ------------------------------------- | ---------------------- |
| id             | uuid          | PRIMARY KEY                           |                        |
| store_id       | uuid          | FK (stores, CASCADE)                  | Toko                   |
| supplier_id    | uuid          | FK (suppliers, CASCADE)               | Supplier               |
| created_by     | bigint        | FK (users)                            | User pembuat           |
| received_by    | bigint        | NULLABLE, FK (users)                  | User penerima          |
| number         | string        | UNIQUE                                | Nomor pembelian        |
| invoice_number | string        | NULLABLE                              | Nomor invoice supplier |
| purchase_date  | date          |                                       | Tanggal pembelian      |
| discount_type  | enum          | VALUES: 'percent', 'amount', NULLABLE | Tipe diskon header     |
| discount_value | decimal(12,2) | NULLABLE                              | Nilai diskon header    |
| price          | decimal(15,2) | DEFAULT: 0                            | Total harga            |
| notes          | text          | NULLABLE                              | Catatan                |
| created_at     | timestamp     |                                       |                        |
| updated_at     | timestamp     |                                       |                        |

---

### 📋 purchase_items

Tabel item/detail pembelian.

| Field               | Type            | Attributes                                     | Keterangan          |
| ------------------- | --------------- | ---------------------------------------------- | ------------------- |
| id                  | uuid            | PRIMARY KEY                                    |                     |
| purchase_id         | uuid            | FK (purchases, CASCADE)                        | Pembelian           |
| product_id          | uuid            | FK (products)                                  | Produk              |
| price_type          | enum            | VALUES: 'toko', 'distributor', DEFAULT: 'toko' | Tipe harga beli     |
| quantity_ordered    | unsignedInteger |                                                | Jumlah pesan        |
| unit_purchase_price | decimal(12,2)   |                                                | Harga beli per unit |
| item_discount_type  | enum            | VALUES: 'percent', 'amount', NULLABLE          | Tipe diskon item    |
| item_discount_value | decimal(12,2)   | NULLABLE                                       | Nilai diskon item   |
| created_at          | timestamp       |                                                |                     |
| updated_at          | timestamp       |                                                |                     |

---

## Service Management

### 📋 service_orders

Tabel order service (bengkel).

| Field             | Type          | Attributes                                                                                              | Keterangan               |
| ----------------- | ------------- | ------------------------------------------------------------------------------------------------------- | ------------------------ |
| id                | uuid          | PRIMARY KEY                                                                                             |                          |
| number            | string        | UNIQUE                                                                                                  | Nomor SO (SO-202511-001) |
| store_id          | uuid          | FK (stores)                                                                                             | Toko/bengkel             |
| customer_id       | uuid          | NULLABLE, FK (suppliers)                                                                                | Pelanggan                |
| status            | enum          | VALUES: 'checkin', 'in_progress', 'waiting_parts', 'ready', 'invoiced', 'cancelled', DEFAULT: 'checkin' | Status global            |
| checkin_at        | dateTime      |                                                                                                         | Waktu check-in           |
| completed_at      | dateTime      | NULLABLE                                                                                                | Waktu selesai            |
| general_complaint | text          | NULLABLE                                                                                                | Keluhan umum             |
| estimated_total   | decimal(15,2) | DEFAULT: 0                                                                                              | Estimasi total           |
| transaction_id    | uuid          | NULLABLE, FK (transactions)                                                                             | Transaksi POS            |
| created_at        | timestamp     |                                                                                                         |                          |
| updated_at        | timestamp     |                                                                                                         |                          |

---

### 📋 customer_vehicles

Tabel kendaraan pelanggan.

| Field        | Type      | Attributes               | Keterangan                |
| ------------ | --------- | ------------------------ | ------------------------- |
| id           | uuid      | PRIMARY KEY              |                           |
| customer_id  | uuid      | NULLABLE, FK (suppliers) | Pelanggan                 |
| plate_number | string    |                          | Nomor polisi (KT 1234 AB) |
| brand        | string    | NULLABLE                 | Merk (Honda, Yamaha)      |
| model        | string    | NULLABLE                 | Model (Beat, Vario)       |
| year         | year      | NULLABLE                 | Tahun                     |
| color        | string    | NULLABLE                 | Warna                     |
| notes        | text      | NULLABLE                 | Catatan                   |
| created_at   | timestamp |                          |                           |
| updated_at   | timestamp |                          |                           |

**Unique Index:** (customer_id, plate_number)

---

### 📋 service_order_units

Tabel unit/kendaraan dalam service order.

| Field               | Type          | Attributes                                                                                                           | Keterangan          |
| ------------------- | ------------- | -------------------------------------------------------------------------------------------------------------------- | ------------------- |
| id                  | uuid          | PRIMARY KEY                                                                                                          |                     |
| service_order_id    | uuid          | FK (service_orders, CASCADE), INDEX                                                                                  | Service order       |
| customer_vehicle_id | uuid          | FK (customer_vehicles)                                                                                               | Kendaraan           |
| status              | enum          | VALUES: 'checkin', 'diagnosis', 'in_progress', 'waiting_parts', 'ready', 'invoiced', 'cancelled', DEFAULT: 'checkin' | Status unit         |
| checkin_at          | dateTime      |                                                                                                                      | Waktu check-in      |
| completed_at        | dateTime      | NULLABLE                                                                                                             | Waktu selesai       |
| plate_number        | string        |                                                                                                                      | Nomor polisi        |
| brand               | string        | NULLABLE                                                                                                             | Merk                |
| model               | string        | NULLABLE                                                                                                             | Model               |
| color               | string        | NULLABLE                                                                                                             | Warna               |
| complaint           | text          | NULLABLE                                                                                                             | Keluhan spesifik    |
| diagnosis           | text          | NULLABLE                                                                                                             | Diagnosis montir    |
| work_done           | text          | NULLABLE                                                                                                             | Ringkasan pekerjaan |
| estimated_total     | decimal(15,2) | DEFAULT: 0                                                                                                           | Estimasi total      |
| created_at          | timestamp     |                                                                                                                      |                     |
| updated_at          | timestamp     |                                                                                                                      |                     |

---

### 📋 service_order_unit_mechanics

Tabel montir yang menangani unit.

| Field                 | Type         | Attributes                                       | Keterangan      |
| --------------------- | ------------ | ------------------------------------------------ | --------------- |
| service_order_unit_id | uuid         | FK (service_order_units, CASCADE)                | Service unit    |
| mechanic_id           | bigint       | FK (users)                                       | Montir/user     |
| role                  | enum         | VALUES: 'leader', 'assistant', DEFAULT: 'leader' | Peran           |
| work_portion          | decimal(5,2) | NULLABLE                                         | Porsi pekerjaan |
| created_at            | timestamp    |                                                  |                 |
| updated_at            | timestamp    |                                                  |                 |

**Unique Index:** (service_order_unit_id, mechanic_id)

---

### 📋 service_order_items

Tabel item dalam service order unit.

| Field                 | Type            | Attributes                               | Keterangan                    |
| --------------------- | --------------- | ---------------------------------------- | ----------------------------- |
| id                    | uuid            | PRIMARY KEY                              |                               |
| service_order_unit_id | uuid            | FK (service_order_units, CASCADE), INDEX | Service unit                  |
| item_type             | enum            | VALUES: 'part', 'labor'                  | Tipe item (barang/jasa)       |
| product_id            | uuid            | NULLABLE, FK (products)                  | Produk (opsional)             |
| description           | string          | NULLABLE                                 | Deskripsi (untuk jasa custom) |
| quantity              | unsignedInteger | DEFAULT: 1                               | Jumlah                        |
| unit_price            | decimal(12,2)   | DEFAULT: 0                               | Harga per unit                |
| line_total            | decimal(15,2)   | DEFAULT: 0                               | Total line                    |
| created_at            | timestamp       |                                          |                               |
| updated_at            | timestamp       |                                          |                               |

---

### 📋 service_order_customers

Tabel data pelanggan dalam service order.

| Field            | Type      | Attributes                        | Keterangan    |
| ---------------- | --------- | --------------------------------- | ------------- |
| id               | uuid      | PRIMARY KEY                       |               |
| service_order_id | uuid      | FK (service_orders, CASCADE)      | Service order |
| customer_id      | uuid      | NULLABLE, FK (suppliers, CASCADE) | Pelanggan     |
| name             | string    |                                   | Nama          |
| phone            | string    | NULLABLE                          | Nomor telepon |
| address          | text      | NULLABLE                          | Alamat        |
| created_at       | timestamp |                                   |               |
| updated_at       | timestamp |                                   |               |

---

## Stock Management

### 📋 product_movements

Tabel pergerakan stok produk.

| Field             | Type            | Attributes           | Keterangan              |
| ----------------- | --------------- | -------------------- | ----------------------- |
| id                | uuid            | PRIMARY KEY          |                         |
| product_id        | uuid            | FK (products)        | Produk                  |
| store_id          | uuid            | FK (stores)          | Toko                    |
| movement_type     | enum            | VALUES: 'in', 'out'  | Tipe (masuk/keluar)     |
| quantity          | unsignedInteger |                      | Jumlah (selalu positif) |
| movementable_type | string          | NULLABLE             | Tipe sumber (morphable) |
| movementable_id   | uuid            | NULLABLE             | ID sumber (morphable)   |
| occurred_at       | timestamp       |                      | Waktu kejadian          |
| created_by        | bigint          | NULLABLE, FK (users) | User pembuat            |
| note              | string          | NULLABLE             | Catatan                 |
| created_at        | timestamp       |                      |                         |
| updated_at        | timestamp       |                      |                         |

---

### 📋 stock_adjustments

Tabel penyesuaian stok.

| Field            | Type      | Attributes                 | Keterangan              |
| ---------------- | --------- | -------------------------- | ----------------------- |
| id               | uuid      | PRIMARY KEY                |                         |
| store_id         | uuid      | FK (stores, CASCADE)       | Toko                    |
| posted_by        | bigint    | NULLABLE, FK (users)       | User posting            |
| reference_number | string    | NULLABLE                   | Nomor referensi dokumen |
| occurred_at      | timestamp | DEFAULT: CURRENT_TIMESTAMP | Waktu kejadian          |
| note             | text      | NULLABLE                   | Catatan                 |
| created_at       | timestamp |                            |                         |
| updated_at       | timestamp |                            |                         |

---

### 📋 stock_adjustment_items

Tabel item penyesuaian stok.

| Field               | Type            | Attributes                      | Keterangan       |
| ------------------- | --------------- | ------------------------------- | ---------------- |
| id                  | uuid            | PRIMARY KEY                     |                  |
| stock_adjustment_id | uuid            | FK (stock_adjustments, CASCADE) | Penyesuaian stok |
| product_id          | uuid            | FK (products)                   | Produk           |
| adjustment_type     | enum            | VALUES: 'increase', 'decrease'  | Tipe penyesuaian |
| quantity            | unsignedInteger |                                 | Jumlah           |
| note                | string          | NULLABLE                        | Catatan          |
| created_at          | timestamp       |                                 |                  |
| updated_at          | timestamp       |                                 |                  |

---

### 📋 stock_transfers

Tabel transfer stok antar toko.

| Field            | Type      | Attributes                                               | Keterangan      |
| ---------------- | --------- | -------------------------------------------------------- | --------------- |
| id               | uuid      | PRIMARY KEY                                              |                 |
| from_store_id    | uuid      | FK (stores)                                              | Toko asal       |
| to_store_id      | uuid      | FK (stores)                                              | Toko tujuan     |
| status           | enum      | VALUES: 'draft', 'posted', 'cancelled', DEFAULT: 'draft' | Status          |
| reference_number | string    | NULLABLE                                                 | Nomor referensi |
| occurred_at      | timestamp | DEFAULT: CURRENT_TIMESTAMP                               | Waktu kejadian  |
| created_by       | bigint    | NULLABLE, FK (users)                                     | User pembuat    |
| posted_by        | bigint    | NULLABLE, FK (users)                                     | User posting    |
| posted_at        | timestamp | NULLABLE                                                 | Waktu posting   |
| note             | text      | NULLABLE                                                 | Catatan         |
| created_at       | timestamp |                                                          |                 |
| updated_at       | timestamp |                                                          |                 |

---

### 📋 stock_transfer_items

Tabel item transfer stok.

| Field             | Type            | Attributes                    | Keterangan              |
| ----------------- | --------------- | ----------------------------- | ----------------------- |
| id                | uuid            | PRIMARY KEY                   |                         |
| stock_transfer_id | uuid            | FK (stock_transfers, CASCADE) | Transfer stok           |
| product_id        | uuid            | FK (products)                 | Produk                  |
| quantity          | unsignedInteger |                               | Jumlah                  |
| product_price_id  | uuid            | NULLABLE, FK (product_prices) | Harga produk (opsional) |
| created_at        | timestamp       |                               |                         |
| updated_at        | timestamp       |                               |                         |

---

## Other Tables

### 📋 payments

Tabel metode pembayaran.

| Field          | Type      | Attributes  | Keterangan                   |
| -------------- | --------- | ----------- | ---------------------------- |
| id             | uuid      | PRIMARY KEY |                              |
| name           | string    |             | Nama metode (QRIS, BCA, BRI) |
| type           | string    | NULLABLE    | Tipe                         |
| account_number | string    | NULLABLE    | Nomor rekening               |
| account_name   | string    | NULLABLE    | Nama rekening                |
| provider_code  | string    | NULLABLE    | Kode provider                |
| created_at     | timestamp |             |                              |
| updated_at     | timestamp |             |                              |

---

### 📋 document_sequences

Tabel sequence/counter nomor dokumen.

| Field      | Type      | Attributes                     | Keterangan                            |
| ---------- | --------- | ------------------------------ | ------------------------------------- |
| id         | uuid      | PRIMARY KEY                    |                                       |
| type       | string    |                                | Tipe dokumen (TRX, SRV, PUR, SERVICE) |
| store_id   | uuid      | NULLABLE, FK (stores, CASCADE) | Toko (jika multi-cabang)              |
| sequence   | integer   | DEFAULT: 0                     | Counter sequence                      |
| year       | integer   | NULLABLE                       | Tahun                                 |
| created_at | timestamp |                                |                                       |
| updated_at | timestamp |                                |                                       |

**Unique Index:** (type, store_id, year)

---

### 📋 media

Tabel media/file (spatie/laravel-medialibrary).

| Field                 | Type               | Attributes                  | Keterangan                      |
| --------------------- | ------------------ | --------------------------- | ------------------------------- |
| id                    | bigint             | PRIMARY KEY, AUTO INCREMENT |                                 |
| model_id              | uuid               |                             | ID model (morphable, UUID-safe) |
| model_type            | string             |                             | Tipe model (morphable)          |
| uuid                  | uuid               | UNIQUE, NULLABLE            | UUID media                      |
| collection_name       | string             |                             | Nama koleksi                    |
| name                  | string             |                             | Nama media                      |
| file_name             | string             |                             | Nama file                       |
| mime_type             | string             | NULLABLE                    | MIME type                       |
| disk                  | string             |                             | Disk storage                    |
| conversions_disk      | string             | NULLABLE                    | Disk konversi                   |
| size                  | unsignedBigInteger |                             | Ukuran file (bytes)             |
| manipulations         | json               |                             | Manipulasi (JSON)               |
| custom_properties     | json               |                             | Custom properties (JSON)        |
| generated_conversions | json               |                             | Generated conversions (JSON)    |
| responsive_images     | json               |                             | Responsive images (JSON)        |
| order_column          | unsignedInteger    | NULLABLE, INDEX             | Urutan                          |
| created_at            | timestamp          | NULLABLE                    |                                 |
| updated_at            | timestamp          | NULLABLE                    |                                 |

---

## Cash Flow

### 📋 cash_flow_categories

Tabel kategori alur kas (pemasukan/pengeluaran).

| Field       | Type      | Attributes                  | Keterangan                                      |
| ----------- | --------- | --------------------------- | ----------------------------------------------- |
| id          | bigint    | PRIMARY KEY, AUTO INCREMENT |                                                 |
| name        | string    |                             | Nama kategori (Gaji, Listrik, Operasional, dll) |
| type        | enum      | VALUES: 'income', 'expense' | Jenis kategori (pemasukan/pengeluaran)          |
| description | string    | NULLABLE                    | Deskripsi opsional                              |
| is_active   | boolean   | DEFAULT: true               | Status aktif                                    |
| created_at  | timestamp |                             |                                                 |
| updated_at  | timestamp |                             |                                                 |

---

### 📋 cash_flows

Tabel catatan alur kas masuk dan keluar harian.

| Field          | Type          | Attributes                  | Keterangan                                                    |
| -------------- | ------------- | --------------------------- | ------------------------------------------------------------- |
| id             | uuid          | PRIMARY KEY                 |                                                               |
| store_id       | uuid          | FK (stores, CASCADE)        | Toko/cabang terkait                                           |
| user_id        | bigint        | FK (users)                  | User pencatat transaksi                                       |
| category_id    | bigint        | FK (cash_flow_categories)   | Kategori kas                                                  |
| amount         | decimal(15,2) | DEFAULT: 0                  | Nominal uang                                                  |
| date           | date          |                             | Tanggal kas masuk/keluar                                      |
| type           | enum          | VALUES: 'income', 'expense' | Jenis kategori (pemasukan/pengeluaran)                        |
| description    | text          | NULLABLE                    | Keterangan / rincian pengeluaran                              |
| reference_type | string        | NULLABLE                    | Morph type (integrasi otomatis, misal App\Models\Transaction) |
| reference_id   | uuid          | NULLABLE                    | Morph ID (ID dokumen terkait)                                 |
| created_at     | timestamp     |                             |                                                               |
| updated_at     | timestamp     |                             |                                                               |

---

## Relasi Foreign Key Summary

| From Table                   | FK Field              | References Table     | On Delete      |
| ---------------------------- | --------------------- | -------------------- | -------------- |
| users                        | store_id              | stores               | -              |
| sessions                     | user_id               | users                | -              |
| product_prices               | product_id            | products             | CASCADE        |
| product_prices               | store_id              | stores               | CASCADE        |
| product_stocks               | product_id            | products             | -              |
| product_stocks               | store_id              | stores               | -              |
| product_stocks               | product_price_id      | product_prices       | -              |
| product_discounts            | product_id            | products             | -              |
| product_discounts            | store_id              | stores               | -              |
| product_discounts            | discount_type_id      | discount_types       | -              |
| products                     | product_category_id   | product_categories   | -              |
| products                     | brand_id              | brands               | -              |
| products                     | unit_id               | units                | -              |
| purchases                    | store_id              | stores               | CASCADE        |
| purchases                    | supplier_id           | suppliers            | CASCADE        |
| purchases                    | created_by            | users                | -              |
| purchases                    | received_by           | users                | -              |
| purchase_items               | purchase_id           | purchases            | CASCADE        |
| purchase_items               | product_id            | products             | -              |
| user_id                      | printers              | printers             | -              |
| transactions                 | store_id              | stores               | -              |
| transactions                 | user_id               | users                | -              |
| transactions                 | customer_id           | suppliers            | -              |
| transactions                 | payment_id            | payments             | -              |
| transactions                 | service_order_id      | service_orders       | NULL ON DELETE |
| transaction_items            | transaction_id        | transactions         | CASCADE        |
| transaction_items            | product_id            | products             | -              |
| transaction_items            | store_id              | stores               | -              |
| transaction_items            | product_stock_id      | product_stocks       | -              |
| transaction_items            | discount_type_id      | discount_types       | NULL ON DELETE |
| transaction_payment_attempts | transaction_id        | transactions         | CASCADE        |
| transaction_payment_attempts | user_id               | users                | NULL ON DELETE |
| transaction_payment_attempts | payment_id            | payments             | NULL ON DELETE |
| stock_adjustments            | store_id              | stores               | CASCADE        |
| stock_adjustments            | posted_by             | users                | -              |
| stock_adjustment_items       | stock_adjustment_id   | stock_adjustments    | CASCADE        |
| stock_adjustment_items       | product_id            | products             | -              |
| product_movements            | product_id            | products             | -              |
| product_movements            | store_id              | stores               | -              |
| product_movements            | created_by            | users                | -              |
| product_price_histories      | product_id            | products             | CASCADE        |
| product_price_histories      | store_id              | stores               | CASCADE        |
| product_price_histories      | product_price_id      | product_prices       | CASCADE        |
| product_labels               | product_id            | products             | CASCADE        |
| product_labels               | product_category_id   | product_categories   | -              |
| product_labels               | brand_id              | brands               | -              |
| service_orders               | store_id              | stores               | -              |
| service_orders               | customer_id           | suppliers            | NULL ON DELETE |
| service_orders               | transaction_id        | transactions         | NULL ON DELETE |
| customer_vehicles            | customer_id           | suppliers            | -              |
| service_order_units          | service_order_id      | service_orders       | CASCADE        |
| service_order_units          | customer_vehicle_id   | customer_vehicles    | -              |
| service_order_unit_mechanics | service_order_unit_id | service_order_units  | CASCADE        |
| service_order_unit_mechanics | mechanic_id           | users                | -              |
| service_order_items          | service_order_unit_id | service_order_units  | CASCADE        |
| service_order_items          | product_id            | products             | -              |
| service_order_customers      | service_order_id      | service_orders       | CASCADE        |
| service_order_customers      | customer_id           | suppliers            | CASCADE        |
| document_sequences           | store_id              | stores               | CASCADE        |
| stock_transfers              | from_store_id         | stores               | -              |
| stock_transfers              | to_store_id           | stores               | -              |
| stock_transfers              | created_by            | users                | -              |
| stock_transfers              | posted_by             | users                | -              |
| stock_transfer_items         | stock_transfer_id     | stock_transfers      | CASCADE        |
| stock_transfer_items         | product_id            | products             | -              |
| stock_transfer_items         | product_price_id      | product_prices       | -              |
| cash_flows                   | store_id              | stores               | CASCADE        |
| cash_flows                   | user_id               | users                | -              |
| cash_flows                   | category_id           | cash_flow_categories | -              |

---

## Notes

- **UUID Fields**: Beberapa tabel menggunakan UUID sebagai primary key (universally unique identifier)
- **Timestamps**: Hampir semua tabel memiliki `created_at` dan `updated_at` fields untuk audit trail
- **Soft Delete**: Tidak ada kolom `deleted_at` yang terlihat, jadi tidak ada soft delete
- **Morphable Relations**: Beberapa tabel menggunakan polymorphic relationships
- **Cascading Deletes**: Foreign keys tertentu dihapus secara cascade untuk memastikan referential integrity
- **Multi-Store**: Sistem mendukung multi-toko/cabang dengan field `store_id`
- **Permission System**: Menggunakan package spatie/laravel-permission untuk authorization

---

**Last Updated:** 2026-02-27  
**Project:** Filament POS Bengkel
