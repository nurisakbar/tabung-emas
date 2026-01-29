# Development Checklist - Fitur Cicil Emas
## Checklist Pengembangan Fitur Cicilan Emas untuk Tabung Emas

---

## 📋 Overview Fitur

**Fitur Cicil Emas** memungkinkan nasabah untuk membeli emas secara cicilan dengan jangka waktu tertentu (3, 6, 12, 24 bulan) dan frekuensi pembayaran bulanan atau mingguan.

### Business Rules (Sementara - Dapat Disesuaikan)
- ✅ Harga emas: **Fixed price** saat pembuatan paket cicilan
- ✅ Alokasi emas: **Proporsional** per pembayaran (disarankan)
- ⚠️ Denda keterlambatan: **TBD** (To Be Determined)
- ⚠️ Biaya admin: **TBD** (To Be Determined)
- ⚠️ Down Payment (DP): **TBD** (Wajib/Opsional & Persentase)

---

## 📋 Phase 1: Database Design & Migration

### 1.1 Migration: gold_installment_plans
- [ ] Create migration file
- [ ] Add columns:
  - [ ] `id` (primary key)
  - [ ] `user_id` (foreign key ke users)
  - [ ] `gold_amount` (decimal 15,4)
  - [ ] `price_per_gram` (decimal 15,2) - harga saat pembuatan
  - [ ] `total_price` (decimal 15,2)
  - [ ] `down_payment` (decimal 15,2, nullable)
  - [ ] `installment_amount` (decimal 15,2) - cicilan per periode
  - [ ] `total_installments` (integer) - jumlah periode
  - [ ] `paid_installments` (integer, default 0)
  - [ ] `frequency` (enum: monthly, weekly)
  - [ ] `status` (enum: pending, active, completed, cancelled, overdue)
  - [ ] `start_date` (date)
  - [ ] `end_date` (date, nullable)
  - [ ] `notes` (text, nullable)
  - [ ] `timestamps`
- [ ] Add foreign key constraint
- [ ] Add indexes untuk performance
- [ ] Test migration (up & down)

### 1.2 Migration: installment_schedules
- [ ] Create migration file
- [ ] Add columns:
  - [ ] `id` (primary key)
  - [ ] `installment_plan_id` (foreign key ke gold_installment_plans)
  - [ ] `installment_number` (integer) - periode ke-berapa
  - [ ] `due_date` (date)
  - [ ] `amount` (decimal 15,2)
  - [ ] `paid_amount` (decimal 15,2, default 0)
  - [ ] `status` (enum: pending, paid, overdue, waived)
  - [ ] `paid_at` (datetime, nullable)
  - [ ] `late_fee` (decimal 15,2, default 0)
  - [ ] `notes` (text, nullable)
  - [ ] `timestamps`
- [ ] Add foreign key constraint dengan cascade delete
- [ ] Add indexes (installment_plan_id, due_date, status)
- [ ] Test migration (up & down)

### 1.3 Migration: installment_payments
- [ ] Create migration file
- [ ] Add columns:
  - [ ] `id` (primary key)
  - [ ] `installment_plan_id` (foreign key ke gold_installment_plans)
  - [ ] `schedule_id` (foreign key ke installment_schedules, nullable)
  - [ ] `payment_amount` (decimal 15,2)
  - [ ] `payment_date` (date)
  - [ ] `payment_method` (enum: transfer, cash, other)
  - [ ] `reference_number` (string, nullable) - no referensi pembayaran
  - [ ] `allocated_gold` (decimal 15,4) - emas yang dialokasikan
  - [ ] `status` (enum: pending, verified, rejected)
  - [ ] `verified_by` (foreign key ke users, nullable) - admin yang verifikasi
  - [ ] `verified_at` (datetime, nullable)
  - [ ] `notes` (text, nullable)
  - [ ] `timestamps`
- [ ] Add foreign key constraints
- [ ] Add indexes (installment_plan_id, payment_date, status)
- [ ] Test migration (up & down)

### 1.4 Run Migrations
- [ ] Run `php artisan migrate`
- [ ] Verify tables created successfully
- [ ] Check database structure

---

## 📋 Phase 2: Models & Relationships

