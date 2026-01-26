# Dokumentasi Kebutuhan Sistem
## Tabung Emas - Website Perusahaan Layanan Tabungan Emas

---

## 1. Informasi Umum Proyek

**Nama Proyek:** Tabung Emas  
**Jenis Sistem:** Website Perusahaan Layanan Tabungan Emas  
**Tanggal Dibuat:** 2024  
**Versi Dokumen:** 1.0  
**Status:** Planning Phase

---

## 2. Deskripsi Sistem

Sistem website perusahaan yang bergerak di bidang layanan tabungan emas. Sistem ini memungkinkan nasabah untuk mendaftar, login, dan mengelola tabungan emas mereka secara online. Sistem juga menyediakan informasi harga emas real-time dengan grafik pergerakan harga, serta panel admin untuk mengelola data nasabah, harga emas, dan transaksi.

---

## 3. Tujuan Sistem

- [x] Menyediakan platform online untuk layanan tabungan emas
- [x] Memungkinkan nasabah untuk mendaftar dan mengakses akun mereka
- [x] Menampilkan informasi harga emas real-time (harga jual, harga beli, dan grafik)
- [x] Menyediakan dashboard nasabah untuk melihat informasi tabungan emas
- [x] Menyediakan panel admin untuk mengelola sistem, data nasabah, dan transaksi

---

## 4. Stakeholder

| Nama | Peran | Kontak |
|------|--------|---------|
|      |        |         |

---

## 5. Functional Requirements (Kebutuhan Fungsional)

### 5.1 Role dan Akses Sistem

#### 5.1.1 Pengunjung Umum (Guest)
- [ ] Dapat melihat halaman beranda website
- [ ] Dapat melihat informasi perusahaan
- [ ] Dapat melihat informasi layanan
- [ ] Dapat melihat harga emas hari ini (tanpa login)
- [ ] Dapat mengakses halaman registrasi
- [ ] Dapat mengakses halaman login

#### 5.1.2 Nasabah (Customer)
- [ ] Dapat melakukan registrasi akun baru
- [ ] Dapat melakukan login ke sistem
- [ ] Dapat melihat dan mengedit profil pribadi
- [ ] Dapat melihat informasi harga emas hari ini:
  - [ ] Harga jual emas
  - [ ] Harga beli emas
  - [ ] Grafik pergerakan harga emas (naik/turun)
- [ ] Dapat melihat informasi tabungan emas:
  - [ ] Total tabungan emas
  - [ ] Riwayat transaksi tabungan
  - [ ] Detail tabungan per periode
- [ ] Dapat melakukan logout

#### 5.1.3 Admin
- [ ] Dapat login ke panel admin
- [ ] Dapat melihat dan mengelola database nasabah:
  - [ ] Daftar semua nasabah
  - [ ] Detail informasi nasabah
  - [ ] Edit data nasabah (jika diperlukan)
  - [ ] Nonaktifkan/aktifkan akun nasabah
- [ ] Dapat mengupdate informasi harga emas:
  - [ ] Update harga jual emas
  - [ ] Update harga beli emas
  - [ ] Input data harga harian untuk grafik
- [ ] Dapat melihat transaksi:
  - [ ] Daftar semua transaksi pembelian emas
  - [ ] Daftar semua transaksi penjualan emas
  - [ ] Filter transaksi berdasarkan periode
  - [ ] Detail transaksi per nasabah
- [ ] Dapat melihat dashboard statistik

### 5.2 Fitur Tambahan (Future Enhancement)
- [ ] Notifikasi email untuk transaksi
- [ ] Export laporan transaksi (PDF/Excel)
- [ ] Chat support
- [ ] Mobile app (Android/iOS)

---

## 6. Non-Functional Requirements (Kebutuhan Non-Fungsional)

### 6.1 Performance (Kinerja)
- [ ] Response time < 2 detik untuk halaman umum
- [ ] Response time < 3 detik untuk dashboard
- [ ] Support minimal 100 concurrent users
- [ ] Database query optimization
- [ ] Image optimization untuk fast loading

