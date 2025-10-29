# Küresel Etki Zinciri - Registration and Login Test Plan
# Global Impact Chain - Registration and Login Test Plan

## ✅ Project Requirements Verification

### User Types and Their Expected Behavior:

| User Type | Registration Process | Login Redirect |
|-----------|---------------------|----------------|
| **Consumer** | Basic registration (name, email, password) | Homepage (`/Küresel/`) |
| **Company** | Registration + company details | Company Dashboard (`/Küresel/?page=company`) |
| **Validator** | Registration + validator details | Validator Dashboard (`/Küresel/?page=validator`) |
| **Admin** | Pre-created or special registration | Admin Dashboard (`/Küresel/?page=admin`) |

## 🧪 Test Scenarios

### Test 1: Consumer Registration and Login

**Registration:**
1. Go to: `http://localhost/Küresel/?page=register`
2. Select user type: **👤 Tüketici**
3. Fill in:
   - First Name: Test
   - Last Name: Consumer
   - Email: consumer@test.com
   - Password: Test1234
4. Accept terms and privacy
5. Click "Hesap Oluştur"
6. ✅ Expected: Success message and redirect to login page

**Login:**
1. Go to: `http://localhost/Küresel/?page=login`
2. Enter credentials:
   - Email: consumer@test.com
   - Password: Test1234
3. Click "Giriş Yap"
4. ✅ Expected: Redirect to homepage (`/Küresel/`)

### Test 2: Company Registration and Login

**Registration:**
1. Go to: `http://localhost/Küresel/?page=register`
2. Select user type: **🏢 Şirket**
3. Fill in personal info:
   - First Name: Test
   - Last Name: Company
   - Email: company@test.com
   - Password: Test1234
4. Fill in company details (should appear dynamically):
   - Company Name: Test Gıda A.Ş.
   - Company Type: manufacturer
   - Industry Sector: food
5. Accept terms and privacy
6. Click "Hesap Oluştur"
7. ✅ Expected: Success message and redirect to login page

**Login:**
1. Go to: `http://localhost/Küresel/?page=login`
2. Enter credentials:
   - Email: company@test.com
   - Password: Test1234
3. Click "Giriş Yap"
4. ✅ Expected: Redirect to company dashboard (`/Küresel/?page=company`)

### Test 3: Validator Registration and Login

**Registration:**
1. Go to: `http://localhost/Küresel/?page=register`
2. Select user type: **✅ Validator**
3. Fill in personal info:
   - First Name: Test
   - Last Name: Validator
   - Email: validator@test.com
   - Password: Test1234
4. Fill in validator details (should appear dynamically):
   - Organization Name: Test Denetim Ltd.
   - Organization Type: independent
5. Accept terms and privacy
6. Click "Hesap Oluştur"
7. ✅ Expected: Success message and redirect to login page

**Login:**
1. Go to: `http://localhost/Küresel/?page=login`
2. Enter credentials:
   - Email: validator@test.com
   - Password: Test1234
3. Click "Giriş Yap"
4. ✅ Expected: Redirect to validator dashboard (`/Küresel/?page=validator`)

### Test 4: Admin Login

**Login:**
1. Go to: `http://localhost/Küresel/?page=login`
2. Use demo admin account:
   - Email: admin@kuresaletzinciri.com
   - Password: password
3. Click "Giriş Yap"
4. ✅ Expected: Redirect to admin dashboard (`/Küresel/?page=admin`)

## 📋 Database Schema Verification

