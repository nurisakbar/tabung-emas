# Dokumentasi Fitur Gadai Emas
## Sistem Gadai Emas untuk Tabung Emas

---

## 📋 Overview

**Fitur Gadai Emas** memungkinkan nasabah untuk menggadaikan emas yang mereka miliki di tabungan untuk mendapatkan pinjaman tunai. Sistem ini mengikuti prinsip-prinsip gadai syariah dengan sistem cicilan pembayaran dan opsi tebus emas.

---

## 🎯 Fitur-Fitur Utama

### 1. **Pengajuan Gadai**
- Nasabah mengajukan gadai emas dari tabungan mereka
- Sistem menghitung nilai taksiran emas (biasanya 80-90% dari harga jual)
- Nasabah menentukan jumlah pinjaman (maksimal sesuai nilai taksiran)
- Sistem menghitung biaya administrasi dan jasa simpan
- Nasabah memilih jangka waktu gadai (1, 3, 6, 12 bulan)

### 2. **Perhitungan Nilai Gadai**
- **Nilai Taksiran** = Jumlah Emas (gram) × Harga Jual Emas × Persentase Taksiran (80-90%)
- **Maksimal Pinjaman** = Nilai Taksiran
- **Biaya Administrasi** = Flat atau persentase (dapat dikonfigurasi)
- **Jasa Simpan** = Persentase per bulan dari nilai pinjaman
- **Total yang Harus Dibayar** = Pinjaman + Biaya Admin + (Jasa Simpan × Jangka Waktu)

### 3. **Manajemen Status Gadai**
- **Pending**: Pengajuan menunggu persetujuan admin
- **Active**: Gadai aktif, emas dipegang sistem
- **Lunas**: Pinjaman sudah dilunasi, emas dikembalikan
- **Jatuh Tempo**: Melewati batas waktu tanpa pelunasan
- **Lelang**: Emas dilelang karena tidak ditebus (opsional)

### 4. **Sistem Pembayaran**
- **Pembayaran Cicilan**: Nasabah bisa membayar cicilan per bulan
- **Pelunasan Lebih Cepat**: Nasabah bisa melunasi kapan saja
- **Perpanjangan**: Nasabah bisa memperpanjang jangka waktu (dengan biaya tambahan)
- **Tebus Emas**: Setelah lunas, emas dikembalikan ke tabungan nasabah

### 5. **Tracking & Monitoring**
- Dashboard nasabah: Lihat status gadai aktif
- Riwayat pembayaran cicilan
- Notifikasi jatuh tempo
- Progress pembayaran
- Admin: Monitoring semua gadai aktif, overdue, dll

---

## 🔄 Flow Sistem

### **Flow 1: Pengajuan Gadai**

```
1. Nasabah → Pilih "Gadai Emas" dari menu
2. Sistem tampilkan:
   - Total emas di tabungan
   - Harga jual emas saat ini
   - Kalkulator nilai taksiran
3. Nasabah input:
   - Jumlah emas yang akan digadaikan
   - Jumlah pinjaman yang diinginkan
   - Jangka waktu gadai
4. System hitung:
   - Nilai taksiran
   - Maksimal pinjaman
   - Biaya administrasi
   - Jasa simpan per bulan
   - Total yang harus dibayar
5. Nasabah review & konfirmasi
6. System create:
   - GoldPledge (status: pending)
   - Lock emas dari GoldSaving (tidak bisa dijual/ditarik)
7. Admin verifikasi & approve
8. Status berubah jadi "active"
9. Pinjaman dicairkan (opsional: transfer ke rekening nasabah)
```

### **Flow 2: Pembayaran Cicilan**

```
1. System generate reminder (X hari sebelum jatuh tempo)
2. Nasabah → Pilih gadai aktif
3. Pilih "Bayar Cicilan"
4. System hitung:
   - Cicilan bulan ini
   - Denda jika terlambat
   - Total yang harus dibayar
5. Nasabah konfirmasi pembayaran
6. System update:
   - PledgePayment (status: pending)
   - Admin verifikasi pembayaran
7. Setelah verified:
   - Update total_paid
   - Update progress
   - Jika sudah lunas → status jadi "lunas", emas dikembalikan
```