### 6.2 Security (Keamanan)
- [x] Laravel authentication system
- [x] Password hashing (bcrypt)
- [x] CSRF protection
- [x] XSS protection
- [x] SQL injection prevention (Eloquent ORM)
- [ ] Rate limiting untuk login attempts
- [ ] Secure session management
- [ ] HTTPS/SSL implementation
- [ ] Input validation & sanitization

### 6.3 Scalability (Skalabilitas)
- [ ] Support untuk 1000+ nasabah
- [ ] Database indexing untuk performa
- [ ] Caching mechanism (Laravel cache)
- [ ] Optimized database queries

### 6.4 Usability (Kemudahan Penggunaan)
- [x] User-friendly interface
- [x] **Responsive design - PRIORITAS UTAMA untuk smartphone**
  - [ ] Mobile-first design approach
  - [ ] Breakpoints: Mobile (320px-768px), Tablet (768px-1024px), Desktop (1024px+)
  - [ ] Touch-friendly interface (button size, spacing)
  - [ ] Optimized untuk akses dari smartphone
- [ ] Intuitive navigation
- [ ] Clear error messages
- [ ] Loading indicators
- [ ] Help text dan tooltips
- [ ] Bahasa Indonesia (primary language)
- [ ] **Referensi desain:** Clean layout seperti sahabat.pegadaian.co.id

### 6.5 Reliability (Keandalan)
- [ ] Uptime: 99%+
- [ ] Daily database backup
- [ ] Error logging (Laravel log)
- [ ] Error handling dengan try-catch
- [ ] Transaction rollback untuk data integrity

---

## 7. Teknologi yang Digunakan

### 7.1 Frontend
- [x] Laravel Blade Templates
- [x] HTML5, CSS3, JavaScript
- [x] **Bootstrap 5** (framework CSS utama - responsive design)
- [x] Chart.js / ApexCharts (untuk grafik harga emas)
- [x] jQuery (optional, untuk interaktivitas)
- [x] **Mobile-first approach** (prioritas untuk smartphone)
- [x] **Referensi desain:** https://sahabat.pegadaian.co.id/

### 7.2 Backend
- [x] Laravel 11 (PHP Framework)
- [x] PHP 8.2+
- [x] Laravel Authentication (Breeze/Jetstream)
- [x] Laravel Eloquent ORM

### 7.3 Database
- [x] MySQL 8.0+
- [x] Database migrations
- [x] Database seeding untuk data awal

### 7.4 Infrastructure
- [ ] Web server: Apache/Nginx
- [ ] PHP-FPM
- [ ] SSL Certificate (HTTPS)
- [ ] Domain & Hosting

---

## 8. User Stories

### 8.1 Sebagai Pengunjung Umum
- Sebagai pengunjung umum, saya ingin melihat informasi perusahaan dan layanan agar saya dapat memahami produk yang ditawarkan
- Sebagai pengunjung umum, saya ingin melihat harga emas hari ini agar saya dapat mengetahui harga terkini sebelum mendaftar
- Sebagai pengunjung umum, saya ingin dapat mendaftar akun baru agar saya dapat menjadi nasabah dan menggunakan layanan tabungan emas

### 8.2 Sebagai Nasabah
- Sebagai nasabah, saya ingin dapat login ke sistem agar saya dapat mengakses akun saya
- Sebagai nasabah, saya ingin dapat melihat dan mengedit profil saya agar data saya selalu up-to-date
- Sebagai nasabah, saya ingin melihat harga emas hari ini (jual dan beli) agar saya dapat membuat keputusan investasi yang tepat
- Sebagai nasabah, saya ingin melihat grafik pergerakan harga emas agar saya dapat menganalisis tren harga
- Sebagai nasabah, saya ingin melihat informasi tabungan emas saya agar saya tahu berapa total emas yang sudah saya tabung
- Sebagai nasabah, saya ingin melihat riwayat transaksi tabungan saya agar saya dapat melacak semua aktivitas tabungan

### 8.3 Sebagai Admin
- Sebagai admin, saya ingin dapat login ke panel admin agar saya dapat mengelola sistem
- Sebagai admin, saya ingin melihat database semua nasabah agar saya dapat mengelola data nasabah
- Sebagai admin, saya ingin dapat mengupdate harga emas (jual dan beli) agar informasi harga selalu akurat
- Sebagai admin, saya ingin melihat semua transaksi pembelian emas agar saya dapat memantau aktivitas nasabah
- Sebagai admin, saya ingin melihat semua transaksi penjualan emas agar saya dapat memantau aktivitas nasabah