### 2.1 Model: GoldInstallmentPlan
- [ ] Create model file `app/Models/GoldInstallmentPlan.php`
- [ ] Add fillable properties
- [ ] Add casts untuk decimal dan date
- [ ] Add relationship: `belongsTo(User::class)`
- [ ] Add relationship: `hasMany(InstallmentSchedule::class)`
- [ ] Add relationship: `hasMany(InstallmentPayment::class)`
- [ ] Add accessors/mutators jika diperlukan
- [ ] Add scope methods:
  - [ ] `scopeActive()` - paket yang aktif
  - [ ] `scopeCompleted()` - paket yang selesai
  - [ ] `scopeOverdue()` - paket yang overdue
- [ ] Add helper methods:
  - [ ] `calculateProgress()` - hitung progress pembayaran
  - [ ] `getRemainingAmount()` - sisa yang harus dibayar
  - [ ] `isCompleted()` - cek apakah sudah lunas
  - [ ] `canBeCancelled()` - cek apakah bisa dibatalkan

### 2.2 Model: InstallmentSchedule
- [ ] Create model file `app/Models/InstallmentSchedule.php`
- [ ] Add fillable properties
- [ ] Add casts untuk decimal dan date
- [ ] Add relationship: `belongsTo(GoldInstallmentPlan::class)`
- [ ] Add relationship: `hasMany(InstallmentPayment::class)`
- [ ] Add scope methods:
  - [ ] `scopePending()` - jadwal yang belum dibayar
  - [ ] `scopePaid()` - jadwal yang sudah dibayar
  - [ ] `scopeOverdue()` - jadwal yang overdue
  - [ ] `scopeDueSoon()` - jadwal yang akan jatuh tempo
- [ ] Add helper methods:
  - [ ] `isOverdue()` - cek apakah sudah overdue
  - [ ] `calculateLateFee()` - hitung denda jika ada
  - [ ] `markAsPaid()` - mark sebagai paid

### 2.3 Model: InstallmentPayment
- [ ] Create model file `app/Models/InstallmentPayment.php`
- [ ] Add fillable properties
- [ ] Add casts untuk decimal dan date
- [ ] Add relationship: `belongsTo(GoldInstallmentPlan::class)`
- [ ] Add relationship: `belongsTo(InstallmentSchedule::class, 'schedule_id')`
- [ ] Add relationship: `belongsTo(User::class, 'verified_by')`
- [ ] Add scope methods:
  - [ ] `scopePending()` - pembayaran pending
  - [ ] `scopeVerified()` - pembayaran verified
  - [ ] `scopeRejected()` - pembayaran rejected
- [ ] Add helper methods:
  - [ ] `verify()` - verifikasi pembayaran
  - [ ] `reject()` - reject pembayaran

### 2.4 Update Existing Models
- [ ] Update `User` model:
  - [ ] Add relationship: `hasMany(GoldInstallmentPlan::class)`
- [ ] Update `GoldSaving` model (jika perlu):
  - [ ] Add method untuk alokasi emas dari cicilan

---

## 📋 Phase 3: Services & Business Logic

### 3.1 Service: InstallmentPlanService
- [ ] Create service file `app/Services/InstallmentPlanService.php`
- [ ] Method: `createPlan($user, $data)` - buat paket cicilan baru
  - [ ] Validasi input
  - [ ] Hitung total harga
  - [ ] Hitung cicilan per periode
  - [ ] Generate jadwal pembayaran
  - [ ] Create plan & schedules
  - [ ] Handle DP jika ada
- [ ] Method: `calculateInstallmentAmount($totalPrice, $tenor, $adminFee)` - hitung cicilan
- [ ] Method: `generateSchedules($plan, $frequency, $startDate)` - generate jadwal
- [ ] Method: `processPayment($plan, $paymentData)` - proses pembayaran
  - [ ] Validasi pembayaran
  - [ ] Alokasi emas proporsional
  - [ ] Update schedule status
  - [ ] Update plan progress
  - [ ] Update GoldSaving
- [ ] Method: `processEarlyPayment($plan)` - pelunasan lebih cepat
- [ ] Method: `checkOverduePlans()` - cek paket yang overdue
- [ ] Method: `cancelPlan($plan, $reason)` - batalkan paket

### 3.2 Service: InstallmentCalculatorService
- [ ] Create service file `app/Services/InstallmentCalculatorService.php`
- [ ] Method: `calculate($goldAmount, $pricePerGram, $tenor, $frequency, $adminFee)` - kalkulasi cicilan
  - [ ] Return: total price, installment amount, schedule preview
- [ ] Method: `simulate($params)` - simulasi cicilan untuk preview