### **Flow 3: Pelunasan Lebih Cepat**

```
1. Nasabah → Pilih gadai aktif
2. Pilih "Pelunasan Lebih Cepat"
3. System hitung sisa yang harus dibayar
4. Nasabah konfirmasi
5. System:
   - Mark semua cicilan tersisa sebagai "paid"
   - Status jadi "lunas"
   - Emas dikembalikan ke GoldSaving
   - Unlock emas (bisa dijual/ditarik lagi)
```

### **Flow 4: Perpanjangan Jangka Waktu**

```
1. Nasabah → Pilih gadai aktif (mendekati jatuh tempo)
2. Pilih "Perpanjang Jangka Waktu"
3. System hitung:
   - Biaya perpanjangan
   - Jasa simpan tambahan
4. Nasabah bayar biaya perpanjangan
5. System update:
   - End date diperpanjang
   - Status tetap "active"
```

---

## 💾 Struktur Database

### **Tabel: `gold_pledges`**
```php
- id
- user_id (foreign key)
- gold_amount (decimal) // Jumlah emas yang digadaikan
- price_per_gram (decimal) // Harga jual saat pengajuan
- appraisal_value (decimal) // Nilai taksiran
- loan_amount (decimal) // Jumlah pinjaman
- admin_fee (decimal) // Biaya administrasi
- storage_fee_rate (decimal) // Persentase jasa simpan per bulan
- total_amount (decimal) // Total yang harus dibayar
- paid_amount (decimal) // Total sudah dibayar
- start_date (date)
- end_date (date)
- extension_count (int) // Berapa kali diperpanjang
- status (enum: pending, active, paid, overdue, auctioned)
- notes (text)
- approved_by (user_id, nullable) // Admin yang approve
- approved_at (datetime, nullable)
- timestamps
```

### **Tabel: `pledge_payments`**
```php
- id
- pledge_id (foreign key)
- payment_type (enum: installment, full_payment, extension_fee)
- payment_amount (decimal)
- payment_date (date)
- payment_method (enum: transfer, cash, other)
- reference_number (string, nullable)
- status (enum: pending, verified, rejected)
- verified_by (user_id, nullable)
- verified_at (datetime, nullable)
- notes (text)
- timestamps
```

### **Tabel: `pledge_extensions`** (Opsional)
```php
- id
- pledge_id (foreign key)
- extension_duration (int) // Berapa bulan diperpanjang
- extension_fee (decimal)
- new_end_date (date)
- status (enum: pending, approved)
- timestamps
```

---

## 📊 Business Rules

### **Perhitungan Nilai**
1. **Nilai Taksiran** = Emas (gram) × Harga Jual × 85% (default)
2. **Maksimal Pinjaman** = Nilai Taksiran
3. **Biaya Admin** = Flat Rp 50.000 atau 2% dari pinjaman (dapat dikonfigurasi)
4. **Jasa Simpan** = 1.5% per bulan dari pinjaman (dapat dikonfigurasi)
5. **Denda Keterlambatan** = 2% per hari dari cicilan (dapat dikonfigurasi)

### **Aturan Gadai**
1. Minimum emas yang bisa digadaikan: 1 gram
2. Maksimal jangka waktu: 12 bulan (dapat diperpanjang)
3. Emas yang digadaikan tidak bisa dijual/ditarik sampai lunas
4. Nasabah bisa melunasi kapan saja tanpa penalti
5. Jika jatuh tempo tanpa pelunasan, emas bisa dilelang (opsional)

### **Alokasi Emas**
1. Saat gadai aktif: Emas di-lock di GoldSaving (total_gold tetap, tapi ada flag locked)
2. Saat lunas: Emas di-unlock, kembali bisa dijual/ditarik
3. Jika lelang: Emas dikurangi dari GoldSaving

---

## 🎨 Fitur Tambahan (Opsional)

### **1. Kalkulator Gadai**
- Preview nilai taksiran sebelum ajukan
- Simulasi cicilan per bulan
- Perhitungan total biaya

### **2. Notifikasi**
- Email/SMS reminder sebelum jatuh tempo
- Notifikasi pembayaran berhasil
- Notifikasi gadai lunas