---

## 9. Use Cases

### Use Case 1: Registrasi Nasabah Baru
**Actor:** Pengunjung Umum  
**Precondition:** Pengunjung belum memiliki akun  
**Main Flow:**
1. Pengunjung mengakses halaman registrasi
2. Pengunjung mengisi form registrasi (nama, email, password, dll)
3. Sistem memvalidasi data
4. Sistem menyimpan data nasabah baru
5. Sistem mengirim konfirmasi (email/notifikasi)
6. Nasabah dapat login

**Postcondition:** Nasabah baru terdaftar dan dapat login

### Use Case 2: Login Nasabah
**Actor:** Nasabah  
**Precondition:** Nasabah sudah terdaftar  
**Main Flow:**
1. Nasabah mengakses halaman login
2. Nasabah memasukkan email dan password
3. Sistem memverifikasi kredensial
4. Sistem mengarahkan ke dashboard nasabah

**Postcondition:** Nasabah berhasil login dan dapat mengakses fitur

### Use Case 3: Melihat Harga Emas
**Actor:** Nasabah / Pengunjung Umum  
**Precondition:** -  
**Main Flow:**
1. User mengakses halaman harga emas
2. Sistem menampilkan harga jual dan harga beli hari ini
3. Sistem menampilkan grafik pergerakan harga emas
4. User dapat melihat detail pergerakan harga

**Postcondition:** User melihat informasi harga emas terkini

### Use Case 4: Melihat Tabungan Emas
**Actor:** Nasabah  
**Precondition:** Nasabah sudah login  
**Main Flow:**
1. Nasabah login ke sistem
2. Nasabah mengakses menu "Tabungan Emas"
3. Sistem menampilkan total tabungan emas nasabah
4. Sistem menampilkan riwayat transaksi tabungan

**Postcondition:** Nasabah melihat informasi tabungan emas mereka

### Use Case 5: Admin Update Harga Emas
**Actor:** Admin  
**Precondition:** Admin sudah login  
**Main Flow:**
1. Admin login ke panel admin
2. Admin mengakses menu "Kelola Harga Emas"
3. Admin memasukkan harga jual dan harga beli baru
4. Sistem memvalidasi input
5. Sistem menyimpan harga emas baru
6. Sistem memperbarui grafik harga

**Postcondition:** Harga emas terupdate di sistem

### Use Case 6: Admin Melihat Transaksi
**Actor:** Admin  
**Precondition:** Admin sudah login  
**Main Flow:**
1. Admin login ke panel admin
2. Admin mengakses menu "Transaksi"
3. Admin memilih filter (pembelian/penjualan, periode)
4. Sistem menampilkan daftar transaksi sesuai filter
5. Admin dapat melihat detail transaksi

**Postcondition:** Admin melihat informasi transaksi nasabah

---

## 10. Data Requirements

### 10.1 Data Entities & Database Schema

#### 10.1.1 Tabel: users
- id (Primary Key)
- name (Nama lengkap)
- email (Unique)
- email_verified_at
- password (Hashed)
- role (enum: 'admin', 'nasabah')
- phone (No. telepon)
- address (Alamat)
- status (enum: 'active', 'inactive')
- remember_token
- timestamps (created_at, updated_at)

#### 10.1.2 Tabel: gold_prices
- id (Primary Key)
- date (Tanggal)
- buy_price (Harga beli)
- sell_price (Harga jual)
- created_by (Admin ID)
- timestamps

#### 10.1.3 Tabel: gold_savings
- id (Primary Key)
- user_id (Foreign Key ke users)
- total_gold (Total emas dalam gram)
- last_transaction_date
- timestamps

#### 10.1.4 Tabel: transactions
- id (Primary Key)
- user_id (Foreign Key ke users)
- transaction_type (enum: 'buy', 'sell')
- gold_amount (Jumlah emas dalam gram)
- price_per_gram (Harga per gram saat transaksi)
- total_price (Total harga transaksi)
- transaction_date
- status (enum: 'pending', 'completed', 'cancelled')
- notes (Catatan transaksi)
- timestamps

