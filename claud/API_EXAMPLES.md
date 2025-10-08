# Timesheet API - Request/Response Examples

## Authentication
All endpoints require Bearer token in header:
```
Authorization: Bearer {your_token_here}
```

---

## FREELANCER ENDPOINTS

### 1. List Freelancer Timesheets
**GET** `/api/v1/freelancer/timesheets?status=Pending`

**Response:**
```json
{
    "success": true,
    "message": "Timesheets retrieved successfully",
    "data": {
        "current_page": 1,
        "data": [
            {
                "timesheet_id": 1,
                "project_title": "Website Redesign",
                "company_name": "Tech Corp",
                "start_date": "2025-10-01",
                "end_date": "2025-10-07",
                "total_hours": 35.5,
                "total_amount": 1775.00,
                "status_name": "Pending",
                "submitted_at": "2025-10-08 10:30:00"
            }
        ],
        "total": 1
    }
}
```

### 2. Get Dropdown Data
**GET** `/api/v1/freelancer/timesheets/dropdown-data`

**Response:**
```json
{
    "success": true,
    "message": "Dropdown data retrieved successfully",
    "data": {
        "contracts": [
            {
                "contract_id": 1,
                "project_id": 1,
                "company_id": 1,
                "project_title": "Website Redesign",
                "company_name": "Tech Corp",
                "hourly_rate": 50.00
            }
        ],
        "freelancer_hourly_rate": 50.00
    }
}
```

### 3. Create and Submit Timesheet
**POST** `/api/v1/freelancer/timesheets`

**Request Body:**
```json
{
    "contract_id": 1,
    "company_id": 1,
    "project_id": 1,
    "start_date": "2025-10-01",
    "end_date": "2025-10-07",
    "days": [
        {
            "work_date": "2025-10-01",
            "day_name": "Monday",
            "hours_worked": 8.0,
            "task_description": "Initial design wireframes"
        },
        {
            "work_date": "2025-10-02",
            "day_name": "Tuesday",
            "hours_worked": 7.5,
            "task_description": "Client meeting and revisions"
        },
        {
            "work_date": "2025-10-03",
            "day_name": "Wednesday",
            "hours_worked": 8.0,
            "task_description": "Component development"
        },
        {
            "work_date": "2025-10-04",
            "day_name": "Thursday",
            "hours_worked": 6.5,
            "task_description": "Testing and bug fixes"
        },
        {
            "work_date": "2025-10-05",
            "day_name": "Friday",
            "hours_worked": 5.5,
            "task_description": "Documentation"
        },
        {
            "work_date": "2025-10-06",
            "day_name": "Saturday",
            "hours_worked": 0,
            "task_description": "Day off"
        },
        {
            "work_date": "2025-10-07",
            "day_name": "Sunday",
            "hours_worked": 0,
            "task_description": "Day off"
        }
    ]
}
```

**Response:**
```json
{
    "success": true,
    "message": "Timesheet created and submitted successfully",
    "data": {
        "timesheet_id": 1,
        "project_title": "Website Redesign",
        "company_name": "Tech Corp",
        "start_date": "2025-10-01",
        "end_date": "2025-10-07",
        "total_hours": 35.5,
        "total_amount": 1775.00,
        "status_name": "Pending"
    }
}
```

### 4. View Timesheet Details
**GET** `/api/v1/freelancer/timesheets/1`

**Response:**
```json
{
    "success": true,
    "message": "Timesheet retrieved successfully",
    "data": {
        "timesheet": {
            "timesheet_id": 1,
            "project_title": "Website Redesign",
            "company_name": "Tech Corp",
            "start_date": "2025-10-01",
            "end_date": "2025-10-07",
            "total_hours": 35.5,
            "hourly_rate": 50.00,
            "total_amount": 1775.00,
            "status_name": "Pending"
        },
        "days": [
            {
                "day_id": 1,
                "work_date": "2025-10-01",
                "day_name": "Monday",
                "day_number": 1,
                "hours_worked": 8.0,
                "task_description": "Initial design wireframes",
                "comments": [
                    {
                        "comment_id": 1,
                        "comment_type": "Company",
                        "comment_text": "Please provide more details on the design approach",
                        "commenter_name": "John Smith",
                        "created_at": "2025-10-08 14:30:00"
                    }
                ]
            }
        ]
    }
}
```

