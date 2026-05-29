# CBS (Car Broker System) - Complete Workflow Guide

## 📋 System Overview

The CBS is a comprehensive **Vehicle Trading & Management Platform** with four distinct user roles, each with specific responsibilities and capabilities.

---

## 🚀 **PHASE 1: AUTHENTICATION & ACCESS CONTROL**

### Step 1: User Entry Point
- User visits: `http://localhost/CBS/public/`
- Application checks authentication status
- **If logged in:** Redirect to Dashboard
- **If not logged in:** Present Login/Register options

### Step 2: Authentication Options

#### **A) User Login** (`/login`)
```
1. Enter Email Address
2. Enter Password
3. Click "Sign In"
4. System validates credentials (Bcrypt hashing)
5. If valid:
   - Create secure session
   - Redirect to Dashboard
6. If invalid:
   - Show error message
   - Allow retry
```

#### **B) User Registration** (`/register`)
```
1. Fill Registration Form:
   - Name (required)
   - Email (unique, required)
   - Phone (optional)
   - Address (optional)
   - Password (min 8 chars)
   - Confirm Password
2. Select User Role:
   - Buyer
   - Seller
   - Broker (requires license)
3. Click "Register"
4. System:
   - Validates all inputs
   - Hashes password (Bcrypt)
   - Creates user account
   - Sends verification email
```

### Step 3: Email Verification

**Verification Process:**
```
1. User completes registration
2. Verification email sent to user's inbox
3. User clicks verification link
4. Email status updated in database
5. Access granted to full system
6. If not verified: Limited access (browser warning)
```

### Step 4: Two-Factor Authentication (Optional)

**Optional Security Enhancement:**
```
1. User navigates to Settings
2. Enable 2FA option
3. System generates QR code
4. User scans with authenticator app
5. Download recovery codes (backup)
6. On next login: Enter 2FA code
```

---

## 📊 **PHASE 2: ROLE-BASED DASHBOARD ACCESS**

After login, users are directed to role-specific dashboards based on their role:

### **2A: ADMIN DASHBOARD** 👨‍💼
**Accessible to:** Admin users only

#### Admin Capabilities:
1. **User Management**
   - View all users
   - Activate/Deactivate users
   - Assign roles
   - View user details

2. **Broker License Approval**
   - Review broker applications
   - Download license documents
   - Approve/Reject licenses
   - Send notifications

3. **Vehicle Listing Oversight**
   - View all listings
   - Monitor listing status
   - Delete inappropriate listings
   - View listing analytics

4. **Transaction Monitoring**
   - View all transactions
   - Monitor transaction status
   - Review payment details
   - View transaction history

5. **System Reports**
   - Generate reports
   - View analytics
   - Monitor system health
   - Check user activities

**Admin Dashboard URL:** `/dashboard`

---

### **2B: BROKER DASHBOARD** 🚗
**Accessible to:** Approved brokers

#### Broker Capabilities:

#### 1. **Vehicle Listing Management**
```
Path: /vehicles/create
Steps:
  1. Click "Add New Vehicle"
  2. Fill Vehicle Details:
     - Brand/Make
     - Model
     - Year
     - Price
     - Mileage
     - Transmission (Manual/Automatic)
     - Fuel Type (Petrol/Diesel/Hybrid)
     - Color
     - Description
  3. Upload Images/Videos:
     - Multiple image upload
     - Video URL (optional)
     - Gallery preview
  4. Click "Create Listing"
  5. Vehicle appears in marketplace
  6. Status: "Active" ✅
```

#### 2. **Manage Listings**
```
Path: /vehicles/manage or /vehicles
Features:
  - View all my listings
  - Edit listing details
  - Update images/videos
  - Change price
  - Change vehicle status:
    - Active (available)
    - Inactive (not for sale)
    - Sold
  - Delete listing
  - View listing views/inquiries
```