#### 10.1.5 Tabel: user_profiles (Optional - jika perlu data tambahan)
- id (Primary Key)
- user_id (Foreign Key ke users)
- birth_date
- id_card_number (NIK)
- bank_account
- timestamps

### 10.2 Data Storage
- [ ] Database backup harian
- [ ] Storage untuk file upload (jika ada)
- [ ] Logging untuk audit trail

---

## 11. Integration Requirements

- [ ] Integration dengan sistem eksternal 1
- [ ] Integration dengan sistem eksternal 2
- [ ] API requirements

---

## 12. Constraints (Batasan)

### 12.1 Technical Constraints
- [ ] Menggunakan Laravel 11 (PHP framework)
- [ ] Database MySQL
- [ ] Web-based application (bukan mobile native)
- [ ] Browser compatibility (modern browsers)

### 12.2 Time Constraints
- [ ] Development timeline: 7-10 minggu
- [ ] Deadline sesuai kesepakatan

### 12.3 Budget Constraints
- [ ] Hosting dan domain costs
- [ ] SSL certificate costs
- [ ] Development resources

### 12.4 Regulatory Constraints
- [ ] Compliance dengan regulasi keuangan (jika berlaku)
- [ ] Data privacy (perlindungan data nasabah)

---

## 13. Assumptions (Asumsi)

- [ ] Server hosting sudah tersedia atau akan disediakan
- [ ] Domain sudah tersedia atau akan didaftarkan
- [ ] Data harga emas akan diinput manual oleh admin (belum ada API eksternal)
- [ ] Transaksi emas dilakukan offline, sistem hanya untuk tracking
- [ ] Nasabah memiliki akses internet untuk menggunakan sistem
- [ ] Admin memiliki pengetahuan dasar untuk mengelola sistem

---

## 14. Risks (Risiko)

| Risiko | Dampak | Probabilitas | Mitigasi |
|--------|--------|--------------|----------|
| Data loss | High | Low | Daily backup, database replication |
| Security breach | High | Medium | Implement security best practices, regular updates |
| Server downtime | Medium | Low | Reliable hosting, monitoring tools |
| Performance issues | Medium | Medium | Optimization, caching, load testing |
| Scope creep | Medium | Medium | Clear requirements, change management |
| Timeline delay | Low | Medium | Proper planning, buffer time |

---

## 15. Timeline & Milestones

| Milestone | Target Date | Status |
|-----------|-------------|--------|
|           |             |        |

---

## 16. Testing Requirements

### 16.1 Unit Testing
- [ ] Test models (User, GoldPrice, Transaction, GoldSaving)
- [ ] Test controllers
- [ ] Test services
- [ ] Test validation rules
- [ ] Code coverage minimal 70%

### 16.2 Integration Testing
- [ ] Test authentication flow (register → login → logout)
- [ ] Test harga emas update flow (admin update → nasabah view)
- [ ] Test transaksi flow
- [ ] Test role-based access control
- [ ] Test database operations

### 16.3 Feature Testing
- [ ] Test semua fitur nasabah
- [ ] Test semua fitur admin
- [ ] **Test responsive design di berbagai device - PRIORITAS MOBILE**
  - [ ] Test di smartphone (320px, 375px, 414px)
  - [ ] Test di tablet (768px)
  - [ ] Test di desktop (1024px+)
  - [ ] Test touch interactions
  - [ ] Test form input di mobile
  - [ ] Test navigation di mobile
- [ ] Test browser compatibility (Chrome, Firefox, Safari, Edge)
- [ ] Test di mobile browsers (Chrome Mobile, Safari Mobile)

### 16.4 User Acceptance Testing (UAT)
- [ ] UAT dengan stakeholder
- [ ] Test semua user stories
- [ ] Test semua use cases
- [ ] Feedback dan improvement

---

## 17. Deployment Requirements

### 17.1 Deployment Environment
- [ ] Production server setup (Apache/Nginx)
- [ ] PHP 8.2+ installation
- [ ] MySQL 8.0+ installation
- [ ] SSL certificate setup
- [ ] Domain configuration

