# OTP System - Quick Start Guide

## 🚀 Get Started in 2 Minutes

### Step 1: Login as Admin (No Email Verification Needed)
```
URL: http://localhost/CBS/login
Email: admin@cbs.bt
Password: password

✅ Click Login → You're in! 
(No email verification step)
```

### Step 2: Create a Transaction (as Broker)
```
1. Logout (top right menu)
2. Login with broker account:
   Email: broker@cbs.bt
   Password: password

3. Click "Transactions" → "Add New"
4. Fill in the form:
   - Vehicle: Select any vehicle
   - Buyer: Select any buyer
   - Seller: Select any seller
   - Sale Price: Enter amount
   - Broker Commission: Enter amount
   - Status: "Pending CBS Review" ← IMPORTANT!
   - Agreement: Upload or skip

5. Click "Request Payment Review"
```

### Step 3: View Generated OTP
```
In terminal, check the OTP that was generated:

Option A - From Database:
  mysql> SELECT * FROM transaction_otps 
         ORDER BY created_at DESC LIMIT 1;

Option B - From Logs:
  tail -50 storage/logs/laravel.log | grep -i "OTP\|otp"

You'll see something like:
  code: "123456"
  sent_to: "both" (or "buyer" or "seller")
  expires_at: (current time + 15 minutes)
```

### Step 4: Verify OTP in Transaction Form
```
1. Go back to Transactions → Edit the transaction you just created
2. Scroll down to "One-Time Password (OTP) Verification" section
3. You'll see:
   ┌─────────────────────────────────────────────┐
   │ 🔒 One-Time Password (OTP) Verification       │
   ├─────────────────────────────────────────────┤
   │ ℹ️  An OTP has been sent to the buyer.      │
   │ Expires at: [timestamp]                     │
   │                                             │
   │ Enter OTP Code:                             │
   │ ┌─────────────┐                            │
   │ │  [ 123456 ] │  (6-digit field)           │
   │ └─────────────┘                            │
   │                                             │
   │ [Verify OTP]                                │
   │                                             │
   │ Didn't receive the code?                   │
   │ Check your email or...                     │
   └─────────────────────────────────────────────┘

4. Enter the 6-digit code (e.g., "123456")
5. Click "Verify OTP"
6. Success! You should see:
   ✅ "OTP verified successfully! 
       Awaiting CBS admin approval."
   
   And below it:
   ✅ OTP Verified
      Verified on: [timestamp]
      Awaiting CBS admin approval...
```

### Step 5: Admin Approves (Future Feature)
```
1. Logout and login as admin@cbs.bt
2. Go to admin dashboard (coming soon)
3. Approve verified transaction
4. All parties receive confirmation email
```

---

## 🎯 What Each Section Does

### Authentication Section
- **Admin:** Can login immediately (email verification skipped)
- **Others:** Must verify email first (for security)

### Transaction Creation
- **On Create/Edit:** Set status to "Pending CBS Review"
- **Automatic:** System generates OTP and sends email

### OTP Verification Section (On Transaction Form)
- **Appears When:** Transaction is in "Pending CBS Review" status
- **Shows:** Which party needs to verify (buyer/seller)
- **Action:** Enter 6-digit code and click verify

### States Display

**Before OTP Verification:**
```
ℹ️  An OTP has been sent to the [PARTY]
Expires at: [TIME]

[Input Field for 6 digits]
[Verify OTP Button]
```

**After OTP Verification:**
```
✅ OTP Verified
Verified on: [TIMESTAMP]
Awaiting CBS admin approval to complete the transaction.
You will receive an email once approved.
```

---

## 🔢 OTP Code Format

- **Length:** Exactly 6 digits
- **Characters:** Numbers only (0-9)
- **Example:** 123456, 000001, 999999
- **Input:** Auto-formats as you type (spaces ignored)
- **Expiry:** 15 minutes after generation

---

## ⚡ Keyboard Shortcuts (In OTP Field)

| Action | Result |
|--------|--------|
| Type any number | Automatically accepted |
| Type letters/symbols | Automatically filtered out |
| Paste with non-numbers | Only numbers are pasted |
| Tab key | Moves to next field |
| Enter key | Submits form (when field filled with 6 digits) |

---

## ❌ Error Messages & Solutions

### "Invalid or expired OTP code"
✅ **Solution:** 
- Check that 6 digits are entered
- Verify code hasn't expired (15 minutes)
- Try copying code again from email/logs

### "OTP code must be exactly 6 digits"
✅ **Solution:**
- Ensure exactly 6 numbers (no spaces, letters, symbols)
- Form auto-formats, but double-check