#### 3. **View Inquiries**
```
Path: /inquiries
Shows:
  - List of buyer inquiries
  - Buyer contact information
  - Inquiry date/time
  - Vehicle details
  - Buyer message
  - Reply to inquiry
  - Mark as resolved
```

#### 4. **Record Transactions**
```
Path: /transactions/create
Steps:
  1. Click "New Transaction"
  2. Select Vehicle
  3. Select Buyer
  4. Enter Sale Price
  5. Upload Agreement Document (PDF)
  6. Click "Create Transaction"
  7. Buyer receives OTP
  8. Buyer verifies transaction
  9. Payment processed
  10. Transaction marked "Completed"
```

#### 5. **Manage Broker License**
```
Path: /broker/license
Features:
  - Upload license document
  - View license status
  - Check approval date
  - Renew license
  - Download license copy
  
Status Flow:
  Pending → Under Review → Approved/Rejected
```

**Broker Dashboard URL:** `/dashboard`

---

### **2C: BUYER DASHBOARD** 🛍️
**Accessible to:** Buyers & all authenticated users

#### Buyer Capabilities:

#### 1. **Browse Vehicles**
```
Path: /browse or /vehicles/shop
Features:
  - View all available vehicles
  - Display vehicle grid/list
  - Show vehicle images
  - Display price & key details
  - Quick view option
```

#### 2. **Search & Filter**
```
Filters Available:
  - Brand/Make (dropdown)
  - Model (text search)
  - Price Range (min-max)
  - Year Range (min-max)
  - Transmission (Manual/Automatic)
  - Fuel Type (Petrol/Diesel/Hybrid)
  - Color
  - Mileage Range
  
Search Order:
  1. Select filters
  2. Click "Apply Filters" or auto-refresh
  3. View filtered results
  4. Click "Reset" to clear filters
```

#### 3. **View Vehicle Details**
```
Path: /vehicles/{id}
Shows:
  - Full vehicle specifications
  - Gallery of images
  - Videos (if available)
  - Detailed description
  - Price information
  - Seller/Broker details
  - Customer reviews (if any)
  - "Send Inquiry" button
```

#### 4. **Send Inquiry**
```
Steps:
  1. Click "Send Inquiry" on vehicle detail
  2. Fill inquiry form:
     - Your message/questions
     - Preferred contact method
  3. Click "Send"
  4. Broker receives notification
  5. Buyer can track inquiry status
  6. Chat interface for broker-buyer communication
```

#### 5. **View Bookings/Offers**
```
Path: /bookings or /offers
Shows:
  - Bookings made
  - Pending offers received
  - Accepted offers
  - Transaction history
  - View offer details
  - Accept/Reject offers
```

#### 6. **Vehicle Valuation Tool**
```
Path: /valuation
Features:
  - Calculate vehicle value
  - Input vehicle specs
  - Get estimated price
  - Compare market rates
```

**Buyer Dashboard URL:** `/dashboard`

---

### **2D: SELLER DASHBOARD** 📋
**Accessible to:** Sellers

#### Seller Capabilities:

#### 1. **View Listings**
```
Shows:
  - Vehicles listed through broker
  - Listing status
  - Number of inquiries
  - Sale history
```

#### 2. **Track Sales**
```
Features:
  - View completed transactions
  - Transaction dates
  - Final price
  - Buyer information
```

#### 3. **View Offers**
```
Features:
  - Pending offers
  - Offer details
  - Offer timeline
  - Accept/Reject offers
```

**Seller Dashboard URL:** `/dashboard`

---

## 🔄 **PHASE 3: CORE BUSINESS WORKFLOWS**

### **WORKFLOW A: Creating & Selling a Vehicle (Broker Flow)**