### 3.3 Update Transaction Model (Optional)
- [ ] Add transaction type: `installment` atau `installment_payment`
- [ ] Link transaction dengan installment_payment jika perlu

---

## 📋 Phase 4: Controllers - Nasabah

### 4.1 Controller: InstallmentPlanController (Nasabah)
- [ ] Create controller `app/Http/Controllers/Nasabah/InstallmentPlanController.php`
- [ ] Method: `index()` - list semua paket cicilan nasabah
  - [ ] Filter by status (active, completed, cancelled)
  - [ ] Pagination
  - [ ] Return view dengan data paket
- [ ] Method: `create()` - form buat paket cicilan baru
  - [ ] Get latest gold price
  - [ ] Return view dengan form
- [ ] Method: `store(Request $request)` - simpan paket cicilan baru
  - [ ] Validasi input
  - [ ] Call InstallmentPlanService
  - [ ] Redirect dengan success message
- [ ] Method: `show($id)` - detail paket cicilan
  - [ ] Get plan dengan schedules & payments
  - [ ] Calculate progress
  - [ ] Return view
- [ ] Method: `payment($id)` - form pembayaran cicilan
  - [ ] Get plan & next due schedule
  - [ ] Return view dengan form pembayaran
- [ ] Method: `processPayment(Request $request, $id)` - proses pembayaran
  - [ ] Validasi input
  - [ ] Call InstallmentPlanService
  - [ ] Redirect dengan success message
- [ ] Method: `earlyPayment($id)` - form pelunasan lebih cepat
  - [ ] Get plan & calculate remaining
  - [ ] Return view dengan form
- [ ] Method: `processEarlyPayment(Request $request, $id)` - proses pelunasan
  - [ ] Validasi
  - [ ] Call InstallmentPlanService
  - [ ] Redirect dengan success message
- [ ] Method: `cancel($id)` - form pembatalan
- [ ] Method: `processCancel(Request $request, $id)` - proses pembatalan

### 4.2 Request Validation Classes
- [ ] Create `app/Http/Requests/InstallmentPlanRequest.php`
  - [ ] Rules: gold_amount, tenor, frequency, down_payment (optional)
- [ ] Create `app/Http/Requests/InstallmentPaymentRequest.php`
  - [ ] Rules: payment_amount, payment_date, payment_method, reference_number

---

## 📋 Phase 5: Controllers - Admin

### 5.1 Controller: AdminInstallmentPlanController
- [ ] Create controller `app/Http/Controllers/Admin/InstallmentPlanController.php`
- [ ] Method: `index()` - list semua paket cicilan
  - [ ] Filter by status, user, date range
  - [ ] Search functionality
  - [ ] Pagination
  - [ ] Statistics (total active, overdue, completed)
- [ ] Method: `show($id)` - detail paket cicilan
  - [ ] Get plan dengan semua data
  - [ ] Payment history
  - [ ] Return view
- [ ] Method: `verifyPayment($paymentId)` - verifikasi pembayaran
  - [ ] Update payment status
  - [ ] Process allocation
  - [ ] Redirect dengan success
- [ ] Method: `rejectPayment($paymentId, Request $request)` - reject pembayaran
  - [ ] Update payment status
  - [ ] Add notes
  - [ ] Redirect
- [ ] Method: `export()` - export laporan (optional)

---

## 📋 Phase 6: Routes

### 6.1 Nasabah Routes
- [ ] Add routes di `routes/web.php`:
  - [ ] `GET /nasabah/installments` - list paket cicilan
  - [ ] `GET /nasabah/installments/create` - form buat paket
  - [ ] `POST /nasabah/installments` - simpan paket
  - [ ] `GET /nasabah/installments/{id}` - detail paket
  - [ ] `GET /nasabah/installments/{id}/payment` - form pembayaran
  - [ ] `POST /nasabah/installments/{id}/payment` - proses pembayaran
  - [ ] `GET /nasabah/installments/{id}/early-payment` - form pelunasan
  - [ ] `POST /nasabah/installments/{id}/early-payment` - proses pelunasan
  - [ ] `GET /nasabah/installments/{id}/cancel` - form pembatalan
  - [ ] `POST /nasabah/installments/{id}/cancel` - proses pembatalan

### 6.2 Admin Routes
- [ ] Add routes di `routes/web.php`:
  - [ ] `GET /admin/installments` - list semua paket
  - [ ] `GET /admin/installments/{id}` - detail paket
  - [ ] `POST /admin/installments/payments/{paymentId}/verify` - verifikasi pembayaran
  - [ ] `POST /admin/installments/payments/{paymentId}/reject` - reject pembayaran