### 17.2 Deployment Process
- [ ] Code deployment via Git
- [ ] Environment configuration (.env)
- [ ] Run migrations di production
- [ ] Run composer install
- [ ] Run npm build (jika ada)
- [ ] Set proper file permissions
- [ ] Clear cache

### 17.3 Rollback Plan
- [ ] Backup database sebelum deploy
- [ ] Version control untuk rollback
- [ ] Rollback procedure documentation

---

## 18. Maintenance & Support

### 18.1 Maintenance Schedule
- [ ] Daily database backup
- [ ] Weekly system health check
- [ ] Monthly security updates
- [ ] Regular Laravel updates

### 18.2 Support
- [ ] Support hours: Senin-Jumat, 09:00-17:00 WIB
- [ ] Support channel: Email/WhatsApp
- [ ] Response time: < 24 jam

### 18.3 Documentation
- [ ] User manual untuk nasabah
- [ ] Admin manual
- [ ] Technical documentation
- [ ] API documentation (jika ada)

---

## 19. Change Log

| Tanggal | Versi | Perubahan | Oleh |
|---------|-------|-----------|------|
|         |       |           |      |

---

## 20. Notes (Catatan)

[Catatan tambahan atau informasi penting lainnya]

---

## 21. References (Referensi)

- [ ] Reference 1
- [ ] Reference 2

---

## 22. UI/UX Design Guidelines

### 22.1 Design Framework
- [x] **Bootstrap 5** sebagai framework CSS utama
- [x] Menggunakan komponen Bootstrap bawaan (tidak perlu custom CSS kompleks)
- [x] **Mobile-first approach** - desain dimulai dari mobile, kemudian scale up ke desktop
- [x] **Referensi desain:** https://sahabat.pegadaian.co.id/

### 22.2 Responsive Design Requirements
- [ ] **Prioritas Utama: Smartphone (Mobile)**
  - Breakpoint: 320px - 768px
  - Touch-friendly buttons (minimal 44x44px)
  - Adequate spacing untuk touch interaction
  - Readable font size (minimal 16px)
  - Hamburger menu untuk navigation
  - Bottom navigation untuk fitur utama (optional)

- [ ] **Tablet Support**
  - Breakpoint: 768px - 1024px
  - Optimized layout untuk landscape/portrait
  - Sidebar navigation dapat digunakan

- [ ] **Desktop Support**
  - Breakpoint: 1024px+
  - Full navigation menu
  - Multi-column layouts
  - Hover effects

### 22.3 Design Elements (Referensi Sahabat Pegadaian)
- [ ] **Clean & Simple Layout**
  - Minimal clutter
  - Clear visual hierarchy
  - Ample white space

- [ ] **Color Scheme**
  - Professional color palette
  - Good contrast untuk readability
  - Consistent brand colors

- [ ] **Typography**
  - Clear, readable fonts
  - Appropriate font sizes untuk mobile
  - Good line height untuk readability

- [ ] **Navigation**
  - Simple, intuitive navigation
  - Clear menu structure
  - Easy access ke fitur utama

- [ ] **Forms**
  - Large input fields untuk mobile
  - Clear labels
  - Inline validation
  - Error messages yang jelas

- [ ] **Cards & Components**
  - Card-based layout untuk informasi
  - Clear visual separation
  - Touch-friendly interactive elements

### 22.4 Bootstrap Components yang Akan Digunakan
- [ ] Navbar (responsive navigation)
- [ ] Cards (untuk informasi produk, harga emas)
- [ ] Forms (registrasi, login, profile)
- [ ] Tables (untuk data nasabah, transaksi)
- [ ] Modals (untuk konfirmasi, detail)
- [ ] Alerts (untuk notifikasi, error messages)
- [ ] Buttons (primary, secondary, danger)
- [ ] Badges (untuk status, labels)
- [ ] Progress bars (jika diperlukan)
- [ ] Dropdowns (untuk filter, actions)

### 22.5 Mobile Optimization Checklist
- [ ] Test di berbagai ukuran smartphone (320px, 375px, 414px, 768px)
- [ ] Optimize images untuk mobile (lazy loading, responsive images)
- [ ] Minimize HTTP requests
- [ ] Fast page load time (< 3 detik di mobile)
- [ ] Touch gestures support (swipe, tap)
- [ ] Prevent zoom pada input fields (viewport meta tag)
- [ ] Sticky navigation untuk easy access
- [ ] Bottom action buttons untuk primary actions

