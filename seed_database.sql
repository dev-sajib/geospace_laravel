-- GeoSpace Database Manual Seeding Script

-- Insert Roles
INSERT IGNORE INTO roles (role_id, role_name, role_description, is_active, created_at, updated_at) VALUES
(1, 'Admin', 'System Administrator with full access', 1, NOW(), NOW()),
(2, 'Freelancer', 'Freelancer user who provides services', 1, NOW(), NOW()),
(3, 'Company', 'Company user who posts projects', 1, NOW(), NOW()),
(4, 'Support', 'Support agent for customer service', 1, NOW(), NOW());

-- Insert Timesheet Status
INSERT IGNORE INTO timesheet_status (id, status_name, status_description, created_at, updated_at) VALUES
(1, 'Draft', 'Timesheet in draft state', NOW(), NOW()),
(2, 'Submitted', 'Timesheet submitted for approval', NOW(), NOW()),
(3, 'Approved', 'Timesheet approved by client', NOW(), NOW()),
(4, 'Rejected', 'Timesheet rejected by client', NOW(), NOW()),
(5, 'Paid', 'Timesheet has been paid', NOW(), NOW()),
(6, 'Disputed', 'Timesheet under dispute', NOW(), NOW()),
(7, 'Cancelled', 'Timesheet cancelled', NOW(), NOW());

-- Insert Dispute Status
INSERT IGNORE INTO dispute_status (id, status_name, status_description, created_at, updated_at) VALUES
(1, 'Open', 'Dispute has been opened', NOW(), NOW()),
(2, 'In Progress', 'Dispute is being investigated', NOW(), NOW()),
(3, 'Resolved', 'Dispute has been resolved', NOW(), NOW()),
(4, 'Closed', 'Dispute has been closed', NOW(), NOW());

-- Insert Test Admin User
INSERT IGNORE INTO users (user_id, email, email_verified_at, password_hash, role_id, is_active, created_at, updated_at) VALUES
(1, 'admin@geospace.com', NOW(), '$2y$12$Vogu73ukbhZ0AyJp3YjGjOTTQw2o2aUAe1yKBF9QYvAMXl4JU5m.e', 1, 1, NOW(), NOW());

-- Insert Admin Details
INSERT IGNORE INTO admin_details (user_id, first_name, last_name, phone, department, created_at, updated_at) VALUES
(1, 'System', 'Administrator', '+1234567890', 'IT', NOW(), NOW());

-- Insert Test Freelancer User
INSERT IGNORE INTO users (user_id, email, email_verified_at, password_hash, role_id, is_active, created_at, updated_at) VALUES
(2, 'freelancer@geospace.com', NOW(), '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 1, NOW(), NOW());

-- Insert Test Company User
INSERT IGNORE INTO users (user_id, email, email_verified_at, password_hash, role_id, is_active, created_at, updated_at) VALUES
(3, 'company@geospace.com', NOW(), '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 1, NOW(), NOW());

-- Note: Password for all test accounts is 'password123'