```
START
  ↓
Broker logs in → Broker Dashboard
  ↓
Click "Add Vehicle"
  ↓
Fill Details:
  - Make: Toyota
  - Model: Corolla
  - Year: 2020
  - Price: 1,500,000
  - Mileage: 45,000 km
  - Condition details
  ↓
Upload Images:
  - 5-10 photos
  - Front, back, sides
  - Interior shots
  - Engine
  ↓
Upload Videos (optional)
  ↓
Set Status: "Active"
  ↓
Click "Create Listing"
  ↓
Vehicle Published ✅
  ↓
Appears in:
  - Browse page
  - Search results
  - Featured listings
  ↓
Buyers can now:
  - View details
  - Send inquiries
  - Share listing
  ↓
END

Timeline: 2-3 minutes
```

---

### **WORKFLOW B: Buying a Vehicle (Buyer Flow)**

```
START
  ↓
Buyer logs in → Buyer Dashboard
  ↓
Navigate to Browse/Search
  ↓
Apply Filters:
  - Make: Toyota
  - Price: 1,000,000 - 2,000,000
  - Year: 2018-2022
  ↓
View Results: 15 vehicles match
  ↓
Click on interesting vehicle
  ↓
View Full Details:
  - Specifications
  - Photos
  - Description
  - Reviews
  ↓
Click "Send Inquiry"
  ↓
Enter Message:
  "Is this available? Interested in test drive"
  ↓
Click "Send"
  ↓
Broker Notified ✉️
  ↓
Wait for Response (1-24 hours usually)
  ↓
Chat with Broker
  ↓
Schedule Test Drive
  ↓
Agree on Final Price
  ↓
Proceed to Transaction
  ↓
END

Timeline: 1-7 days
```

---

### **WORKFLOW C: Completing a Transaction (Complete Flow)**

```
Stage 1: NEGOTIATION
  Buyer & Broker negotiate price
  ↓

Stage 2: AGREEMENT
  Broker clicks "Record Transaction"
  Selects:
    - Vehicle
    - Buyer
    - Agreed Price
  ↓

Stage 3: DOCUMENTATION
  Broker uploads:
    - Sale Agreement (PDF)
    - Any relevant documents
  ↓

Stage 4: OTP VERIFICATION
  System sends OTP to Buyer
  Buyer enters OTP
  Confirms transaction
  ↓

Stage 5: PAYMENT PROCESSING
  Select Payment Method:
    - Bank Transfer
    - Cash
    - Check
  ↓

Stage 6: COMPLETION
  System records:
    - Transaction date
    - Sale price
    - Payment method
    - Agreement document
  ↓

Stage 7: NOTIFICATION
  Both parties receive confirmation
  Vehicle status updated to "Sold"
  ↓

END ✅

Timeline: 1-2 hours
```

---

## 🔐 **PHASE 4: SECURITY FEATURES**

### Security Measures Implemented:

1. **Authentication**
   - Secure login with email/password
   - Bcrypt password hashing
   - Session-based auth
   - Remember-me tokens

2. **Authorization**
   - Role-Based Access Control (RBAC)
   - Admin-only sections
   - Broker-only sections
   - Route middleware protection

3. **Input Validation**
   - Server-side validation on all forms
   - Email format validation
   - Phone number validation
   - Price/number type checking
   - File upload validation

4. **Data Protection**
   - CSRF token on all forms
   - SQL injection prevention (Eloquent ORM)
   - XSS protection (Blade templating)
   - Secure headers (HSTS, X-Frame-Options)

5. **Email Verification**
   - Required for account activation
   - Signed verification links
   - Resend verification option

6. **Two-Factor Authentication**
   - Optional 2FA setup
   - Recovery codes backup
   - Time-based OTP

---

## 📱 **PHASE 5: RESPONSIVE DESIGN**

### Device Compatibility:

**Mobile (320px - 767px):**
- Stacked layout
- Touch-friendly buttons
- Mobile navigation menu
- Optimized forms

**Tablet (768px - 1023px):**
- 2-column layout
- Larger buttons
- Optimized tables

**Desktop (1024px+):**
- Full layout
- 3-4 column grids
- Hover effects
- Full functionality

---

## 🛠️ **PHASE 6: KEY FEATURES SUMMARY**

