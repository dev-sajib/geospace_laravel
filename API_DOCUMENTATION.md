# 🌍 GeoSpace Laravel API Documentation

Complete API reference for the GeoSpace platform with authentication, user management, projects, timesheets, and administrative endpoints.

---

## 📋 Table of Contents

- [Overview](#overview)
- [Base URL](#base-url)
- [Authentication](#authentication)
- [Test Credentials](#-test-credentials)
- [Response Format](#-response-format)
- [Status Codes](#status-codes)
- [Public Endpoints](#-public-endpoints-no-authentication)
- [Protected Endpoints](#-protected-endpoints-authentication-required)
- [Error Handling](#error-handling)
- [Rate Limiting](#rate-limiting)

---

## Overview

The GeoSpace API is a RESTful API that uses JWT (JSON Web Tokens) for authentication. All endpoints return JSON responses and follow standard HTTP status codes.

**Version:** v1  
**Protocol:** HTTPS (recommended for production)  
**Format:** JSON

---

## Base URL

### Development
```
http://localhost:8000/api/v1
```

### Production
```
https://your-domain.com/api/v1
```

---

## Authentication

Most endpoints require JWT authentication. After logging in, include the token in the Authorization header for all protected endpoints.

### Header Format
```http
Authorization: Bearer YOUR_JWT_TOKEN
```

### Token Lifecycle
- **Expiry:** 60 minutes (can be refreshed)
- **Refresh Token Expiry:** 2 weeks
- **Algorithm:** HS256

### Example
```bash
curl -X GET "http://localhost:8000/api/v1/me" \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc..." \
  -H "Content-Type: application/json"
```

---

## 🔑 Test Credentials

Use these credentials immediately after running `php artisan db:seed`:

### Available Test Accounts

| Role | Email | Password | Role ID |
|------|-------|----------|---------|
| **Admin** | `admin@geospace.com` | `password123` | 1 |
| **Freelancer** | `freelancer@geospace.com` | `password123` | 2 |
| **Company** | `company@geospace.com` | `password123` | 3 |
| **Support** | `support@geospace.com` | `password123` | 4 |

### User Roles Reference

| Role ID | Role Name | Description |
|---------|-----------|-------------|
| 1 | Admin | System administrator with full access |
| 2 | Freelancer | Independent contractor providing services |
| 3 | Company | Organization hiring freelancers |
| 4 | Support | Customer support agent |
| 5 | Visitor | Unauthenticated user (limited access) |

### Dropdown Categories

Available categories for `/DropdownDataByCategory` endpoint:
- `Skills` - Professional skills
- `Countries` - Country list
- `Industries` - Industry sectors
- `CompanySizes` - Company size ranges
- `ProjectTypes` - Types of projects
- `ProfessionalRoles` - Job positions
- `SupportAgents` - Support team members
- `DisputeStatus` - Dispute resolution statuses

---

## 📊 Response Format

### Success Response
```json
{
  "StatusCode": 200,
  "Message": "Operation successful",
  "Success": true,
  "Data": {
    // Response data here
  }
}
```

### Error Response
```json
{
  "StatusCode": 400,
  "Message": "Error description",
  "Success": false,
  "Errors": {
    "field_name": ["Error message"]
  }
}
```

### Authentication Error
```json
{
  "StatusCode": 401,
  "Message": "Token invalid or expired",
  "Success": false
}
```

### Validation Error
```json
{
  "StatusCode": 422,
  "Message": "Validation failed",
  "Success": false,
  "Errors": {
    "Email": ["The email field is required"],
    "Password": ["The password must be at least 6 characters"]
  }
}
```

---

## Status Codes

| Code | Status | Description |
|------|--------|-------------|
| 200 | OK | Request successful |
| 201 | Created | Resource created successfully |
| 400 | Bad Request | Invalid request or business logic error |
| 401 | Unauthorized | Authentication required or token invalid |
| 403 | Forbidden | User doesn't have permission |
| 404 | Not Found | Resource not found |
| 422 | Unprocessable Entity | Validation failed |
| 500 | Internal Server Error | Server error |

---

## 🔓 Public Endpoints (No Authentication)

These endpoints do not require authentication.

---

### 1. User Login

Authenticate a user and receive a JWT token.

**Endpoint:** `POST /Login`

**Request Body:**
```json
{
  "Email": "admin@geospace.com",
  "Password": "password123"
}
```

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Login successful",
  "Success": true,
  "Token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL3YxL0xvZ2luIiwiaWF0IjoxNzI4MzQ1Njc4LCJleHAiOjE3MjgzNDkyNzgsIm5iZiI6MTcyODM0NTY3OCwianRpIjoiYUJjRGVGMTIzNCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.xyz123abc456...",
  "UserDetails": {
    "UserId": 1,
    "UserName": "Admin User",
    "Email": "admin@geospace.com",
    "RoleId": 1,
    "RoleName": "Admin",
    "ProfileImage": null,
    "IsVerified": true,
    "IsActive": true
  }
}
```

**Error Response (401):**
```json
{
  "StatusCode": 401,
  "Message": "Invalid credentials",
  "Success": false
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/v1/Login" \
  -H "Content-Type: application/json" \
  -d '{
    "Email": "admin@geospace.com",
    "Password": "password123"
  }'
```

---

### 2. Freelancer Signup

Register a new freelancer account.

**Endpoint:** `POST /SignUpFreelancer`

**Request Body:**
```json
{
  "UserPosition": "Geologist",
  "UserName": "John Doe",
  "Email": "john.doe@example.com",
  "PasswordHash": "securePassword123",
  "RoleId": 2,
  "AuthProvider": "Manual"
}
```

**Success Response (201):**
```json
{
  "StatusCode": 201,
  "Message": "Freelancer registered successfully",
  "Success": true,
  "Data": {
    "UserId": 5,
    "Email": "john.doe@example.com",
    "RoleId": 2,
    "UserPosition": "Geologist"
  }
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/v1/SignUpFreelancer" \
  -H "Content-Type: application/json" \
  -d '{
    "UserPosition": "Geologist",
    "UserName": "John Doe",
    "Email": "john.doe@example.com",
    "PasswordHash": "securePassword123",
    "RoleId": 2,
    "AuthProvider": "Manual"
  }'
```

---

### 3. Freelancer Details

Add detailed information for a freelancer profile.

**Endpoint:** `POST /SignUpFreelancerDetails`

**Request Body:**
```json
{
  "UserId": 5,
  "FirstName": "John",
  "LastName": "Doe",
  "CellNumber": "+1-234-567-8900",
  "Country": "Canada",
  "City": "Toronto",
  "Bio": "Experienced geologist with 10+ years in mining",
  "HourlyRate": 85.00,
  "Skills": ["Geological Survey", "Mining", "GIS Mapping"]
}
```

**Success Response (201):**
```json
{
  "StatusCode": 201,
  "Message": "Freelancer details added successfully",
  "Success": true,
  "Data": {
    "UserDetailsId": 10,
    "UserId": 5,
    "FirstName": "John",
    "LastName": "Doe"
  }
}
```

---

### 4. Company Details

Add company information for a company account.

**Endpoint:** `POST /SignUpCompanyDetails`

**Request Body:**
```json
{
  "UserId": 6,
  "CompanyName": "GeoTech Solutions Inc.",
  "ContactName": "Jane Smith",
  "CompanySize": "51-200",
  "Industry": "Mining",
  "Website": "https://geotech-solutions.com",
  "Description": "Leading provider of geological services"
}
```

**Success Response (201):**
```json
{
  "StatusCode": 201,
  "Message": "Company details added successfully",
  "Success": true,
  "Data": {
    "CompanyId": 3,
    "UserId": 6,
    "CompanyName": "GeoTech Solutions Inc."
  }
}
```

---

### 5. Log Visitor

Log visitor activity on the platform.

**Endpoint:** `POST /LogVisitor`

**Request Body:**
```json
{
  "IpAddress": "192.168.1.1",
  "UserAgent": "Mozilla/5.0...",
  "PageVisited": "/home",
  "ReferrerUrl": "https://google.com"
}
```

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Visitor logged successfully",
  "Success": true
}
```

---

### 6. LinkedIn OAuth Callback

Handle LinkedIn OAuth authentication callback.

**Endpoint:** `GET /api/auth/linkedin/callback/signup`

**Query Parameters:**
- `code` - OAuth authorization code
- `state` - OAuth state parameter

**Note:** This is typically handled automatically by OAuth flow.

---

## 🔒 Protected Endpoints (Authentication Required)

All endpoints below require a valid JWT token in the Authorization header.

---

## Common Endpoints

### 7. Get Current User Profile

Get the authenticated user's profile information.

**Endpoint:** `GET /me`

**Headers:**
```http
Authorization: Bearer YOUR_JWT_TOKEN
```

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "User profile retrieved successfully",
  "Success": true,
  "Data": {
    "UserId": 1,
    "Email": "admin@geospace.com",
    "RoleId": 1,
    "RoleName": "Admin",
    "UserPosition": "System Administrator",
    "IsActive": true,
    "IsVerified": true,
    "UserDetails": {
      "FirstName": "Admin",
      "LastName": "User",
      "Phone": "+1-555-0101",
      "City": "Toronto",
      "Country": "Canada",
      "ProfileImage": null,
      "Bio": null
    }
  }
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/me" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

---

### 8. Logout

Invalidate the current JWT token.

**Endpoint:** `POST /logout`

**Headers:**
```http
Authorization: Bearer YOUR_JWT_TOKEN
```

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Successfully logged out",
  "Success": true
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/v1/logout" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

---

### 9. Get Menus by Role

Get menu items based on the user's role.

**Endpoint:** `GET /GetMenusByRoleId`

**Query Parameters:**
- `RoleId` (required) - Role ID (1-5)

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Menus retrieved successfully",
  "Success": true,
  "Data": [
    {
      "MenuId": 1,
      "MenuName": "Dashboard",
      "MenuUrl": "/dashboard",
      "Icon": "dashboard-icon",
      "DisplayOrder": 1
    },
    {
      "MenuId": 2,
      "MenuName": "Projects",
      "MenuUrl": "/projects",
      "Icon": "project-icon",
      "DisplayOrder": 2
    }
  ]
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/GetMenusByRoleId?RoleId=1" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

---

### 10. Get Notifications

Get all notifications for the authenticated user.

**Endpoint:** `GET /Notifications`

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Notifications retrieved successfully",
  "Success": true,
  "Data": [
    {
      "NotificationId": 1,
      "UserId": 1,
      "Title": "New Project Assignment",
      "Message": "You have been assigned to Project Alpha",
      "Type": "Project",
      "IsRead": false,
      "CreatedAt": "2024-10-08T10:30:00"
    },
    {
      "NotificationId": 2,
      "UserId": 1,
      "Title": "Timesheet Approved",
      "Message": "Your timesheet for Oct 1-7 has been approved",
      "Type": "Timesheet",
      "IsRead": true,
      "CreatedAt": "2024-10-07T15:45:00"
    }
  ]
}
```

---

### 11. Update Notification

Mark a notification as read.

**Endpoint:** `POST /UpdateNotification`

**Request Body:**
```json
{
  "NotificationId": 1
}
```

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Notification marked as read",
  "Success": true
}
```

---

### 12. Get Dropdown Data by Category

Get dropdown options for various categories.

**Endpoint:** `GET /DropdownDataByCategory`

**Query Parameters:**
- `Category` (required) - Category name (Skills, Countries, Industries, etc.)

**Available Categories:**
- `Skills`
- `Countries`
- `Industries`
- `CompanySizes`
- `ProjectTypes`
- `ProfessionalRoles`
- `SupportAgents`
- `DisputeStatus`

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Dropdown data retrieved successfully",
  "Success": true,
  "Data": [
    {
      "ValueId": 1,
      "DisplayName": "Geologist",
      "Value": "geologist",
      "SortOrder": 1
    },
    {
      "ValueId": 2,
      "DisplayName": "Mining Engineer",
      "Value": "mining-engineer",
      "SortOrder": 2
    }
  ]
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/DropdownDataByCategory?Category=Skills" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

---

## Company Endpoints

Endpoints accessible by users with Company role (RoleId: 3).

---

### 13. Current Projects List

Get list of current/active projects for the company.

**Endpoint:** `GET /company/CurrentProjectList`

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Projects retrieved successfully",
  "Success": true,
  "Data": [
    {
      "ProjectId": 1,
      "ProjectTitle": "Geological Survey - Northern Ontario",
      "ProjectType": "Survey",
      "Status": "Active",
      "BudgetMin": 50000.00,
      "BudgetMax": 75000.00,
      "DurationWeeks": 12,
      "CreatedAt": "2024-09-01T00:00:00"
    }
  ]
}
```

---

### 14. Active Freelancers List

Get list of freelancers currently working on company projects.

**Endpoint:** `GET /company/ActiveFreelancerList`

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Active freelancers retrieved successfully",
  "Success": true,
  "Data": [
    {
      "UserId": 2,
      "FreelancerName": "John Smith",
      "Email": "freelancer@geospace.com",
      "UserPosition": "Geologist",
      "ProjectTitle": "Geological Survey",
      "HourlyRate": 85.00,
      "ContractStatus": "Active"
    }
  ]
}
```

---

### 15. Company Pending Timesheets

Get timesheets pending approval for the company.

**Endpoint:** `GET /company/CompanyPendingTimesheetList`

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Pending timesheets retrieved successfully",
  "Success": true,
  "Data": [
    {
      "TimesheetId": 24,
      "FreelancerName": "John Smith",
      "ProjectTitle": "Geological Survey",
      "WorkDate": "2024-10-04",
      "WorkHours": 8.00,
      "HourlyRate": 60.00,
      "CalculatedAmount": 480.00,
      "TaskDescription": "Site mapping and sample collection",
      "SubmittedAt": "2024-10-04T17:00:00"
    }
  ]
}
```

---

### 16. Company Notifications

Get notifications specific to the company.

**Endpoint:** `GET /company/NotificationList`

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Notifications retrieved successfully",
  "Success": true,
  "Data": [
    {
      "NotificationId": 5,
      "Title": "New Timesheet Submitted",
      "Message": "John Smith submitted timesheet for approval",
      "Type": "Timesheet",
      "IsRead": false,
      "CreatedAt": "2024-10-08T09:15:00"
    }
  ]
}
```

---

### 17. Dashboard Statistics

Get dashboard statistics and metrics for the company.

**Endpoint:** `GET /company/DashboardStats`

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Dashboard statistics retrieved successfully",
  "Success": true,
  "Data": {
    "ActiveProjects": 5,
    "ActiveFreelancers": 12,
    "PendingTimesheets": 8,
    "TotalSpentThisMonth": 45000.00,
    "UpcomingDeadlines": 3
  }
}
```

---

## Admin Endpoints

Endpoints accessible by users with Admin role (RoleId: 1).

---

### 18. Verified Users List

Get list of verified users in the system.

**Endpoint:** `GET /admin/VerifiedUserList`

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Verified users retrieved successfully",
  "Success": true,
  "Data": [
    {
      "UserId": 1,
      "Email": "admin@geospace.com",
      "UserName": "Admin User",
      "RoleName": "Admin",
      "IsVerified": true,
      "IsActive": true,
      "CreatedAt": "2024-01-01T00:00:00"
    }
  ]
}
```

---

### 19. Pending Verification List

Get users pending verification.

**Endpoint:** `GET /admin/PendingVerificationList`

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Pending verifications retrieved successfully",
  "Success": true,
  "Data": [
    {
      "UserId": 10,
      "Email": "newuser@example.com",
      "UserName": "New User",
      "RoleName": "Freelancer",
      "IsVerified": false,
      "SubmittedAt": "2024-10-07T12:00:00"
    }
  ]
}
```

---

### 20. Suspended Accounts List

Get list of suspended user accounts.

**Endpoint:** `GET /admin/SuspendedAccountsList`

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "Suspended accounts retrieved successfully",
  "Success": true,
  "Data": [
    {
      "UserId": 15,
      "Email": "suspended@example.com",
      "UserName": "Suspended User",
      "RoleName": "Freelancer",
      "IsActive": false,
      "SuspendedAt": "2024-09-15T10:30:00",
      "Reason": "Terms violation"
    }
  ]
}
```

---

### 21. Get User Details

Get detailed information about a specific user.

**Endpoint:** `GET /admin/GetUserDetails`

**Query Parameters:**
- `UserId` (required) - User ID

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "User details retrieved successfully",
  "Success": true,
  "Data": {
    "UserId": 2,
    "Email": "freelancer@geospace.com",
    "RoleName": "Freelancer",
    "UserPosition": "Geologist",
    "IsActive": true,
    "IsVerified": true,
    "LastLogin": "2024-10-08T08:00:00",
    "UserDetails": {
      "FirstName": "John",
      "LastName": "Smith",
      "Phone": "+1-555-0102",
      "City": "Vancouver",
      "Country": "Canada",
      "HourlyRate": 85.00,
      "Bio": "Experienced geologist with 10+ years"
    },
    "Stats": {
      "TotalProjects": 15,
      "ActiveProjects": 3,
      "TotalEarnings": 125000.00
    }
  }
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/admin/GetUserDetails?UserId=2" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

---

### 22. Update User Status

Activate or deactivate a user account.

**Endpoint:** `POST /admin/UpdateUserStatus`

**Request Body:**
```json
{
  "UserId": 10,
  "IsActive": false,
  "UpdatedBy": 1,
  "Reason": "Account suspended due to policy violation"
}
```

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "User status updated successfully",
  "Success": true
}
```

---

### 23. Verify User

Verify a user account.

**Endpoint:** `POST /admin/VerifyUser`

**Request Body:**
```json
{
  "UserId": 10,
  "UpdatedBy": 1
}
```

**Success Response (200):**
```json
{
  "StatusCode": 200,
  "Message": "User verified successfully",
  "Success": true
}
```

---

## Timesheet Management Endpoints

Comprehensive timesheet management for tracking work hours and approvals.

---

### 24. Get All Timesheets

Retrieve a paginated list of all timesheets with filters.

**Endpoint:** `GET /admin/timesheets`

**Query Parameters:**
- `per_page` (optional, default: 15) - Records per page
- `status_id` (optional) - Filter by status (1=Pending, 2=Approved, 3=Rejected)
- `contract_id` (optional) - Filter by contract ID
- `user_id` (optional) - Filter by freelancer user ID
- `start_date` (optional, format: YYYY-MM-DD) - Filter from date
- `end_date` (optional, format: YYYY-MM-DD) - Filter to date

**Success Response (200):**
```json
{
  "success": true,
  "message": "Timesheets retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [
      {
        "timesheet_id": 1,
        "contract_id": 5,
        "user_id": 10,
        "work_date": "2024-10-01",
        "day_of_week": "Tuesday",
        "work_hours": 8.00,
        "task_description": "Geological survey and mapping",
        "status_id": 1,
        "status_display_name": "Pending",
        "submitted_at": "2024-10-01T18:00:00",
        "contract_title": "Northern Ontario Survey",
        "contract_hourly_rate": 85.00,
        "project_title": "Geological Survey Project",
        "company_name": "Mining Solutions Inc.",
        "freelancer_name": "John Smith",
        "freelancer_email": "freelancer@geospace.com",
        "calculated_amount": 680.00
      }
    ],
    "per_page": 15,
    "total": 25,
    "last_page": 2
  }
}
```

**cURL Example:**
```bash
curl -X GET "http://localhost:8000/api/v1/admin/timesheets?status_id=1&per_page=20" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

---

### 25. Get Timesheet Details

Retrieve detailed information about a specific timesheet.

**Endpoint:** `GET /admin/timesheets/{id}`

**URL Parameters:**
- `id` (required) - Timesheet ID

**Success Response (200):**
```json
{
  "success": true,
  "message": "Timesheet retrieved successfully",
  "data": {
    "timesheet": {
      "timesheet_id": 1,
      "contract_id": 5,
      "user_id": 10,
      "work_date": "2024-10-01",
      "work_hours": 8.00,
      "task_description": "Geological survey and mapping",
      "status_name": "Pending",
      "approved_at": null,
      "approved_by": null,
      "rejected_reason": null,
      "contract_title": "Northern Ontario Survey",
      "project_title": "Geological Survey Project",
      "company_name": "Mining Solutions Inc.",
      "freelancer_name": "John Smith",
      "calculated_amount": 680.00
    },
    "payments": [],
    "history": []
  }
}
```

**Error Response (404):**
```json
{
  "success": false,
  "message": "Timesheet not found"
}
```

---

### 26. Create Timesheet

Create a new timesheet entry.

**Endpoint:** `POST /admin/timesheets`

**Request Body:**
```json
{
  "contract_id": 5,
  "user_id": 10,
  "work_date": "2024-10-08",
  "work_hours": 8.5,
  "task_description": "Site inspection and sample collection from Zone A"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Timesheet created successfully",
  "data": {
    "timesheet_id": 25,
    "contract_id": 5,
    "user_id": 10,
    "work_date": "2024-10-08",
    "work_hours": 8.5,
    "status_id": 1,
    "created_at": "2024-10-08T10:30:00"
  }
}
```

**Validation Error (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "work_hours": ["Work hours cannot exceed 24 hours"],
    "work_date": ["Cannot submit timesheet for future dates"]
  }
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/v1/admin/timesheets" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "contract_id": 5,
    "user_id": 10,
    "work_date": "2024-10-08",
    "work_hours": 8,
    "task_description": "Geological survey work"
  }'
