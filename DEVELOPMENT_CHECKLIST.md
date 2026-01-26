# Development Checklist - Tabung Emas
## Checklist Pengembangan Website Tabungan Emas

---

## ⚠️ Catatan Penting - UI/UX Design

### Framework & Approach
- ✅ **Bootstrap 5** sebagai framework CSS utama (menggunakan komponen bawaan)
- ✅ **Mobile-First Design** - Prioritas utama untuk smartphone
- ✅ **Referensi Desain:** https://sahabat.pegadaian.co.id/

### Responsive Requirements
- **Mobile (320px-768px):** PRIORITAS UTAMA
- **Tablet (768px-1024px):** Support
- **Desktop (1024px+):** Support

### Testing Mobile
- Test di berbagai ukuran smartphone sebelum deploy
- Pastikan semua fitur dapat diakses dengan mudah dari mobile
- Optimize untuk touch interaction

---

## 📋 Phase 1: Project Setup & Planning

### 1.1 Environment Setup
- [x] Install Laravel 11
- [ ] Setup database MySQL
- [ ] Konfigurasi .env file
- [ ] Setup Git repository
- [x] Install dependencies (composer install, npm install)
- [x] **Setup Bootstrap 5** (via CDN atau npm install bootstrap@5)
- [ ] Setup viewport meta tag untuk responsive

### 1.2 Database Design
- [ ] Buat ERD (Entity Relationship Diagram)
- [ ] Design database schema
- [ ] Review dan approve database design

### 1.3 Project Structure
- [ ] Setup folder structure
- [ ] Setup authentication system (Laravel Breeze/Jetstream)
- [ ] Setup middleware untuk role-based access

---

## 📋 Phase 2: Database & Models

### 2.1 Database Migrations
- [x] Create users table migration (add role, phone, address, status)
- [x] Create gold_prices table migration
- [x] Create gold_savings table migration
- [x] Create transactions table migration
- [ ] Create user_profiles table migration (jika diperlukan)
- [ ] Run migrations

### 2.2 Models & Relationships
- [x] Create User model dengan role
- [x] Create GoldPrice model
- [x] Create GoldSaving model
- [x] Create Transaction model
- [x] Setup relationships antar models
- [ ] Setup model factories untuk testing

### 2.3 Database Seeding
- [x] Create admin user seeder
- [ ] Create sample nasabah seeder
- [x] Create sample gold prices seeder
- [ ] Create sample transactions seeder

---

## 📋 Phase 3: Authentication & Authorization

### 3.1 Authentication System
- [x] Setup Laravel authentication (Laravel Breeze)
- [x] Implement registrasi nasabah
- [x] Implement login untuk nasabah dan admin
- [x] Implement logout
- [ ] Implement password reset
- [ ] Email verification (optional)

### 3.2 Authorization & Middleware
- [x] Create middleware untuk admin
- [x] Create middleware untuk nasabah
- [x] Setup route protection
- [ ] Test role-based access control

---

## 📋 Phase 4: Frontend - Public Pages (Pengunjung Umum)

### 4.1 Layout & Design
- [ ] Design homepage layout (referensi: sahabat.pegadaian.co.id)
- [ ] Setup master layout dengan navigation
- [ ] **Implement responsive design - MOBILE FIRST**
- [ ] **Setup Bootstrap 5** (CDN atau npm)
- [ ] Create responsive navigation (hamburger menu untuk mobile)
- [ ] Test responsive di berbagai device sizes

### 4.2 Public Pages
- [x] Homepage (beranda)
- [ ] About Us (tentang perusahaan)
- [ ] Services (layanan)
- [ ] Contact (kontak)
- [x] Harga Emas (untuk pengunjung umum) - di homepage
- [x] Register page (via Breeze)
- [x] Login page (via Breeze)

---

## 📋 Phase 5: Frontend - Nasabah Dashboard