---

## 23. Development Checklist (Checklist Pengembangan)

### Phase 1: Project Setup & Planning
- [ ] **1.1 Environment Setup**
  - [ ] Install Laravel 11
  - [ ] Setup database MySQL
  - [ ] Konfigurasi .env file
  - [ ] Setup Git repository
  - [ ] Install dependencies (composer install, npm install)

- [ ] **1.2 Database Design**
  - [ ] Buat ERD (Entity Relationship Diagram)
  - [ ] Design database schema
  - [ ] Review dan approve database design

- [ ] **1.3 Project Structure**
  - [ ] Setup folder structure
  - [ ] Setup authentication system (Laravel Breeze/Jetstream)
  - [ ] Setup middleware untuk role-based access

### Phase 2: Database & Models
- [ ] **2.1 Database Migrations**
  - [ ] Create users table migration
  - [ ] Create gold_prices table migration
  - [ ] Create gold_savings table migration
  - [ ] Create transactions table migration
  - [ ] Create user_profiles table migration (jika diperlukan)
  - [ ] Run migrations

- [ ] **2.2 Models & Relationships**
  - [ ] Create User model dengan role
  - [ ] Create GoldPrice model
  - [ ] Create GoldSaving model
  - [ ] Create Transaction model
  - [ ] Setup relationships antar models
  - [ ] Setup model factories untuk testing

- [ ] **2.3 Database Seeding**
  - [ ] Create admin user seeder
  - [ ] Create sample nasabah seeder
  - [ ] Create sample gold prices seeder
  - [ ] Create sample transactions seeder

### Phase 3: Authentication & Authorization
- [ ] **3.1 Authentication System**
  - [ ] Setup Laravel authentication
  - [ ] Implement registrasi nasabah
  - [ ] Implement login untuk nasabah dan admin
  - [ ] Implement logout
  - [ ] Implement password reset
  - [ ] Email verification (optional)

- [ ] **3.2 Authorization & Middleware**
  - [ ] Create middleware untuk admin
  - [ ] Create middleware untuk nasabah
  - [ ] Setup route protection
  - [ ] Test role-based access control

### Phase 4: Frontend - Public Pages (Pengunjung Umum)
- [ ] **4.1 Layout & Design**
  - [ ] Design homepage layout (referensi: sahabat.pegadaian.co.id)
  - [ ] Setup master layout dengan navigation
  - [ ] **Implement responsive design - MOBILE FIRST**
  - [ ] **Setup Bootstrap 5** (CDN atau npm)
  - [ ] Create responsive navigation (hamburger menu untuk mobile)
  - [ ] Test responsive di berbagai device sizes

- [ ] **4.2 Public Pages**
  - [ ] Homepage (beranda)
  - [ ] About Us (tentang perusahaan)
  - [ ] Services (layanan)
  - [ ] Contact (kontak)
  - [ ] Harga Emas (untuk pengunjung umum)
  - [ ] Register page
  - [ ] Login page

### Phase 5: Frontend - Nasabah Dashboard
- [ ] **5.1 Dashboard Layout**
  - [ ] Design dashboard layout untuk nasabah
  - [ ] Setup sidebar navigation
  - [ ] Implement header dengan user info
  - [ ] Responsive dashboard design

- [ ] **5.2 Profile Management**
  - [ ] Halaman view profile
  - [ ] Halaman edit profile
  - [ ] Form validation
  - [ ] Update profile functionality

- [ ] **5.3 Harga Emas Page**
  - [ ] Display harga jual hari ini
  - [ ] Display harga beli hari ini
  - [ ] Integrate chart library (Chart.js/ApexCharts)
  - [ ] Display grafik pergerakan harga emas
  - [ ] Filter grafik berdasarkan periode (hari/minggu/bulan)

- [ ] **5.4 Tabungan Emas Page**
  - [ ] Display total tabungan emas
  - [ ] Display riwayat transaksi
  - [ ] Pagination untuk riwayat transaksi
  - [ ] Filter transaksi (jika diperlukan)
  - [ ] Detail transaksi