```

---

### 27. Update Timesheet

Update an existing timesheet (only if not approved).

**Endpoint:** `PUT /admin/timesheets/{id}`

**URL Parameters:**
- `id` (required) - Timesheet ID

**Request Body:**
```json
{
  "work_hours": 9.0,
  "task_description": "Updated: Geological survey, mapping, and analysis"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Timesheet updated successfully",
  "data": {
    "timesheet_id": 25,
    "work_hours": 9.0,
    "task_description": "Updated: Geological survey, mapping, and analysis"
  }
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Cannot update approved timesheet"
}
```

---

### 28. Delete Timesheet

Delete a timesheet (only if not approved and has no payments).

**Endpoint:** `DELETE /admin/timesheets/{id}`

**URL Parameters:**
- `id` (required) - Timesheet ID

**Success Response (200):**
```json
{
  "success": true,
  "message": "Timesheet deleted successfully"
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Cannot delete timesheet with associated payments"
}
```

---

### 29. Approve Timesheet

Approve a pending timesheet and create payment record.

**Endpoint:** `POST /admin/timesheets/{id}/approve`

**URL Parameters:**
- `id` (required) - Timesheet ID

**Success Response (200):**
```json
{
  "success": true,
  "message": "Timesheet approved successfully",
  "data": {
    "timesheet_id": 25,
    "status": "Approved",
    "approved_at": "2024-10-08T14:30:00",
    "payment_created": true,
    "payment_amount": 680.00
  }
}
```

**Error Response (400):**
```json
{
  "success": false,
  "message": "Timesheet is already approved"
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/v1/admin/timesheets/25/approve" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

---

### 30. Reject Timesheet

Reject a timesheet with a reason.

**Endpoint:** `POST /admin/timesheets/{id}/reject`

**URL Parameters:**
- `id` (required) - Timesheet ID

**Request Body:**
```json
{
  "rejected_reason": "Please provide more detailed task description"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Timesheet rejected successfully",
  "data": {
    "timesheet_id": 25,
    "status": "Rejected",
    "rejected_at": "2024-10-08T14:35:00",
    "rejected_reason": "Please provide more detailed task description"
  }
}
```

**cURL Example:**
```bash
curl -X POST "http://localhost:8000/api/v1/admin/timesheets/25/reject" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "rejected_reason": "Please provide more detailed task description"
  }'
```

---

### 31. Get Pending Timesheets

Get all timesheets awaiting approval.

**Endpoint:** `GET /admin/timesheets/pending`

**Query Parameters:**
- `per_page` (optional, default: 15) - Records per page
- `company_id` (optional) - Filter by company ID

**Success Response (200):**
```json
{
  "success": true,
  "message": "Pending timesheets retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [
      {
        "timesheet_id": 24,
        "contract_id": 8,
        "user_id": 15,
        "work_date": "2024-10-04",
        "work_hours": 8.00,
        "task_description": "Backend API development",
        "contract_title": "Mobile App Backend",
        "hourly_rate": 60.00,
        "project_title": "Mobile Application",
        "company_name": "StartUp Inc",
        "freelancer_name": "Jane Smith",
        "calculated_amount": 480.00,
        "submitted_at": "2024-10-04T17:00:00"
      }
    ],
    "total": 8
  }
}
```

---

## Timesheet Status Reference

| Status ID | Status Name | Description |
|-----------|-------------|-------------|
| 1 | Pending | Awaiting approval |
| 2 | Approved | Approved by company/admin |
| 3 | Rejected | Rejected with reason |
| 4 | Under Review | Being reviewed by admin |
| 5 | Disputed | Under dispute resolution |

---

## Error Handling

### Common Errors

**Invalid Token:**
```json
{
  "StatusCode": 401,
  "Message": "Token is invalid or expired",
  "Success": false
}
```

**Permission Denied:**
```json
{
  "StatusCode": 403,
  "Message": "You do not have permission to access this resource",
  "Success": false
}
```

**Resource Not Found:**
```json
{
  "StatusCode": 404,
  "Message": "The requested resource was not found",
  "Success": false
}
```

**Validation Failed:**
```json
{
  "StatusCode": 422,
  "Message": "Validation failed",
  "Success": false,
  "Errors": {
    "Email": ["The email field is required"],
    "Password": ["The password must be at least 6 characters"]
  }
}
```

**Server Error:**
```json
{
  "StatusCode": 500,
  "Message": "An internal server error occurred",
  "Success": false
}
```

---

## Rate Limiting

The API implements rate limiting to prevent abuse:

- **Public Endpoints:** 60 requests per minute
- **Authenticated Endpoints:** 120 requests per minute
- **Admin Endpoints:** 200 requests per minute

**Rate Limit Headers:**
```http
X-RateLimit-Limit: 120
X-RateLimit-Remaining: 115
X-RateLimit-Reset: 1696776000
```

**Rate Limit Exceeded Response:**
```json
{
  "StatusCode": 429,
  "Message": "Too many requests. Please try again later.",
  "Success": false,
  "RetryAfter": 60
}
```

---

## Testing with Different Tools

### cURL Examples

**Basic GET Request:**
```bash
curl -X GET "http://localhost:8000/api/v1/me" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**POST Request with JSON:**
```bash
curl -X POST "http://localhost:8000/api/v1/Login" \
  -H "Content-Type: application/json" \
  -d '{
    "Email": "admin@geospace.com",
    "Password": "password123"
  }'