### 5.1 Dashboard Layout
- [ ] Design dashboard layout untuk nasabah
- [ ] Setup sidebar navigation
- [ ] Implement header dengan user info
- [ ] Responsive dashboard design

### 5.2 Profile Management
- [x] Halaman view profile
- [x] Halaman edit profile
- [x] Form validation
- [x] Update profile functionality

### 5.3 Harga Emas Page
- [x] Display harga jual hari ini
- [x] Display harga beli hari ini
- [ ] Integrate chart library (Chart.js/ApexCharts)
- [ ] Display grafik pergerakan harga emas
- [ ] Filter grafik berdasarkan periode (hari/minggu/bulan)

### 5.4 Tabungan Emas Page
- [x] Display total tabungan emas
- [x] Display riwayat transaksi
- [x] Pagination untuk riwayat transaksi
- [ ] Filter transaksi (jika diperlukan)
- [ ] Detail transaksi

---

## 📋 Phase 6: Frontend - Admin Panel

### 6.1 Admin Dashboard Layout
- [ ] Design admin panel layout
- [ ] Setup admin navigation menu
- [ ] Implement admin header
- [ ] Responsive admin design

### 6.2 Database Nasabah Management
- [x] List semua nasabah (table dengan pagination)
- [ ] Search nasabah
- [x] View detail nasabah
- [ ] Edit data nasabah (jika diperlukan)
- [x] Activate/deactivate nasabah
- [ ] Export data nasabah (optional)

### 6.3 Harga Emas Management
- [x] Form untuk update harga emas
- [x] Input harga jual
- [x] Input harga beli
- [x] Validasi input
- [x] History update harga emas
- [ ] Grafik harga emas di admin panel

### 6.4 Transaksi Management
- [x] List semua transaksi pembelian
- [x] List semua transaksi penjualan
- [x] Filter transaksi berdasarkan:
  - [x] Type (pembelian/penjualan)
  - [ ] Periode (tanggal)
  - [ ] Nasabah
  - [x] Status
- [x] View detail transaksi
- [ ] Export laporan transaksi (optional)
- [x] Dashboard statistik transaksi

---

## 📋 Phase 7: Backend - Controllers & Logic

### 7.1 Public Controllers
- [x] HomeController
- [x] AuthController (register, login, logout) - via Breeze
- [ ] GoldPriceController (public view)

### 7.2 Nasabah Controllers
- [x] DashboardController
- [x] ProfileController
- [x] GoldPriceController (nasabah view)
- [x] GoldSavingController

### 7.3 Admin Controllers
- [x] AdminDashboardController
- [x] AdminUserController (manage nasabah)
- [x] AdminGoldPriceController (manage harga)
- [x] AdminTransactionController

### 7.4 Business Logic
- [ ] Logic untuk kalkulasi tabungan emas
- [ ] Logic untuk update harga emas
- [ ] Logic untuk transaksi
- [ ] Validation rules
- [ ] Error handling

---

## 📋 Phase 8: API & Services (Jika diperlukan)

### 8.1 Services
- [ ] GoldPriceService (untuk manage harga emas)
- [ ] TransactionService (untuk manage transaksi)
- [ ] UserService (untuk manage user)

### 8.2 API Endpoints (Optional)
- [ ] API untuk harga emas (jika perlu mobile app)
- [ ] API untuk transaksi
- [ ] API authentication

---

## 📋 Phase 9: Validation & Security

### 9.1 Form Validation
- [ ] Validasi form registrasi
- [ ] Validasi form login
- [ ] Validasi form profile
- [ ] Validasi form harga emas
- [ ] Validasi form transaksi

### 9.2 Security Implementation
- [ ] CSRF protection
- [ ] XSS protection
- [ ] SQL injection prevention
- [ ] Password hashing
- [ ] Rate limiting untuk login
- [ ] Secure session management

---

## 📋 Phase 10: Testing

### 10.1 Unit Testing
- [ ] Test models
- [ ] Test controllers
- [ ] Test services
- [ ] Code coverage minimal 70%

