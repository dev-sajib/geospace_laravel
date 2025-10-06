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