### Phase 6: Frontend - Admin Panel
- [ ] **6.1 Admin Dashboard Layout**
  - [ ] Design admin panel layout
  - [ ] Setup admin navigation menu
  - [ ] Implement admin header
  - [ ] Responsive admin design

- [ ] **6.2 Database Nasabah Management**
  - [ ] List semua nasabah (table dengan pagination)
  - [ ] Search nasabah
  - [ ] View detail nasabah
  - [ ] Edit data nasabah (jika diperlukan)
  - [ ] Activate/deactivate nasabah
  - [ ] Export data nasabah (optional)

- [ ] **6.3 Harga Emas Management**
  - [ ] Form untuk update harga emas
  - [ ] Input harga jual
  - [ ] Input harga beli
  - [ ] Validasi input
  - [ ] History update harga emas
  - [ ] Grafik harga emas di admin panel

- [ ] **6.4 Transaksi Management**
  - [ ] List semua transaksi pembelian
  - [ ] List semua transaksi penjualan
  - [ ] Filter transaksi berdasarkan:
    - [ ] Type (pembelian/penjualan)
    - [ ] Periode (tanggal)
    - [ ] Nasabah
    - [ ] Status
  - [ ] View detail transaksi
  - [ ] Export laporan transaksi (optional)
  - [ ] Dashboard statistik transaksi

### Phase 7: Backend - Controllers & Logic
- [ ] **7.1 Public Controllers**
  - [ ] HomeController
  - [ ] AuthController (register, login, logout)
  - [ ] GoldPriceController (public view)

- [ ] **7.2 Nasabah Controllers**
  - [ ] DashboardController
  - [ ] ProfileController
  - [ ] GoldPriceController (nasabah view)
  - [ ] GoldSavingController

- [ ] **7.3 Admin Controllers**
  - [ ] AdminDashboardController
  - [ ] AdminUserController (manage nasabah)
  - [ ] AdminGoldPriceController (manage harga)
  - [ ] AdminTransactionController

- [ ] **7.4 Business Logic**
  - [ ] Logic untuk kalkulasi tabungan emas
  - [ ] Logic untuk update harga emas
  - [ ] Logic untuk transaksi
  - [ ] Validation rules
  - [ ] Error handling

### Phase 8: API & Services (Jika diperlukan)
- [ ] **8.1 Services**
  - [ ] GoldPriceService (untuk manage harga emas)
  - [ ] TransactionService (untuk manage transaksi)
  - [ ] UserService (untuk manage user)

- [ ] **8.2 API Endpoints (Optional)**
  - [ ] API untuk harga emas (jika perlu mobile app)
  - [ ] API untuk transaksi
  - [ ] API authentication

### Phase 9: Validation & Security
- [ ] **9.1 Form Validation**
  - [ ] Validasi form registrasi
  - [ ] Validasi form login
  - [ ] Validasi form profile
  - [ ] Validasi form harga emas
  - [ ] Validasi form transaksi

- [ ] **9.2 Security Implementation**
  - [ ] CSRF protection
  - [ ] XSS protection
  - [ ] SQL injection prevention
  - [ ] Password hashing
  - [ ] Rate limiting untuk login
  - [ ] Secure session management

### Phase 10: Testing
- [ ] **10.1 Unit Testing**
  - [ ] Test models
  - [ ] Test controllers
  - [ ] Test services
  - [ ] Code coverage minimal 70%

- [ ] **10.2 Feature Testing**
  - [ ] Test registrasi
  - [ ] Test login/logout
  - [ ] Test profile management
  - [ ] Test harga emas display
  - [ ] Test tabungan emas display
  - [ ] Test admin functions

- [ ] **10.3 Integration Testing**
  - [ ] Test flow registrasi → login → dashboard
  - [ ] Test admin update harga → nasabah view harga
  - [ ] Test transaksi flow

### Phase 11: UI/UX Enhancement
- [ ] **11.1 Design Polish**
  - [ ] Improve UI design consistency
  - [ ] Add loading states
  - [ ] Add success/error messages
  - [ ] Improve form UX
  - [ ] Add tooltips dan help text

