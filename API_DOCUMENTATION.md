# GeoSpace Laravel API Documentation

**Base URL**: `http://127.0.0.1:8001/api/v1`

## Authentication
Most endpoints require JWT authentication. Include the token in the Authorization header:
```
Authorization: Bearer YOUR_JWT_TOKEN
```

---

## 🔓 **Public Endpoints (No Authentication Required)**

### 1. **User Login**
- **URL**: `POST /Login`
- **Description**: Authenticate user and get JWT token
- **Body**:
```json
{
  "Email": "test@example.com",
  "Password": "test123"
}
```
- **Response**:
```json
{
  "Token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "UserDetails": {
    "UserId": 5,
    "UserName": "test@example.com",
    "Email": "test@example.com",
    "RoleId": 1,
    "RoleName": "Admin"
  }
}
```

### 2. **Freelancer Signup**
- **URL**: `POST /SignUpFreelancer`
- **Description**: Register a new freelancer
- **Body**:
```json
{
  "UserPosition": "Geologist",
  "UserName": "John Doe",
  "Email": "john@example.com",
  "PasswordHash": "password123",
  "RoleId": 2,
  "AuthProvider": "Manual"
}
```

### 3. **Freelancer Details**
- **URL**: `POST /SignUpFreelancerDetails`
- **Description**: Add detailed information for freelancer
- **Body**:
```json
{
  "UserId": 1,
  "FirstName": "John",
  "LastName": "Doe",
  "CellNumber": "+1234567890",
  "Country": "Canada",
  "City": "Toronto"
}
```

### 4. **Company Details**
- **URL**: `POST /SignUpCompanyDetails`
- **Description**: Add company information
- **Body**:
```json
{
  "UserId": 1,
  "CompanyName": "GeoTech Solutions",
  "ContactName": "Jane Smith",
  "CompanySize": "11-50"
}
```

### 5. **LinkedIn OAuth Callback**
- **URL**: `GET /api/auth/linkedin/callback/signup`
- **Description**: Handle LinkedIn OAuth signup callback

---

## 🔒 **Protected Endpoints (Authentication Required)**

### **Common Endpoints**

### 6. **Get User Profile**
- **URL**: `GET /me`
- **Description**: Get authenticated user's profile information

### 7. **Logout**
- **URL**: `POST /logout`
- **Description**: Invalidate JWT token

### 8. **Get Menus by Role**
- **URL**: `GET /GetMenusByRoleId?RoleId=1`
- **Description**: Get menu items based on user role
- **Parameters**: `RoleId` (integer)

### 9. **Get Notifications**
- **URL**: `GET /Notifications`
- **Description**: Get all notifications for the user

### 10. **Update Notification**
- **URL**: `POST /UpdateNotification`
- **Description**: Mark notification as read
- **Body**:
```json
{
  "NotificationId": 1
}
```

### 11. **Get Dropdown Data**
- **URL**: `GET /DropdownDataByCategory?Category=Skills`
- **Description**: Get dropdown options by category
- **Parameters**: `Category` (string) - e.g., "Skills", "Countries", "Industries"

---

### **Company Endpoints**

### 12. **Current Projects**
- **URL**: `GET /company/CurrentProjectList`
- **Description**: Get list of current/active projects

### 13. **Active Freelancers**
- **URL**: `GET /company/ActiveFreelancerList`
- **Description**: Get list of active freelancers

### 14. **Pending Timesheets**
- **URL**: `GET /company/CompanyPendingTimesheetList`
- **Description**: Get pending timesheets for approval

### 15. **Company Notifications**
- **URL**: `GET /company/NotificationList`
- **Description**: Get company-specific notifications

### 16. **Dashboard Statistics**
- **URL**: `GET /company/DashboardStats`
- **Description**: Get dashboard statistics and metrics

---

### **Admin Endpoints**

### 17. **Verified Users**
- **URL**: `GET /admin/VerifiedUserList`
- **Description**: Get list of verified users

### 18. **Pending Verification**
- **URL**: `GET /admin/PendingVerificationList`
- **Description**: Get users pending verification

### 19. **Suspended Accounts**
- **URL**: `GET /admin/SuspendedAccountsList`
- **Description**: Get list of suspended user accounts

### 20. **Get User Details**
- **URL**: `GET /admin/GetUserDetails?UserId=1`
- **Description**: Get detailed information about a specific user
- **Parameters**: `UserId` (integer)

### 21. **Update User Status**
- **URL**: `POST /admin/UpdateUserStatus`
- **Description**: Activate/deactivate user account
- **Body**:
```json
{
  "UserId": 1,
  "IsActive": true,
  "UpdatedBy": 1
}
```

### 22. **Verify User**
- **URL**: `POST /admin/VerifyUser`
- **Description**: Verify a user account
- **Body**:
```json
{
  "UserId": 1,
  "UpdatedBy": 1
}
```

### 23. **Timesheets Managment**

### Base URL
```
/api/v1/admin
```

All endpoints require authentication using JWT token in the Authorization header:
```
Authorization: Bearer {token}
```

---

### Endpoints

### 1. Get All Timesheets
**GET** `/timesheets`