### 10.2 Feature Testing
- [ ] Test registrasi
- [ ] Test login/logout
- [ ] Test profile management
- [ ] Test harga emas display
- [ ] Test tabungan emas display
- [ ] Test admin functions

### 10.3 Integration Testing
- [ ] Test flow registrasi → login → dashboard
- [ ] Test admin update harga → nasabah view harga
- [ ] Test transaksi flow

### 10.4 Mobile Responsive Testing (PRIORITAS)
- [ ] Test di smartphone ukuran 320px (iPhone SE)
- [ ] Test di smartphone ukuran 375px (iPhone 12/13)
- [ ] Test di smartphone ukuran 414px (iPhone Pro Max)
- [ ] Test di tablet 768px (iPad)
- [ ] Test semua halaman responsive:
  - [ ] Homepage
  - [ ] Register/Login pages
  - [ ] Dashboard nasabah
  - [ ] Profile page
  - [ ] Harga emas page
  - [ ] Tabungan emas page
  - [ ] Admin panel
- [ ] Test touch interactions (tap, swipe)
- [ ] Test form input di mobile
- [ ] Test navigation menu di mobile
- [ ] Test grafik/chart di mobile
- [ ] Test loading time di mobile network (3G/4G)
- [ ] Test di browser mobile: Chrome Mobile, Safari Mobile

---

## 📋 Phase 11: UI/UX Enhancement

### 11.1 Design Polish
- [ ] Improve UI design consistency
- [ ] Add loading states
- [ ] Add success/error messages
- [ ] Improve form UX
- [ ] Add tooltips dan help text

### 11.2 User Experience
- [ ] Add breadcrumbs
- [ ] Improve navigation
- [ ] Add search functionality
- [ ] **Improve mobile responsiveness - PRIORITAS UTAMA**
  - [ ] Test semua halaman di smartphone
  - [ ] Optimize touch interactions
  - [ ] Improve mobile navigation
  - [ ] Optimize forms untuk mobile input
- [ ] Add animations (optional, jangan sampai mengganggu performa mobile)

---

## 📋 Phase 12: Performance Optimization

### 12.1 Database Optimization
- [ ] Add database indexes
- [ ] Optimize queries (N+1 problem)
- [ ] Implement query caching (jika perlu)

### 12.2 Application Optimization
- [ ] Optimize images
- [ ] Minify CSS/JS
- [ ] Implement caching (Laravel cache)
- [ ] Optimize page load time

---

## 📋 Phase 13: Documentation

### 13.1 Code Documentation
- [ ] Add code comments
- [ ] Document API endpoints (jika ada)
- [ ] Document database schema

### 13.2 User Documentation
- [ ] User manual untuk nasabah
- [ ] Admin manual
- [ ] Installation guide

---

## 📋 Phase 14: Deployment Preparation

### 14.1 Pre-Deployment
- [ ] Setup production environment
- [ ] Configure production .env
- [ ] Setup SSL certificate
- [ ] Setup domain & DNS

### 14.2 Deployment
- [ ] Deploy application
- [ ] Run migrations di production
- [ ] Setup database backup
- [ ] Test production environment

### 14.3 Post-Deployment
- [ ] Monitor application
- [ ] Setup error logging
- [ ] Setup monitoring tools
- [ ] Create admin account di production

---

## 📋 Phase 15: Maintenance & Support

### 15.1 Maintenance Plan
- [ ] Schedule regular backups
- [ ] Plan untuk updates
- [ ] Plan untuk bug fixes

### 15.2 Support
- [ ] Setup support channel
- [ ] Document common issues
- [ ] Create FAQ

---

## 📊 Progress Tracking

**Total Tasks:** 0 / 150+  
**Completion:** 0%

**Last Updated:** [Tanggal]

---

## 📝 Notes

[Catatan tambahan selama development]

---

*Checklist ini akan diperbarui sesuai dengan progress development.*