### 5. Resubmit Rejected Timesheet
**PUT** `/api/v1/freelancer/timesheets/1/resubmit`

**Request Body:**
```json
{
    "days": [
        {
            "day_id": 1,
            "hours_worked": 7.5,
            "task_description": "Initial design wireframes with detailed approach document",
            "freelancer_comment": "Added detailed design approach as requested"
        },
        {
            "day_id": 2,
            "hours_worked": 7.5,
            "task_description": "Client meeting and revisions"
        }
    ]
}
```

**Response:**
```json
{
    "success": true,
    "message": "Timesheet resubmitted successfully"
}
```

### 6. Request Payment
**POST** `/api/v1/freelancer/timesheets/1/request-payment`

**Response:**
```json
{
    "success": true,
    "message": "Payment request submitted successfully"
}
```

### 7. Payment History
**GET** `/api/v1/freelancer/timesheets/payment-history`

**Response:**
```json
{
    "success": true,
    "message": "Payment history retrieved successfully",
    "data": {
        "payments": {
            "data": [
                {
                    "payment_id": 1,
                    "invoice_number": "INV-20251008-000001",
                    "project_title": "Website Redesign",
                    "company_name": "Tech Corp",
                    "amount": 1775.00,
                    "status": "Completed",
                    "transaction_id": "TXN123456",
                    "paid_at": "2025-10-15 16:00:00"
                }
            ]
        },
        "total_earnings": {
            "total_earned": 15000.00,
            "total_paid": 14500.00,
            "pending_amount": 500.00
        }
    }
}
```

---

## COMPANY ENDPOINTS

### 1. List Company Timesheets
**GET** `/api/v1/company/timesheets?status=Pending`

**Response:**
```json
{
    "success": true,
    "message": "Timesheets retrieved successfully",
    "data": {
        "data": [
            {
                "timesheet_id": 1,
                "project_title": "Website Redesign",
                "freelancer_name": "Jane Doe",
                "freelancer_email": "jane@example.com",
                "start_date": "2025-10-01",
                "end_date": "2025-10-07",
                "total_hours": 35.5,
                "total_amount": 1775.00,
                "status_name": "Pending",
                "submitted_at": "2025-10-08 10:30:00"
            }
        ]
    }
}
```

### 2. Pending Timesheets
**GET** `/api/v1/company/timesheets/pending`

**Response:** Same as above, filtered to Pending only

### 3. Accepted Timesheets
**GET** `/api/v1/company/timesheets/accepted`

**Response:**
```json
{
    "success": true,
    "message": "Accepted timesheets retrieved successfully",
    "data": {
        "data": [
            {
                "timesheet_id": 1,
                "project_title": "Website Redesign",
                "freelancer_name": "Jane Doe",
                "status_name": "Accepted",
                "invoice_id": 1,
                "invoice_number": "INV-20251008-000001",
                "invoice_status": "Generated",
                "due_date": "2025-11-08"
            }
        ]
    }
}
```

### 4. View Timesheet Details
**GET** `/api/v1/company/timesheets/1`

**Response:**
```json
{
    "success": true,
    "message": "Timesheet retrieved successfully",
    "data": {
        "timesheet": {
            "timesheet_id": 1,
            "project_title": "Website Redesign",
            "freelancer_name": "Jane Doe",
            "freelancer_email": "jane@example.com",
            "freelancer_phone": "+1234567890",
            "start_date": "2025-10-01",
            "end_date": "2025-10-07",
            "total_hours": 35.5,
            "hourly_rate": 50.00,
            "total_amount": 1775.00,
            "status_name": "Pending"
        },
        "days": [
            {
                "day_id": 1,
                "work_date": "2025-10-01",
                "day_name": "Monday",
                "hours_worked": 8.0,
                "task_description": "Initial design wireframes",
                "comments": []
            }
        ]
    }
}
```

### 5. Add Comment to Day
**POST** `/api/v1/company/timesheets/1/days/1/comment`

**Request Body:**
```json
{
    "comment_text": "Please provide more details on the design approach"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Comment added successfully",
    "data": {
        "comment_id": 1,
        "comment_type": "Company",
        "comment_text": "Please provide more details on the design approach",
        "commenter_name": "John Smith",
        "created_at": "2025-10-08 14:30:00"
    }
}
```