Retrieve a paginated list of all timesheets with filters.

**Query Parameters:**
- `per_page` (optional, default: 15) - Number of records per page
- `status_id` (optional) - Filter by status (1=Pending, 2=Approved, 3=Rejected)
- `contract_id` (optional) - Filter by contract ID
- `user_id` (optional) - Filter by freelancer user ID
- `start_date` (optional, format: YYYY-MM-DD) - Filter from this date
- `end_date` (optional, format: YYYY-MM-DD) - Filter until this date

**Example Request:**
```bash
GET /api/v1/admin/timesheets?status_id=1&per_page=20
```

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
        "task_description": "Completed frontend development",
        "status_id": 1,
        "status_display_name": "Pending",
        "submitted_at": "2024-10-01T18:00:00",
        "contract_title": "Website Development",
        "contract_hourly_rate": 50.00,
        "project_title": "E-commerce Platform",
        "company_name": "Tech Corp",
        "freelancer_name": "John Doe",
        "freelancer_email": "john@example.com",
        "calculated_amount": 400.00
      }
    ],
    "total": 25
  }
}
```

---

### 2. Get Timesheet Details
**GET** `/timesheets/{id}`

Retrieve detailed information about a specific timesheet.

**URL Parameters:**
- `id` (required) - Timesheet ID

**Example Request:**
```bash
GET /api/v1/admin/timesheets/1
```

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
      "task_description": "Completed frontend development",
      "status_name": "Pending",
      "contract_title": "Website Development",
      "project_title": "E-commerce Platform",
      "company_name": "Tech Corp",
      "freelancer_name": "John Doe",
      "calculated_amount": 400.00
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

### 3. Create Timesheet
**POST** `/timesheets`

Create a new timesheet entry.

**Request Body:**
```json
{
  "contract_id": 5,
  "user_id": 10,
  "work_date": "2024-10-05",
  "work_hours": 7.5,
  "task_description": "Implemented user authentication module and fixed bugs",
  "status_id": 1
}
```

**Validation Rules:**
- `contract_id`: required, integer, must exist in contracts table
- `user_id`: required, integer, must exist in users table
- `work_date`: required, valid date
- `work_hours`: required, numeric, between 0.25 and 24
- `task_description`: required, string, max 1000 characters
- `status_id`: optional, integer, must exist in timesheet_status table

**Success Response (201):**
```json
{
  "success": true,
  "message": "Timesheet created successfully",
  "data": {
    "timesheet_id": 26,
    "contract_id": 5,
    "user_id": 10,
    "work_date": "2024-10-05",
    "day_of_week": "Saturday",
    "work_hours": 7.50,
    "task_description": "Implemented user authentication module and fixed bugs",
    "status_id": 1,
    "status_display_name": "Pending",
    "submitted_at": "2024-10-05T14:30:00"
  }
}
```

**Error Response (422):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "work_hours": ["The work hours must be between 0.25 and 24."]
  }
}
```

---

### 4. Update Timesheet
**PUT/PATCH** `/timesheets/{id}`

Update an existing timesheet.

**URL Parameters:**
- `id` (required) - Timesheet ID

**Request Body:**
```json
{
  "work_hours": 8.0,
  "task_description": "Updated task description with more details",
  "status_id": 1
}
```

**Validation Rules:**
- `work_date`: optional, valid date
- `work_hours`: optional, numeric, between 0.25 and 24
- `task_description`: optional, string, max 1000 characters
- `status_id`: optional, integer, must exist in timesheet_status table
- `rejected_reason`: optional, string, max 500 characters

**Success Response (200):**
```json
{
  "success": true,
  "message": "Timesheet updated successfully",
  "data": {
    "timesheet_id": 1,
    "work_hours": 8.00,
    "task_description": "Updated task description with more details",
    "updated_at": "2024-10-05T15:00:00"
  }
}
```

---

### 5. Approve Timesheet
**POST** `/timesheets/{id}/approve`

Approve a pending timesheet and create payment record.

**URL Parameters:**
- `id` (required) - Timesheet ID

**Example Request:**
```bash
POST /api/v1/admin/timesheets/1/approve
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Timesheet approved successfully",
  "data": {
    "timesheet_id": 1,
    "status_id": 2,
    "status_display_name": "Approved",
    "approved_at": "2024-10-05T16:00:00",
    "approved_by": 3
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

---

### 6. Reject Timesheet
**POST** `/timesheets/{id}/reject`

Reject a timesheet with a reason.

**URL Parameters:**
- `id` (required) - Timesheet ID

**Request Body:**
```json
{
  "rejected_reason": "The work hours don't match the project requirements. Please resubmit with correct hours."
}
```

**Validation Rules:**
- `rejected_reason`: required, string, max 500 characters

**Success Response (200):**
```json
{
  "success": true,
  "message": "Timesheet rejected successfully",
  "data": {
    "timesheet_id": 1,
    "status_id": 3,
    "status_display_name": "Rejected",
    "rejected_reason": "The work hours don't match the project requirements. Please resubmit with correct hours.",
    "approved_by": 3,
    "approved_at": "2024-10-05T16:30:00"
  }
}
```

---

### 7. Delete Timesheet
**DELETE** `/timesheets/{id}`

Delete a timesheet (only if not approved and has no payments).

**URL Parameters:**
- `id` (required) - Timesheet ID

**Example Request:**
```bash
DELETE /api/v1/admin/timesheets/1
```

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
  "message": "Cannot delete approved timesheet. Please reject it first."
}
```