---

## 📋 Phase 7: Views - Nasabah

### 7.1 Layout & Navigation
- [ ] Update navigation nasabah:
  - [ ] Add menu "Cicil Emas" di sidebar
  - [ ] Add badge untuk paket aktif/overdue

### 7.2 View: List Paket Cicilan
- [ ] Create `resources/views/nasabah/installments/index.blade.php`
- [ ] Display:
  - [ ] Card/tabel list paket cicilan
  - [ ] Status badge (active, completed, overdue)
  - [ ] Progress bar pembayaran
  - [ ] Total emas, sisa cicilan
  - [ ] Next payment date
  - [ ] Action buttons (detail, bayar, pelunasan)
- [ ] Filter by status
- [ ] Pagination
- [ ] **Mobile responsive design**

### 7.3 View: Form Buat Paket Cicilan
- [ ] Create `resources/views/nasabah/installments/create.blade.php`
- [ ] Display:
  - [ ] Current gold price
  - [ ] Form input: jumlah emas (gram)
  - [ ] Form input: tenor (3, 6, 12, 24 bulan)
  - [ ] Form input: frekuensi (bulanan/mingguan)
  - [ ] Form input: DP (optional)
  - [ ] Kalkulator preview:
    - [ ] Total harga
    - [ ] Cicilan per periode
    - [ ] Jadwal pembayaran preview
  - [ ] Summary card
- [ ] Real-time calculation dengan JavaScript
- [ ] Validation feedback
- [ ] **Mobile responsive design**

### 7.4 View: Detail Paket Cicilan
- [ ] Create `resources/views/nasabah/installments/show.blade.php`
- [ ] Display:
  - [ ] Info paket (gold amount, total price, status)
  - [ ] Progress bar & percentage
  - [ ] Tabel jadwal pembayaran:
    - [ ] Periode, due date, amount, status
    - [ ] Late fee jika ada
  - [ ] Payment history
  - [ ] Action buttons (bayar, pelunasan, cancel)
- [ ] **Mobile responsive design**

### 7.5 View: Form Pembayaran
- [ ] Create `resources/views/nasabah/installments/payment.blade.php`
- [ ] Display:
  - [ ] Info paket & schedule yang akan dibayar
  - [ ] Form: payment amount, payment date, payment method
  - [ ] Form: reference number (untuk transfer)
  - [ ] Form: notes
  - [ ] Summary: amount, late fee (jika ada), total
- [ ] Validation
- [ ] **Mobile responsive design**

### 7.6 View: Form Pelunasan Lebih Cepat
- [ ] Create `resources/views/nasabah/installments/early-payment.blade.php`
- [ ] Display:
  - [ ] Info paket
  - [ ] Sisa yang harus dibayar
  - [ ] Form pembayaran
  - [ ] Confirmation message
- [ ] **Mobile responsive design**

### 7.7 Update Dashboard Nasabah
- [ ] Add widget: "Paket Cicilan Aktif"
  - [ ] Count paket aktif
  - [ ] Next payment reminder
  - [ ] Link ke halaman cicilan

---

## 📋 Phase 8: Views - Admin

### 8.1 View: List Semua Paket Cicilan
- [ ] Create `resources/views/admin/installments/index.blade.php`
- [ ] Display:
  - [ ] Statistics cards (total, active, overdue, completed)
  - [ ] Tabel dengan filter:
    - [ ] Search by user name/email
    - [ ] Filter by status
    - [ ] Filter by date range
  - [ ] Columns: User, Gold Amount, Total Price, Progress, Status, Actions
  - [ ] Pagination
- [ ] **Mobile responsive design**

### 8.2 View: Detail Paket Cicilan (Admin)
- [ ] Create `resources/views/admin/installments/show.blade.php`
- [ ] Display:
  - [ ] Info lengkap paket
  - [ ] User info
  - [ ] Tabel jadwal pembayaran
  - [ ] Payment history dengan status
  - [ ] Action buttons (verify/reject payment)
- [ ] **Mobile responsive design**

### 8.3 View: Payment Verification
- [ ] Create modal atau separate page untuk verifikasi pembayaran
- [ ] Display payment details
- [ ] Form untuk approve/reject dengan notes

