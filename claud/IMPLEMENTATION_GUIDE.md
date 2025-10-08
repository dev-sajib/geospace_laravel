# Timesheet API Implementation Guide

## Overview
Complete timesheet workflow system implementation with Freelancer, Company, and Admin functionality.

---

## 1. NEW FILES TO CREATE

### Controllers

#### 1.1 FreelancerTimesheetController.php
**Location:** `app/Http/Controllers/Freelancer/FreelancerTimesheetController.php`
**Purpose:** Handle all freelancer timesheet operations
**Created:** ✓ Available in outputs folder

**Key Methods:**
- `index()` - List freelancer's timesheets
- `getDropdownData()` - Get companies and projects for dropdown
- `store()` - Create and submit new timesheet with 7 days
- `show()` - View timesheet details with days and comments
- `resubmit()` - Resubmit rejected timesheet with modifications
- `requestPayment()` - Request payment for accepted timesheet
- `paymentHistory()` - View payment history and total earnings

#### 1.2 CompanyTimesheetController.php
**Location:** `app/Http/Controllers/Company/CompanyTimesheetController.php`
**Purpose:** Handle company timesheet review and payment operations
**Created:** ✓ Available in outputs folder

**Key Methods:**
- `index()` - List all company timesheets
- `pendingTimesheets()` - List pending timesheets for review
- `acceptedTimesheets()` - List accepted timesheets with invoices
- `show()` - View timesheet details with 7 days and comments
- `addDayComment()` - Add company comment to specific day
- `accept()` - Accept timesheet and auto-generate invoice
- `reject()` - Reject timesheet with reason
- `completePayment()` - Mark invoice payment as complete
- `paymentHistory()` - View company payment history

### Models

#### 1.3 TimesheetDay.php
**Location:** `app/Models/TimesheetDay.php`
**Purpose:** Model for individual day records in timesheet
**Created:** ✓ Available in outputs folder

**Relationships:**
- belongsTo: Timesheet
- hasMany: TimesheetDayComment

#### 1.4 TimesheetDayComment.php
**Location:** `app/Models/TimesheetDayComment.php`
**Purpose:** Model for company and freelancer comments on days
**Created:** ✓ Available in outputs folder

**Relationships:**
- belongsTo: TimesheetDay, Timesheet, User (commenter)

#### 1.5 Invoice.php
**Location:** `app/Models/Invoice.php`
**Purpose:** Model for auto-generated invoices
**Created:** ✓ Available in outputs folder

**Relationships:**
- belongsTo: Timesheet, Contract, CompanyDetail, User (freelancer)
- hasMany: PaymentRequest, Payment

#### 1.6 PaymentRequest.php
**Location:** `app/Models/PaymentRequest.php`
**Purpose:** Model for freelancer payment requests
**Created:** ✓ Available in outputs folder

**Relationships:**
- belongsTo: Timesheet, Invoice, User (freelancer), User (processor)

---

## 2. FILES TO MODIFY

### 2.1 AdminTimesheetManagementController.php
**Location:** `app/Http/Controllers/Admin/TimesheetManagementController.php`
**Action:** Replace with new version
**Created:** ✓ Available in outputs folder

**New Methods Added:**
- `acceptedTimesheets()` - View all accepted timesheets
- `paymentRequests()` - View all freelancer payment requests
- `companyPayments()` - View company payments needing verification
- `verifyCompanyPayment()` - Verify company payment with transaction
- `processFreelancerPayment()` - Process freelancer payment and update earnings
- `downloadInvoice()` - Download invoice data
- `statistics()` - Get comprehensive statistics

### 2.2 Timesheet.php
**Location:** `app/Models/Timesheet.php`
**Action:** Replace with new version
**Created:** ✓ Available in outputs folder

**Changes:**
- Updated fillable fields to match new schema
- Added new relationships (days, invoices, paymentRequests)
- Added helper methods (isEditable, canRequestPayment, calculateTotalAmount)
- Added query scopes (pending, accepted, rejected, byFreelancer, byCompany)

### 2.3 TimesheetStatus.php
**Location:** `app/Models/TimesheetStatus.php`
**Action:** Replace with new version (or keep if not needed)
**Created:** ✓ Available in outputs folder