```

### Postman Setup

1. **Create Environment:**
   - Variable: `base_url` = `http://localhost:8000/api/v1`
   - Variable: `token` = (will be set after login)

2. **Set Headers:**
   ```
   Authorization: Bearer {{token}}
   Content-Type: application/json
   Accept: application/json
   ```

3. **Test Flow:**
   - Login → Save token to environment variable
   - Use `{{token}}` in subsequent requests

### JavaScript/Fetch Example

```javascript
// Login
const login = async () => {
  const response = await fetch('http://localhost:8000/api/v1/Login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      Email: 'admin@geospace.com',
      Password: 'password123'
    })
  });
  const data = await response.json();
  return data.Token;
};

// Get User Profile
const getProfile = async (token) => {
  const response = await fetch('http://localhost:8000/api/v1/me', {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  return await response.json();
};
```

---

## Best Practices

1. **Always use HTTPS in production**
2. **Store tokens securely** (never in local storage for sensitive apps)
3. **Implement token refresh** before expiration
4. **Handle rate limiting** gracefully
5. **Validate data** before sending requests
6. **Use appropriate HTTP methods** (GET, POST, PUT, DELETE)
7. **Include proper error handling** in your application
8. **Log API responses** for debugging
9. **Test with all user roles** to ensure proper access control
10. **Keep credentials secure** (never commit to version control)

---

## Notes

1. **Authentication:** All endpoints except public ones require a valid JWT token
2. **Pagination:** List endpoints return paginated results (default: 15 per page)
3. **Timestamps:** All timestamps are in ISO 8601 format (UTC)
4. **Notifications:** Approval/rejection actions automatically create notifications
5. **Activity Logging:** All CRUD operations are logged in activity_logs table
6. **Payment Creation:** Approving a timesheet automatically creates a payment record
7. **Business Rules:**
   - Can only delete timesheets that are not approved and have no payments
   - User must be assigned to contract to create timesheets
   - Rejected timesheets can be resubmitted after editing
   - Cannot update or delete approved timesheets

---

## Support

For issues, questions, or feature requests:
- Check logs: `storage/logs/laravel.log`
- Use real-time monitoring: `php artisan pail`
- Review main README: `README.md`

---

**API Version:** 1.0  
**Last Updated:** October 2024  
**Documentation Status:** ✅ Complete

---

*For setup instructions and troubleshooting, see [README.md](README.md)*