---

### 8. Get Timesheet Statistics
**GET** `/timesheets/stats`

Get statistical overview of timesheets.

**Query Parameters:**
- `contract_id` (optional) - Filter by contract ID
- `user_id` (optional) - Filter by user ID
- `start_date` (optional, format: YYYY-MM-DD) - Filter from this date
- `end_date` (optional, format: YYYY-MM-DD) - Filter until this date

**Example Request:**
```bash
GET /api/v1/admin/timesheets/stats?contract_id=5
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Statistics retrieved successfully",
  "data": {
    "total_timesheets": 150,
    "pending_timesheets": 25,
    "approved_timesheets": 115,
    "rejected_timesheets": 10,
    "total_hours": 1200.50,
    "total_amount": 60025.00,
    "average_hours_per_timesheet": 8.00,
    "by_status": [
      {
        "status_name": "Pending",
        "count": 25
      },
      {
        "status_name": "Approved",
        "count": 115
      },
      {
        "status_name": "Rejected",
        "count": 10
      }
    ]
  }
}
```

---

### 9. Get Pending Timesheets
**GET** `/timesheets/pending`

Get all pending timesheets awaiting approval.

**Query Parameters:**
- `per_page` (optional, default: 15) - Number of records per page
- `company_id` (optional) - Filter by company ID

**Example Request:**
```bash
GET /api/v1/admin/timesheets/pending?company_id=2
```

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
    "total": 25
  }
}
```

---

## Status Codes

- `200 OK` - Request successful
- `201 Created` - Resource created successfully
- `400 Bad Request` - Invalid request or business logic error
- `401 Unauthorized` - Authentication required or token invalid
- `403 Forbidden` - User doesn't have permission
- `404 Not Found` - Resource not found
- `422 Unprocessable Entity` - Validation failed
- `500 Internal Server Error` - Server error

---

## Timesheet Status IDs

- `1` - Pending
- `2` - Approved
- `3` - Rejected

---

## Notes

1. **Authentication**: All endpoints require a valid JWT token
2. **Pagination**: List endpoints return paginated results
3. **Notifications**: Approval/rejection actions automatically create notifications for freelancers
4. **Activity Logging**: All CRUD operations are logged in activity_logs table
5. **Payment Creation**: Approving a timesheet automatically creates a payment record
6. **Business Rules**:
    - Can only delete timesheets that are not approved and have no payments
    - User must be assigned to contract to create timesheets
    - Rejected timesheets can be resubmitted after editing

---

## Example Usage with cURL

### Get all timesheets
```bash
curl -X GET "http://your-domain.com/api/v1/admin/timesheets?per_page=10" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json"
```

### Create timesheet
```bash
curl -X POST "http://your-domain.com/api/v1/admin/timesheets" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "contract_id": 5,
    "user_id": 10,
    "work_date": "2024-10-05",
    "work_hours": 8,
    "task_description": "Completed frontend development tasks"
  }'
```

### Approve timesheet
```bash
curl -X POST "http://your-domain.com/api/v1/admin/timesheets/1/approve" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json"
```

### Reject timesheet
```bash
curl -X POST "http://your-domain.com/api/v1/admin/timesheets/1/reject" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "rejected_reason": "Please provide more detailed task description"
  }'
```
---

## 📊 **Response Format**

### Success Response:
```json
{
  "StatusCode": 200,
  "Message": "Success",
  "Success": true,
  "Data": {...}
}
```

### Error Response:
```json
{
  "StatusCode": 400,
  "Message": "Error message",
  "Success": false,
  "Errors": {...}
}
```

### Authentication Error:
```json
{
  "StatusCode": 401,
  "Message": "Token invalid or expired",
  "Success": false
}
```

---

## 🧪 **Testing Examples**

### Using cURL:

**Login:**
```bash
curl -X POST "http://127.0.0.1:8001/api/v1/Login" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"Email": "test@example.com", "Password": "test123"}'
```

**Get Protected Data:**
```bash
curl -X GET "http://127.0.0.1:8001/api/v1/DropdownDataByCategory?Category=Skills" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### Using Postman:
1. Set Base URL: `http://127.0.0.1:8001/api/v1`
2. For protected endpoints, add Authorization header: `Bearer YOUR_JWT_TOKEN`
3. Set Content-Type: `application/json` for POST requests

---

## 🔑 **Test Credentials**
- **Email**: `test@example.com`
- **Password**: `test123`
- **Role**: Admin (RoleId: 1)

## 🗄️ **Available Roles**
- **1**: Admin
- **2**: Freelancer  
- **3**: Company
- **4**: Support
- **5**: Visitor

## 📝 **Available Dropdown Categories**
- Skills
- Countries
- Industries
- Company Sizes
- Project Types