### 6. Accept Timesheet
**POST** `/api/v1/company/timesheets/1/accept`

**Response:**
```json
{
    "success": true,
    "message": "Timesheet accepted and invoice generated",
    "data": {
        "timesheet_id": 1,
        "invoice_id": 1,
        "invoice_number": "INV-20251008-000001"
    }
}
```

### 7. Reject Timesheet
**POST** `/api/v1/company/timesheets/1/reject`

**Request Body:**
```json
{
    "rejection_reason": "Hours seem excessive for the described tasks. Please review."
}
```

**Response:**
```json
{
    "success": true,
    "message": "Timesheet rejected successfully"
}
```

### 8. Complete Payment
**POST** `/api/v1/company/invoices/1/complete-payment`

**Request Body:**
```json
{
    "transaction_id": "TXN123456789",
    "payment_method": "Bank Transfer"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Payment completed and submitted for verification"
}
```

### 9. Payment History
**GET** `/api/v1/company/payments/history`

**Response:**
```json
{
    "success": true,
    "message": "Payment history retrieved successfully",
    "data": {
        "data": [
            {
                "payment_id": 1,
                "invoice_number": "INV-20251008-000001",
                "project_title": "Website Redesign",
                "freelancer_name": "Jane Doe",
                "amount": 1775.00,
                "status": "Approved",
                "transaction_id": "TXN123456789",
                "paid_at": "2025-10-12 10:00:00"
            }
        ]
    }
}
```

---

## ADMIN ENDPOINTS

### 1. List All Timesheets
**GET** `/api/v1/admin/timesheets?status=Accepted&start_date=2025-10-01`

**Response:**
```json
{
    "success": true,
    "message": "Timesheets retrieved successfully",
    "data": {
        "data": [
            {
                "timesheet_id": 1,
                "project_title": "Website Redesign",
                "freelancer_name": "Jane Doe",
                "freelancer_email": "jane@example.com",
                "company_name": "Tech Corp",
                "start_date": "2025-10-01",
                "end_date": "2025-10-07",
                "total_hours": 35.5,
                "total_amount": 1775.00,
                "status_name": "Accepted"
            }
        ]
    }
}
```

### 2. Accepted Timesheets
**GET** `/api/v1/admin/timesheets/accepted`

**Response:**
```json
{
    "success": true,
    "message": "Accepted timesheets retrieved successfully",
    "data": {
        "data": [
            {
                "timesheet_id": 1,
                "project_title": "Website Redesign",
                "freelancer_name": "Jane Doe",
                "company_name": "Tech Corp",
                "invoice_id": 1,
                "invoice_number": "INV-20251008-000001",
                "invoice_status": "Paid"
            }
        ]
    }
}
```

### 3. View Timesheet Details
**GET** `/api/v1/admin/timesheets/1`

**Response:**
```json
{
    "success": true,
    "message": "Timesheet retrieved successfully",
    "data": {
        "timesheet": {
            "timesheet_id": 1,
            "project_title": "Website Redesign",
            "freelancer_name": "Jane Doe",
            "company_name": "Tech Corp",
            "status_name": "Accepted",
            "reviewed_by_name": "John Smith"
        },
        "days": [
            {
                "day_id": 1,
                "work_date": "2025-10-01",
                "hours_worked": 8.0,
                "comments": []
            }
        ],
        "invoice": {
            "invoice_id": 1,
            "invoice_number": "INV-20251008-000001",
            "total_amount": 1775.00,
            "status": "Paid"
        },
        "payment_requests": [],
        "payments": []
    }
}
```

### 4. Payment Requests
**GET** `/api/v1/admin/payment-requests?status=Pending`

**Response:**
```json
{
    "success": true,
    "message": "Payment requests retrieved successfully",
    "data": {
        "data": [
            {
                "request_id": 1,
                "timesheet_id": 1,
                "invoice_number": "INV-20251008-000001",
                "project_title": "Website Redesign",
                "freelancer_name": "Jane Doe",
                "freelancer_email": "jane@example.com",
                "company_name": "Tech Corp",
                "requested_amount": 1775.00,
                "status": "Pending",
                "requested_at": "2025-10-13 09:00:00"
            }
        ]
    }
}
```

### 5. Process Freelancer Payment
**POST** `/api/v1/admin/payment-requests/1/process`