| Feature | Broker | Buyer | Seller | Admin |
|---------|--------|-------|--------|-------|
| Create Listings | ✅ | ❌ | ❌ | ✅ |
| Edit Listings | ✅ | ❌ | ❌ | ✅ |
| Delete Listings | ✅ | ❌ | ❌ | ✅ |
| Browse Vehicles | ✅ | ✅ | ✅ | ✅ |
| Search & Filter | ✅ | ✅ | ✅ | ✅ |
| Send Inquiry | ✅ | ✅ | ❌ | ❌ |
| Record Transaction | ✅ | ❌ | ❌ | ✅ |
| View Transactions | ✅ | ✅ | ✅ | ✅ |
| Manage Users | ❌ | ❌ | ❌ | ✅ |
| Approve Licenses | ❌ | ❌ | ❌ | ✅ |
| View Reports | ❌ | ❌ | ❌ | ✅ |
| Upload License | ✅ | ❌ | ❌ | ❌ |

---

## 🌐 **KEY URLs REFERENCE**

| Module | URL | Access |
|--------|-----|--------|
| Home | `/` | All |
| Login | `/login` | Guest |
| Register | `/register` | Guest |
| Dashboard | `/dashboard` | Auth |
| Browse Vehicles | `/browse` | Auth |
| Shop Vehicles | `/vehicles/shop` | All |
| Create Vehicle | `/vehicles/create` | Broker |
| Edit Vehicle | `/vehicles/{id}/edit` | Broker |
| Vehicle Details | `/vehicles/{id}` | Auth |
| Transactions | `/transactions` | Auth |
| Inquiries | `/inquiries` | Auth |
| Bookings | `/bookings` | Auth |
| Valuation | `/valuation` | All |
| Broker License | `/broker/license` | Broker |
| Profile | `/profile` | Auth |
| Logout | `/logout` | Auth |

---

## 📊 **DATABASE STRUCTURE**

**Core Tables:**
- `users` - User accounts with roles
- `vehicles` - Car listings
- `inquiries` - Buyer inquiries
- `transactions` - Sale records
- `bookings` - Vehicle bookings
- `offers` - Purchase offers
- `seller_requests` - Vehicle requests
- `vehicle_reviews` - Customer reviews

---

## 🔄 **ACTIVITY FLOW SUMMARY**

```
User Registration
    ↓
Email Verification
    ↓
Select Role
    ↓
Role-Specific Dashboard
    ↓
    ├─ Broker: Create/Manage Listings → Record Transactions
    ├─ Buyer: Browse → Search → Inquire → Negotiate → Buy
    ├─ Seller: View Listings → Track Sales
    └─ Admin: Manage All Operations
    ↓
Dashboard Analytics
    ↓
Transaction History
```

---

## ✅ **CURRENT SYSTEM STATUS**

- ✅ Authentication: Complete
- ✅ Dashboard: Complete
- ✅ Vehicle Management: Complete
- ✅ Search & Filter: Complete
- ✅ Transaction Processing: Complete
- ✅ Email Verification: Complete
- ✅ Role-Based Access: Complete
- ✅ Professional UI: Complete
- ✅ Responsive Design: Complete
- ✅ OTP Verification: Complete
- ✅ 2FA Support: Complete
- ✅ Broker License System: Complete

---

## 🚀 **QUICK START**

**Admin Access:**
- Email: `admin@cbs.bt`
- Password: `password`
- Role: Admin

**Broker Access:**
- Email: `agent@cbs.bt`
- Password: `password`
- Role: Broker

**Buyer Access:**
- Email: `buyer@cbs.bt`
- Password: `password`
- Role: Buyer

**Backend Server:** `http://127.0.0.1:8001`

---

## 📞 **Support**

For issues or questions, check:
1. System logs in `/storage/logs/`
2. Database integrity in phpMyAdmin
3. Laravel error logs in terminal

---

**Last Updated:** May 29, 2026
**System Status:** ✅ Production Ready