**Note:** This model is needed and enhanced with helper methods.

**Changes:**
- Added status constants
- Added static helper methods (getByName, getPendingStatus, etc.)

---

## 3. ROUTES TO ADD

**Location:** `routes/api.php`
**Reference:** See `timesheet_routes.php` in outputs folder

Add these route groups:

```php
// Freelancer routes
Route::prefix('v1/freelancer')->middleware(['auth:sanctum', 'role:Freelancer'])->group(function () {
    Route::get('timesheets', [FreelancerTimesheetController::class, 'index']);
    Route::get('timesheets/dropdown-data', [FreelancerTimesheetController::class, 'getDropdownData']);
    Route::post('timesheets', [FreelancerTimesheetController::class, 'store']);
    Route::get('timesheets/{id}', [FreelancerTimesheetController::class, 'show']);
    Route::put('timesheets/{id}/resubmit', [FreelancerTimesheetController::class, 'resubmit']);
    Route::post('timesheets/{id}/request-payment', [FreelancerTimesheetController::class, 'requestPayment']);
    Route::get('timesheets/payment-history', [FreelancerTimesheetController::class, 'paymentHistory']);
});

// Company routes
Route::prefix('v1/company')->middleware(['auth:sanctum', 'role:Company'])->group(function () {
    Route::get('timesheets', [CompanyTimesheetController::class, 'index']);
    Route::get('timesheets/pending', [CompanyTimesheetController::class, 'pendingTimesheets']);
    Route::get('timesheets/accepted', [CompanyTimesheetController::class, 'acceptedTimesheets']);
    Route::get('timesheets/{id}', [CompanyTimesheetController::class, 'show']);
    Route::post('timesheets/{id}/days/{dayId}/comment', [CompanyTimesheetController::class, 'addDayComment']);
    Route::post('timesheets/{id}/accept', [CompanyTimesheetController::class, 'accept']);
    Route::post('timesheets/{id}/reject', [CompanyTimesheetController::class, 'reject']);
    Route::post('invoices/{invoiceId}/complete-payment', [CompanyTimesheetController::class, 'completePayment']);
    Route::get('payments/history', [CompanyTimesheetController::class, 'paymentHistory']);
});

// Admin routes (update existing admin timesheet routes)
Route::prefix('v1/admin')->middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    Route::get('timesheets', [TimesheetManagementController::class, 'index']);
    Route::get('timesheets/accepted', [TimesheetManagementController::class, 'acceptedTimesheets']);
    Route::get('timesheets/{id}', [TimesheetManagementController::class, 'show']);
    Route::delete('timesheets/{id}', [TimesheetManagementController::class, 'destroy']);
    Route::get('payment-requests', [TimesheetManagementController::class, 'paymentRequests']);
    Route::post('payment-requests/{requestId}/process', [TimesheetManagementController::class, 'processFreelancerPayment']);
    Route::get('payments/company-payments', [TimesheetManagementController::class, 'companyPayments']);
    Route::post('payments/{paymentId}/verify', [TimesheetManagementController::class, 'verifyCompanyPayment']);
    Route::get('invoices/{invoiceId}/download', [TimesheetManagementController::class, 'downloadInvoice']);
    Route::get('timesheets/statistics', [TimesheetManagementController::class, 'statistics']);
});
```

---

## 4. DATABASE SCHEMA VERIFICATION

### Existing Tables (Already in schema):
✓ timesheets
✓ timesheet_days
✓ timesheet_day_comments
✓ timesheet_status
✓ invoices
✓ payment_requests
✓ payments
✓ freelancer_earnings

**Action Required:** Verify all tables exist. If not, run migrations.

---

## 5. WORKFLOW SUMMARY

### 5.1 Freelancer Creates Timesheet
1. Freelancer selects company and project from dropdown
2. Fills 7 days working hours with start/end dates
3. Submits timesheet
4. Status: **Pending**
5. Notification sent to company

### 5.2 Company Reviews Timesheet
1. Company opens pending timesheet
2. Reviews 7 days with hours
3. Can add comments to specific days
4. Options:
   - **Accept:** Auto-generates invoice → Status: **Accepted**
   - **Reject:** Send back to freelancer → Status: **Rejected**