- [ ] **11.2 User Experience**
  - [ ] Add breadcrumbs
  - [ ] Improve navigation
  - [ ] Add search functionality
  - [ ] **Improve mobile responsiveness - PRIORITAS UTAMA**
    - [ ] Test semua halaman di smartphone
    - [ ] Optimize touch interactions
    - [ ] Improve mobile navigation
    - [ ] Optimize forms untuk mobile input
  - [ ] Add animations (optional, jangan sampai mengganggu performa mobile)

### Phase 12: Performance Optimization
- [ ] **12.1 Database Optimization**
  - [ ] Add database indexes
  - [ ] Optimize queries (N+1 problem)
  - [ ] Implement query caching (jika perlu)

- [ ] **12.2 Application Optimization**
  - [ ] Optimize images
  - [ ] Minify CSS/JS
  - [ ] Implement caching (Laravel cache)
  - [ ] Optimize page load time

### Phase 13: Documentation
- [ ] **13.1 Code Documentation**
  - [ ] Add code comments
  - [ ] Document API endpoints (jika ada)
  - [ ] Document database schema

- [ ] **13.2 User Documentation**
  - [ ] User manual untuk nasabah
  - [ ] Admin manual
  - [ ] Installation guide

### Phase 14: Deployment Preparation
- [ ] **14.1 Pre-Deployment**
  - [ ] Setup production environment
  - [ ] Configure production .env
  - [ ] Setup SSL certificate
  - [ ] Setup domain & DNS

- [ ] **14.2 Deployment**
  - [ ] Deploy application
  - [ ] Run migrations di production
  - [ ] Setup database backup
  - [ ] Test production environment

- [ ] **14.3 Post-Deployment**
  - [ ] Monitor application
  - [ ] Setup error logging
  - [ ] Setup monitoring tools
  - [ ] Create admin account di production

### Phase 15: Maintenance & Support
- [ ] **15.1 Maintenance Plan**
  - [ ] Schedule regular backups
  - [ ] Plan untuk updates
  - [ ] Plan untuk bug fixes

- [ ] **15.2 Support**
  - [ ] Setup support channel
  - [ ] Document common issues
  - [ ] Create FAQ

---

## 24. Development Timeline (Estimasi)

| Phase | Task | Estimasi Waktu | Prioritas |
|-------|------|----------------|-----------|
| Phase 1 | Project Setup | 1-2 hari | High |
| Phase 2 | Database & Models | 2-3 hari | High |
| Phase 3 | Authentication | 2-3 hari | High |
| Phase 4 | Public Pages | 3-4 hari | High |
| Phase 5 | Nasabah Dashboard | 4-5 hari | High |
| Phase 6 | Admin Panel | 5-6 hari | High |
| Phase 7 | Backend Logic | 3-4 hari | High |
| Phase 8 | API & Services | 2-3 hari | Medium |
| Phase 9 | Validation & Security | 2-3 hari | High |
| Phase 10 | Testing | 3-4 hari | High |
| Phase 11 | UI/UX Enhancement | 2-3 hari | Medium |
| Phase 12 | Performance | 2-3 hari | Medium |
| Phase 13 | Documentation | 1-2 hari | Low |
| Phase 14 | Deployment | 2-3 hari | High |
| Phase 15 | Maintenance | Ongoing | Medium |

**Total Estimasi:** 35-50 hari kerja (7-10 minggu)

---

## 25. Priority Features (Prioritas Fitur)

### Must Have (P0 - Critical)
1. ✅ Authentication (Register, Login, Logout)
2. ✅ Role-based access (Guest, Nasabah, Admin)
3. ✅ Profile management untuk nasabah
4. ✅ Display harga emas (jual & beli)
5. ✅ Grafik harga emas
6. ✅ Tabungan emas page untuk nasabah
7. ✅ Admin: Manage nasabah
8. ✅ Admin: Update harga emas
9. ✅ Admin: View transaksi

### Should Have (P1 - Important)
1. ⚠️ Dashboard statistik untuk admin
2. ⚠️ Search & filter functionality
3. ⚠️ Export laporan (PDF/Excel)
4. ⚠️ Email notifications

### Nice to Have (P2 - Enhancement)
1. 📌 Mobile app
2. 📌 Chat support
3. 📌 Push notifications
4. 📌 Advanced analytics

---

*Dokumen ini akan diperbarui sesuai dengan perkembangan proyek.*