### 8.4 Update Admin Dashboard
- [ ] Add widget: "Cicilan Overview"
  - [ ] Total paket aktif
  - [ ] Paket overdue
  - [ ] Pembayaran pending verifikasi
  - [ ] Link ke halaman cicilan

---

## 📋 Phase 9: Business Logic Implementation

### 9.1 Kalkulasi Cicilan
- [ ] Implement formula:
  - [ ] Total Price = Gold Amount × Price Per Gram
  - [ ] Installment Amount = (Total Price + Admin Fee) / Tenor
  - [ ] Handle DP jika ada
- [ ] Test dengan berbagai skenario

### 9.2 Generate Jadwal Pembayaran
- [ ] Logic untuk monthly frequency
- [ ] Logic untuk weekly frequency
- [ ] Handle edge cases (bulan dengan 28/29/30/31 hari)
- [ ] Test jadwal generation

### 9.3 Alokasi Emas Proporsional
- [ ] Formula: `allocated_gold = (payment_amount / total_price) × gold_amount`
- [ ] Update GoldSaving setelah setiap pembayaran
- [ ] Handle rounding issues
- [ ] Test alokasi

### 9.4 Denda Keterlambatan (Jika Diimplementasikan)
- [ ] Logic untuk hitung denda
- [ ] Update schedule dengan late_fee
- [ ] Test dengan berbagai skenario overdue

### 9.5 Pelunasan Lebih Cepat
- [ ] Hitung sisa yang harus dibayar
- [ ] Mark semua schedule tersisa sebagai paid
- [ ] Alokasi sisa emas
- [ ] Update plan status ke completed

### 9.6 Check Overdue Plans
- [ ] Create command atau scheduled job
- [ ] Check schedules dengan due_date < today & status = pending
- [ ] Update status ke overdue
- [ ] Calculate late fee jika ada
- [ ] Send notification (optional)

---

## 📋 Phase 10: Validation & Security

### 10.1 Form Validation
- [ ] Validasi form buat paket:
  - [ ] Gold amount: required, numeric, min 0.0001
  - [ ] Tenor: required, in [3,6,12,24]
  - [ ] Frequency: required, in [monthly,weekly]
  - [ ] DP: optional, numeric, max = total_price
- [ ] Validasi form pembayaran:
  - [ ] Payment amount: required, numeric, min 0.01
  - [ ] Payment date: required, date
  - [ ] Payment method: required, in [transfer,cash,other]
  - [ ] Reference number: required if transfer
- [ ] Custom validation rules jika perlu

### 10.2 Authorization
- [ ] Middleware: hanya nasabah yang bisa akses nasabah routes
- [ ] Middleware: hanya admin yang bisa akses admin routes
- [ ] Check ownership: nasabah hanya bisa akses paket mereka sendiri
- [ ] Check status: validasi status sebelum action (bayar, cancel, dll)

### 10.3 Security
- [ ] CSRF protection (Laravel default)
- [ ] Input sanitization
- [ ] SQL injection prevention (Eloquent)
- [ ] XSS protection (Blade escaping)
- [ ] Rate limiting untuk payment (optional)

---

## 📋 Phase 11: Notifications & Reminders

### 11.1 Payment Reminders
- [ ] Create notification class untuk reminder
- [ ] Schedule job untuk kirim reminder (X hari sebelum due date)
- [ ] Email notification (optional)
- [ ] In-app notification (optional)

### 11.2 Status Notifications
- [ ] Notification saat paket dibuat
- [ ] Notification saat pembayaran berhasil
- [ ] Notification saat paket completed
- [ ] Notification saat overdue

### 11.3 Admin Notifications
- [ ] Notification saat ada pembayaran pending verifikasi
- [ ] Notification saat ada paket overdue

---

## 📋 Phase 12: Testing

### 12.1 Unit Tests
- [ ] Test models & relationships
- [ ] Test service methods:
  - [ ] `createPlan()`
  - [ ] `calculateInstallmentAmount()`
  - [ ] `generateSchedules()`
  - [ ] `processPayment()`
  - [ ] `processEarlyPayment()`
- [ ] Test helper methods di models

### 12.2 Feature Tests
- [ ] Test create installment plan flow
- [ ] Test payment flow
- [ ] Test early payment flow
- [ ] Test cancel plan flow
- [ ] Test admin verification flow
- [ ] Test authorization (nasabah hanya bisa akses paket mereka)