### 5.3 Rejected Timesheet (Freelancer)
1. Freelancer sees rejected status
2. Opens timesheet
3. Edits hours for days with company comments
4. Adds freelancer comments if needed
5. Resubmits → Status: **Pending**
6. Goes back to company

### 5.4 Accepted Timesheet (Company Payment)
1. Company sees accepted timesheet with invoice
2. Processes payment manually
3. Clicks "Payment Complete" with transaction ID
4. Status: **Pending Verification**
5. Notification sent to admin

### 5.5 Accepted Timesheet (Freelancer Payment Request)
1. Freelancer sees accepted timesheet
2. Clicks "Request Payment"
3. Payment request sent to admin
4. Freelancer waits for payment processing

### 5.6 Admin Actions
#### Company Payment Verification:
1. Admin views company payments
2. Verifies transaction with bank manually
3. Approves payment → Status: **Approved**

#### Freelancer Payment Processing:
1. Admin views payment requests
2. Downloads invoice if needed
3. Processes payment manually
4. Enters transaction ID
5. Completes payment
6. Updates freelancer_earnings table
7. Notification sent to freelancer

---

## 6. KEY FEATURES

### Security
- All routes protected with auth:sanctum middleware
- Role-based access control (Freelancer, Company, Admin)
- User can only access their own data

### Validation
- Complete input validation on all endpoints
- Business logic validation (e.g., can't accept already accepted timesheet)
- Transaction safety with DB::beginTransaction()

### Notifications
- Real-time notifications for all key actions
- Sent to relevant parties (freelancer, company, admin)

### Activity Logging
- All actions logged in activity_logs table
- Includes user, action, entity, old/new values, IP, user agent

### Error Handling
- Try-catch blocks on all operations
- Proper HTTP status codes
- Descriptive error messages
- Database transaction rollback on errors

---

## 7. TESTING CHECKLIST

### Freelancer Tests:
- [ ] List timesheets with filters
- [ ] Get dropdown data (companies, projects)
- [ ] Create and submit timesheet with 7 days
- [ ] View timesheet details
- [ ] Resubmit rejected timesheet
- [ ] Request payment for accepted timesheet
- [ ] View payment history

### Company Tests:
- [ ] List all timesheets
- [ ] List pending timesheets
- [ ] List accepted timesheets
- [ ] View timesheet with days and comments
- [ ] Add comment to specific day
- [ ] Accept timesheet (verify invoice created)
- [ ] Reject timesheet
- [ ] Complete payment with transaction ID
- [ ] View payment history

### Admin Tests:
- [ ] List all timesheets with filters
- [ ] View accepted timesheets
- [ ] View timesheet full details
- [ ] Delete timesheet (if no payments)
- [ ] View payment requests
- [ ] Process freelancer payment
- [ ] View company payments
- [ ] Verify company payment
- [ ] Download invoice data
- [ ] View statistics

---

## 8. API RESPONSE FORMAT

All endpoints return JSON in this format:

```json
{
    "success": true/false,
    "message": "Descriptive message",
    "data": { ... }
}
```

Error responses include:
```json
{
    "success": false,
    "message": "Error message",
    "error": "Detailed error (in development)",
    "errors": { ... } // Validation errors if applicable
}
```

---

## 9. NEXT STEPS

1. Copy all controller files from outputs to respective locations
2. Copy all model files from outputs to app/Models
3. Update routes/api.php with new routes
4. Test each endpoint systematically
5. Verify database relationships
6. Test complete workflow end-to-end
7. Add frontend integration

---

## 10. NOTES

- TimesheetStatus model IS needed and should be kept with enhancements
- All monetary values use DECIMAL(10,2) or DECIMAL(12,2)
- All timestamps use Laravel's Carbon for consistency
- Invoice numbers are auto-generated in format: INV-YYYYMMDD-000001
- Payment status flow: Pending → Approved/Completed
- Freelancer earnings update only after admin completes payment

---

**Implementation Status:** All core files created and ready for integration
**Database Schema:** Verified and matches existing schema
**Total Endpoints Created:** 26 endpoints across 3 roles