**Request Body:**
```json
{
    "transaction_id": "TXN-FL-123456",
    "payment_method": "Bank Transfer",
    "payment_notes": "Payment processed via bank transfer"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Freelancer payment processed successfully"
}
```

### 6. Company Payments for Verification
**GET** `/api/v1/admin/payments/company-payments`

**Response:**
```json
{
    "success": true,
    "message": "Company payments retrieved successfully",
    "data": {
        "data": [
            {
                "payment_id": 1,
                "invoice_number": "INV-20251008-000001",
                "timesheet_id": 1,
                "company_name": "Tech Corp",
                "project_title": "Website Redesign",
                "amount": 1775.00,
                "transaction_id": "TXN123456789",
                "status": "Pending"
            }
        ]
    }
}
```

### 7. Verify Company Payment
**POST** `/api/v1/admin/payments/1/verify`

**Request Body:**
```json
{
    "verification_notes": "Transaction verified with bank records"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Payment verified and approved successfully"
}
```

### 8. Download Invoice
**GET** `/api/v1/admin/invoices/1/download`

**Response:**
```json
{
    "success": true,
    "message": "Invoice data retrieved successfully",
    "data": {
        "invoice": {
            "invoice_number": "INV-20251008-000001",
            "invoice_date": "2025-10-08",
            "company_name": "Tech Corp",
            "company_address": "123 Tech Street",
            "freelancer_name": "Jane Doe",
            "freelancer_address": "456 Freelancer Ave",
            "project_title": "Website Redesign",
            "total_hours": 35.5,
            "hourly_rate": 50.00,
            "subtotal": 1775.00,
            "tax_amount": 0,
            "total_amount": 1775.00
        },
        "days": [
            {
                "work_date": "2025-10-01",
                "day_name": "Monday",
                "hours_worked": 8.0,
                "task_description": "Initial design wireframes"
            }
        ]
    }
}
```

### 9. Statistics
**GET** `/api/v1/admin/timesheets/statistics?start_date=2025-10-01&end_date=2025-10-31`

**Response:**
```json
{
    "success": true,
    "message": "Statistics retrieved successfully",
    "data": {
        "total_timesheets": 45,
        "pending_timesheets": 12,
        "accepted_timesheets": 28,
        "rejected_timesheets": 5,
        "total_hours": 1580.5,
        "total_amount": 79025.00,
        "pending_payment_requests": 8,
        "pending_company_payments": 3
    }
}
```

### 10. Delete Timesheet
**DELETE** `/api/v1/admin/timesheets/1`

**Response:**
```json
{
    "success": true,
    "message": "Timesheet deleted successfully"
}
```

---

## ERROR RESPONSES

### Validation Error
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "days": ["The days field is required."],
        "start_date": ["The start date must be a valid date."]
    }
}
```

### Not Found
```json
{
    "success": false,
    "message": "Timesheet not found"
}
```

### Unauthorized
```json
{
    "success": false,
    "message": "Timesheet not found or access denied"
}
```

### Business Logic Error
```json
{
    "success": false,
    "message": "Only pending timesheets can be accepted"
}
```

### Server Error
```json
{
    "success": false,
    "message": "Failed to create timesheet",
    "error": "Database connection error"
}
```

---

## HTTP STATUS CODES

- **200 OK** - Success
- **201 Created** - Resource created successfully
- **400 Bad Request** - Business logic error
- **403 Forbidden** - Access denied
- **404 Not Found** - Resource not found
- **422 Unprocessable Entity** - Validation failed
- **500 Internal Server Error** - Server error

---

## TESTING WORKFLOW

1. **Freelancer creates timesheet** → Status: Pending
2. **Company reviews and accepts** → Status: Accepted, Invoice auto-generated
3. **Company marks payment complete** → Payment: Pending verification
4. **Admin verifies company payment** → Payment: Approved
5. **Freelancer requests payment** → Payment Request created
6. **Admin processes freelancer payment** → Freelancer earnings updated

---

## POSTMAN COLLECTION

You can import these examples into Postman for easier testing. Create environment variables:
- `base_url`: http://your-api-domain.com
- `freelancer_token`: {freelancer_auth_token}
- `company_token`: {company_auth_token}
- `admin_token`: {admin_auth_token}