### 12.3 Integration Tests
- [ ] Test full flow: create plan → payment → completion
- [ ] Test alokasi emas ke GoldSaving
- [ ] Test update GoldSaving setelah pembayaran
- [ ] Test overdue detection

### 12.4 Manual Testing
- [ ] Test di browser (Chrome, Firefox, Safari)
- [ ] **Test mobile responsive** di berbagai device
- [ ] Test form validation
- [ ] Test error handling
- [ ] Test edge cases:
  - [ ] Pelunasan di tengah periode
  - [ ] Pembayaran lebih dari yang harus dibayar
  - [ ] Cancel paket yang sudah ada pembayaran
  - [ ] Multiple payments untuk satu schedule

---

## 📋 Phase 13: UI/UX Polish

### 13.1 Design Consistency
- [ ] Konsisten dengan design system existing
- [ ] Gunakan Bootstrap 5 components
- [ ] Color scheme sesuai tema emas
- [ ] Icon consistency

### 13.2 User Experience
- [ ] Loading states untuk async operations
- [ ] Success/error messages yang jelas
- [ ] Confirmation dialogs untuk actions penting
- [ ] Tooltips untuk help text
- [ ] Progress indicators
- [ ] Empty states (jika belum ada paket)

### 13.3 Mobile Optimization
- [ ] **PRIORITAS: Mobile-first design**
- [ ] Touch-friendly buttons
- [ ] Responsive tables (gunakan card di mobile)
- [ ] Optimize forms untuk mobile input
- [ ] Test di berbagai ukuran smartphone
- [ ] Fast loading time

### 13.4 Accessibility
- [ ] Semantic HTML
- [ ] ARIA labels jika perlu
- [ ] Keyboard navigation
- [ ] Color contrast

---

## 📋 Phase 14: Documentation

### 14.1 Code Documentation
- [ ] Add PHPDoc comments untuk methods
- [ ] Document complex business logic
- [ ] Document API endpoints (jika ada)

### 14.2 User Documentation
- [ ] User guide untuk nasabah: cara cicil emas
- [ ] FAQ: pertanyaan umum tentang cicilan
- [ ] Admin guide: cara verifikasi pembayaran

### 14.3 Technical Documentation
- [ ] Document database schema
- [ ] Document business rules
- [ ] Document calculation formulas

---

## 📋 Phase 15: Performance Optimization

### 15.1 Database Optimization
- [ ] Add indexes untuk queries yang sering digunakan
- [ ] Optimize N+1 queries dengan eager loading
- [ ] Query optimization untuk list pages

### 15.2 Application Optimization
- [ ] Cache gold price jika perlu
- [ ] Optimize view rendering
- [ ] Lazy load untuk data besar

---

## 📋 Phase 16: Deployment & Monitoring

### 16.1 Pre-Deployment
- [ ] Review semua checklist
- [ ] Test di staging environment
- [ ] Backup database
- [ ] Prepare migration scripts

### 16.2 Deployment
- [ ] Run migrations di production
- [ ] Verify tables created
- [ ] Test critical flows di production
- [ ] Monitor error logs

### 16.3 Post-Deployment
- [ ] Monitor application performance
- [ ] Monitor error rates
- [ ] Collect user feedback
- [ ] Plan untuk improvements

---

## 📊 Progress Tracking

**Total Tasks:** 0 / 200+  
**Completion:** 0%

**Last Updated:** [Tanggal]

### Status Summary
- ✅ Completed: 0
- 🟡 In Progress: 0
- ⏳ Pending: 0
- ❌ Blocked: 0

---

## 📝 Notes & Decisions

### Business Decisions Needed
- [ ] **Denda Keterlambatan**: Persentase atau flat? Berapa?
- [ ] **Biaya Admin**: Flat atau persentase? Berapa?
- [ ] **Down Payment**: Wajib atau opsional? Berapa persen?
- [ ] **Maksimal Paket Aktif**: Satu nasabah boleh berapa paket aktif?

### Technical Decisions
- [ ] Payment verification: Manual (admin) atau otomatis?
- [ ] Notification system: Email, SMS, atau in-app?
- [ ] Export laporan: Format apa? (PDF, Excel)

### Future Enhancements (Post-MVP)
- [ ] Payment gateway integration
- [ ] Auto-debit untuk pembayaran
- [ ] Kalkulator cicilan di homepage
- [ ] Referral program untuk cicilan
- [ ] Promo/diskon untuk cicilan

---

*Checklist ini akan diperbarui sesuai dengan progress development dan feedback.*