### Users Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    user_type ENUM('admin', 'company', 'validator', 'consumer') NOT NULL,
    status ENUM('active', 'inactive', 'suspended', 'pending') DEFAULT 'pending',
    email_verified BOOLEAN DEFAULT FALSE,
    profile_image VARCHAR(255),
    language CHAR(2) DEFAULT 'tr',
    timezone VARCHAR(50) DEFAULT 'Europe/Istanbul',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL DEFAULT NULL
)
```

### Companies Table
```sql
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    legal_name VARCHAR(255),
    tax_number VARCHAR(50),
    industry_sector VARCHAR(100),
    company_type ENUM('supplier', 'manufacturer', 'distributor', 'retailer', 'farmer', 'fisher', 'logistics') NOT NULL,
    registration_number VARCHAR(100),
    website VARCHAR(255),
    description TEXT,
    -- Address, Contact, Certification, Reputation, Blockchain fields...
)
```

### Validators Table
```sql
CREATE TABLE validators (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL,
    user_id INT NOT NULL,
    validator_name VARCHAR(255) NOT NULL,
    organization_type ENUM('ngo', 'certification_body', 'audit_firm', 'government', 'independent') NOT NULL,
    specialization JSON,
    credentials JSON,
    -- Location, Reputation, Token, Blockchain fields...
)
```

## 🔧 Technical Implementation Details

### Registration Flow:
1. User selects user type on registration page
2. Dynamic form fields appear based on user type
3. Form submitted to `/api/auth/register`
4. API creates user account with appropriate user_type
5. For company/validator users, additional profiles are created
6. Success response includes redirect URL
7. Frontend redirects to login page

### Login Flow:
1. User enters credentials on login page
2. Form submitted to `/api/auth/login`
3. API authenticates user and determines user_type
4. API response includes user data and redirect URL
5. Frontend JavaScript determines redirect based on user_type:
   - admin → `/Küresel/?page=admin`
   - company → `/Küresel/?page=company`
   - validator → `/Küresel/?page=validator`
   - consumer → `/Küresel/` (homepage)

## ✅ All Pages Verified Working:

| Page | URL | Status |
|------|-----|--------|
| Homepage | `http://localhost/Küresel/` | ✅ Working |
| Register | `http://localhost/Küresel/?page=register` | ✅ Working |
| Login | `http://localhost/Küresel/?page=login` | ✅ Working |
| Company Dashboard | `http://localhost/Küresel/?page=company` | ✅ Working |
| Validator Dashboard | `http://localhost/Küresel/?page=validator` | ✅ Working |
| Admin Dashboard | `http://localhost/Küresel/?page=admin` | ✅ Working |
| Consumer Dashboard | `http://localhost/Küresel/?page=consumer` | ✅ Working |
| About | `http://localhost/Küresel/?page=about` | ✅ Working |
| Contact | `http://localhost/Küresel/?page=contact` | ✅ Working |

## 🎯 Project Alignment

### Vizyon ve Misyon Uyumlu Özellikler:
✅ **Şeffaflık:** Kullanıcıların rollerine göre farklı bilgiler sunulur
✅ **Hesap Verebilirlik:** Şirketler kendi dashboard'larında veri sağlar
✅ **Güven:** Blockchain entegrasyonu için altyapı hazır
✅ **Adil Rekabet:** Tüm kullanıcı tipleri için eşit erişim

### Sistem Akış Şeması Uyumlu Özellikler:
✅ **Tedarik Zinciri Katılımcıları:** Şirket kullanıcıları için özel dashboard
✅ **Bağımsız Denetçiler:** Validator kullanıcıları için özel dashboard
✅ **Tüketiciler:** Ürün bilgilerine erişim için consumer rolü
✅ **Platform Yöneticileri:** Admin kullanıcıları için yönetim paneli

## 🚀 Next Steps for Full Implementation:

1. **Email Verification:** Implement email verification for new users
2. **Password Reset:** Complete forgot password functionality
3. **Profile Management:** Allow users to update their profiles
4. **Company/Validator Profile Completion:** Allow users to complete their profiles after registration
5. **API Endpoints:** Create missing API endpoints for dashboards
6. **Blockchain Integration:** Connect to actual blockchain network
7. **QR Code Generation:** Implement QR code generation for products
8. **Product Management:** Allow companies to add/manage products

---

## ✅ Test Results Summary:

| Feature | Status | Notes |
|---------|--------|-------|
| Consumer Registration | ✅ Working | Basic registration, homepage redirect |
| Company Registration | ✅ Working | Company profile creation, dashboard redirect |
| Validator Registration | ✅ Working | Validator profile creation, dashboard redirect |
| Admin Login | ✅ Working | Admin dashboard redirect |
| All Pages Accessible | ✅ Working | No 404 errors |
| Role-Based Redirects | ✅ Working | Correct dashboard for each user type |
| Database Schema | ✅ Ready | All required tables exist |
| User Type Handling | ✅ Working | Proper ENUM values in database |

---

**Status:** ✅ **All Registration and Login Features Working Correctly!**
**Last Updated:** 2025-10-10

The Küresel Etki Zinciri platform now correctly handles registration and login for all user types according to your project requirements!