### No OTP Verification Section Showing
✅ **Solution:**
- Ensure transaction status is "Pending CBS Review"
- Refresh page (F5)
- Make sure you're editing existing transaction (not creating new)

### "Not verified yet"
✅ **Solution:**
- You haven't verified OTP yet
- Find the OTP code and enter it in the form above
- Click "Verify OTP"

---

## 📋 Complete Workflow Checklist

- [ ] Broker logs in
- [ ] Broker creates new transaction
- [ ] Broker sets status to "Pending CBS Review"
- [ ] Broker submits form
- [ ] System automatically generates OTP (check logs)
- [ ] Broker/Buyer/Seller receives notification (check logs/email)
- [ ] Buyer/Seller opens transaction edit page
- [ ] Buyer/Seller sees OTP verification section
- [ ] Buyer/Seller enters 6-digit code
- [ ] Buyer/Seller clicks "Verify OTP"
- [ ] System shows success message
- [ ] OTP Verified status displays with timestamp
- [ ] Admin can now approve transaction
- [ ] System sends confirmation to all parties

---

## 🧪 Test Data

### Pre-loaded Users:
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@cbs.bt | password |
| Broker | broker@cbs.bt | password |
| Buyer | buyer@cbs.bt | password |
| Seller | seller@cbs.bt | password |

### Pre-loaded Vehicles:
Check Vehicles section or use any vehicle from dropdown in transaction form.

---

## 🔍 Debugging Tips

### Check if OTP was Generated:
```bash
# In terminal, check database:
mysql -u root -p
use cbs_db;
SELECT id, transaction_id, code, created_at, expires_at 
FROM transaction_otps 
ORDER BY created_at DESC 
LIMIT 5;
```

### Check if OTP was Verified:
```bash
SELECT id, email, otp_verified_at 
FROM transactions 
WHERE otp_verified_at IS NOT NULL 
ORDER BY otp_verified_at DESC;
```

### Check Email Sending Status:
```bash
# Check recent logs:
tail -100 storage/logs/laravel.log | grep -i "otp\|email\|notification"

# Look for:
# ✅ "OTP generated for transaction"
# ✅ "OTP notification sent"
# ❌ "Failed to send OTP email" (expected if mail not configured)
```

---

## 🎓 Understanding the Process

### Database Records Created:
```
When OTP Generated:
├─ transaction_otps:
│  ├─ id: auto-increment
│  ├─ transaction_id: which transaction
│  ├─ code: the 6-digit code
│  ├─ sent_to: "buyer" or "seller" or "both"
│  ├─ expires_at: now + 15 minutes
│  ├─ used_at: NULL (until verified)
│  └─ created_at: now

When OTP Verified:
└─ transactions:
   ├─ otp_verified_at: now() ← SET
   │
   └─ transaction_otps:
      └─ used_at: now() ← SET
```

### Email Content (if configured):
```
Subject: Your OTP Code for Transaction #[ID]

Body:
  Your OTP code is: 123456
  
  This code will expire on: [timestamp]
  
  Transaction Details:
  - Vehicle: [Brand Model]
  - Buyer: [Name]
  - Seller: [Name]
  - Amount: [Price]
  
  If you didn't request this code, please ignore this email.
```

### Error Logs (in storage/logs/laravel.log):
```
[2024-XX-XX HH:MM:SS] local.INFO: OTP generated for transaction
[2024-XX-XX HH:MM:SS] local.INFO: OTP sent to buyer
[2024-XX-XX HH:MM:SS] local.INFO: OTP verified successfully
```

---

## 🚨 Common Issues

**Issue:** Form won't submit
- Check all required fields are filled
- Check browser console for JS errors (F12)

**Issue:** OTP input field not accepting numbers
- Clear field and try again
- Page refresh (F5)
- Try different browser

**Issue:** Verification button doesn't respond
- Ensure all 6 digits entered
- Wait a moment for button state change
- Check browser network tab (F12 → Network)

**Issue:** Can't find OTP code
- Check database: `SELECT * FROM transaction_otps...`
- Check logs: `tail storage/logs/laravel.log`
- Code should appear within seconds of transaction creation

---

## ✅ Verification Steps

After implementing, verify these work:

1. ✅ Admin can login without email verification
2. ✅ Transaction creation with pending_review status
3. ✅ OTP code is generated (in database or logs)
4. ✅ OTP section appears on transaction edit form
5. ✅ OTP submission validates correctly
6. ✅ OTP verification shows success message
7. ✅ Database records are updated correctly
8. ✅ Errors show helpful messages

---

**The system is ready to use! 🎉**

For full implementation details, see: `OTP-SYSTEM-IMPLEMENTATION.md`