### **3. Laporan Admin**
- Dashboard statistik gadai aktif
- Laporan gadai overdue
- Laporan pendapatan dari jasa simpan
- Export data gadai

### **4. Integrasi Payment**
- Payment gateway untuk pembayaran otomatis
- Auto-debit untuk cicilan bulanan

### **5. Sistem Lelang** (Advanced)
- Jika gadai tidak ditebus setelah X hari
- Admin bisa lelang emas
- Proses lelang otomatis atau manual

---

## 🔐 Keamanan & Validasi

### **Validasi**
- Cek apakah nasabah punya cukup emas di tabungan
- Validasi jumlah pinjaman tidak melebihi nilai taksiran
- Validasi jangka waktu dalam batas yang diizinkan
- Validasi pembayaran tidak melebihi total yang harus dibayar

### **Authorization**
- Hanya nasabah yang bisa akses gadai mereka sendiri
- Hanya admin yang bisa approve/reject pengajuan
- Hanya admin yang bisa verifikasi pembayaran

### **Data Integrity**
- Lock emas saat gadai aktif (prevent double gadai)
- Transaction untuk semua operasi kritis
- Audit trail untuk semua perubahan status

---

## 📱 User Interface

### **Nasabah**
- **Halaman Daftar Gadai**: List semua gadai (aktif, lunas, dll)
- **Halaman Ajukan Gadai**: Form dengan kalkulator
- **Halaman Detail Gadai**: Info lengkap, jadwal pembayaran, riwayat
- **Halaman Pembayaran**: Form bayar cicilan/pelunasan
- **Halaman Perpanjangan**: Form perpanjang jangka waktu

### **Admin**
- **Halaman Daftar Gadai**: Semua gadai dengan filter & search
- **Halaman Detail Gadai**: Info lengkap, approve/reject, verifikasi pembayaran
- **Dashboard**: Statistik gadai aktif, overdue, pendapatan

---

## 🔄 Integrasi dengan Sistem Existing

### **GoldSaving Integration**
- Lock/unlock emas saat gadai aktif/lunas
- Update `total_gold` tetap, tapi ada field `locked_gold`
- Atau buat field `available_gold` = `total_gold` - `locked_gold`

### **Transaction Integration**
- Bisa link pembayaran gadai ke Transaction model
- Atau buat model terpisah untuk tracking yang lebih detail

### **User Integration**
- Link ke user yang punya gadai
- Track history gadai per user

---

## 📈 Metrik & Analytics

### **Untuk Nasabah**
- Total gadai aktif
- Total pinjaman aktif
- Progress pembayaran
- Jatuh tempo berikutnya

### **Untuk Admin**
- Total gadai aktif
- Total nilai pinjaman aktif
- Total pendapatan dari jasa simpan
- Jumlah gadai overdue
- Rata-rata jangka waktu gadai
- Tingkat pelunasan lebih cepat

---

## 🚀 Implementation Priority

### **Phase 1: Core Features (MVP)**
1. Pengajuan gadai dengan kalkulator
2. Approve/reject oleh admin
3. Pembayaran cicilan
4. Pelunasan lebih cepat
5. Tracking status & progress

### **Phase 2: Enhanced Features**
1. Perpanjangan jangka waktu
2. Notifikasi & reminder
3. Dashboard statistik
4. Laporan admin

### **Phase 3: Advanced Features**
1. Sistem lelang
2. Payment gateway integration
3. Auto-debit cicilan
4. Mobile app integration

---

## 📝 Notes

### **Pertimbangan Bisnis**
- Persentase taksiran (80-90%) bisa disesuaikan
- Biaya admin & jasa simpan bisa dikonfigurasi per periode
- Kebijakan denda keterlambatan perlu ditentukan
- Kebijakan lelang perlu ditentukan (berapa hari setelah jatuh tempo)

### **Technical Considerations**
- Gunakan database transaction untuk operasi kritis
- Implement locking mechanism untuk prevent race condition
- Cache harga emas untuk performa
- Optimize query untuk dashboard dengan banyak data

### **Compliance**
- Pastikan sesuai dengan regulasi gadai syariah
- Transparansi biaya & perhitungan
- Dokumentasi lengkap untuk audit

---

*Dokumentasi ini akan diperbarui sesuai dengan kebutuhan development dan feedback.*
