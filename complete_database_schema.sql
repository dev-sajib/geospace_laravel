-- =====================================================
-- GEOSPACE DATABASE - COMPLETE SCHEMA
-- Comprehensive Timesheet Workflow System
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS geospace_db;
USE geospace_db;

-- =====================================================
-- 1. CORE USER MANAGEMENT TABLES
-- =====================================================

-- Roles table
CREATE TABLE roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    role_description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Users table
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    user_position VARCHAR(100),
    auth_provider VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    is_verified BOOLEAN DEFAULT FALSE,
    email_verified_at TIMESTAMP NULL,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE RESTRICT
);

-- User details table
CREATE TABLE user_details (
    user_details_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    postal_code VARCHAR(20),
    country VARCHAR(100),
    profile_image VARCHAR(500),
    bio TEXT,
    linkedin_url VARCHAR(500),
    website_url VARCHAR(500),
    resume_or_cv VARCHAR(500),
    hourly_rate DECIMAL(10,2),
    availability_status ENUM('Available', 'Busy', 'Unavailable') DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Company details table
CREATE TABLE company_details (
    company_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    company_type VARCHAR(100),
    industry VARCHAR(100),
    company_size ENUM('1-10', '11-50', '51-200', '201-500', '500+'),
    website VARCHAR(500),
    description TEXT,
    founded_year INT,
    headquarters VARCHAR(255),
    logo VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =====================================================
-- 2. PROJECT AND CONTRACT MANAGEMENT
-- =====================================================

-- Projects table
CREATE TABLE projects (
    project_id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    project_title VARCHAR(255) NOT NULL,
    project_description TEXT NOT NULL,
    project_type VARCHAR(100),
    budget_min DECIMAL(12,2),
    budget_max DECIMAL(12,2),
    currency VARCHAR(3) DEFAULT 'CAD',
    duration_weeks INT,
    status ENUM('Draft', 'Published', 'In Progress', 'Completed', 'Cancelled') DEFAULT 'Draft',
    skills_required JSON,
    location VARCHAR(255),
    is_remote BOOLEAN DEFAULT FALSE,
    deadline DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES company_details(company_id) ON DELETE CASCADE
);

-- Contracts table
CREATE TABLE contracts (
    contract_id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    company_id INT NOT NULL,
    contract_title VARCHAR(255) NOT NULL,
    contract_description TEXT,
    hourly_rate DECIMAL(10,2),
    total_amount DECIMAL(12,2),
    start_date DATE,
    end_date DATE,
    status ENUM('Pending', 'Active', 'Completed', 'Cancelled', 'Disputed') DEFAULT 'Pending',
    payment_terms VARCHAR(255),
    milestones JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES company_details(company_id) ON DELETE CASCADE
);

-- =====================================================
-- 3. TIMESHEET WORKFLOW SYSTEM
-- =====================================================

-- Timesheet status table
CREATE TABLE timesheet_status (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL UNIQUE,
    status_description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Timesheets table (Main timesheet record for 7 days period)
CREATE TABLE timesheets (
    timesheet_id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    company_id INT NOT NULL,
    project_id INT NOT NULL,
    
    -- Date range (7 days period)
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    
    -- Status tracking
    status_id INT DEFAULT 1,
    status_display_name VARCHAR(50),
    
    -- Total calculations
    total_hours DECIMAL(6,2) NOT NULL DEFAULT 0,
    hourly_rate DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    
    -- Submission tracking
    submitted_at TIMESTAMP NULL,
    reviewed_at TIMESTAMP NULL,
    reviewed_by INT NULL,
    
    -- Payment tracking
    payment_requested_at TIMESTAMP NULL,
    payment_completed_at TIMESTAMP NULL,
    
    -- Resubmission tracking
    resubmission_count INT DEFAULT 0,
    last_resubmitted_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES company_details(company_id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (status_id) REFERENCES timesheet_status(status_id),
    FOREIGN KEY (reviewed_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Timesheet days table (7 days breakdown)
CREATE TABLE timesheet_days (
    day_id INT PRIMARY KEY AUTO_INCREMENT,
    timesheet_id INT NOT NULL,
    
    -- Day information
    work_date DATE NOT NULL,
    day_name VARCHAR(20) NOT NULL, -- Monday, Tuesday, etc.
    day_number INT NOT NULL, -- 1-7
    
    -- Hours worked
    hours_worked DECIMAL(4,2) NOT NULL DEFAULT 0,
    
    -- Task description for this day
    task_description TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (timesheet_id) REFERENCES timesheets(timesheet_id) ON DELETE CASCADE
);

-- Timesheet day comments table (Comments per day)
CREATE TABLE timesheet_day_comments (
    comment_id INT PRIMARY KEY AUTO_INCREMENT,
    day_id INT NOT NULL,
    timesheet_id INT NOT NULL,
    
    -- Comment details
    comment_by INT NOT NULL,
    comment_type ENUM('Company', 'Freelancer') NOT NULL,
    comment_text TEXT NOT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (day_id) REFERENCES timesheet_days(day_id) ON DELETE CASCADE,
    FOREIGN KEY (timesheet_id) REFERENCES timesheets(timesheet_id) ON DELETE CASCADE,
    FOREIGN KEY (comment_by) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =====================================================
-- 4. INVOICE AND PAYMENT SYSTEM
-- =====================================================

-- Invoices table (Auto-generated after timesheet approval)
CREATE TABLE invoices (
    invoice_id INT PRIMARY KEY AUTO_INCREMENT,
    timesheet_id INT NOT NULL,
    contract_id INT NOT NULL,
    company_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    
    -- Invoice details
    invoice_number VARCHAR(100) UNIQUE NOT NULL,
    invoice_date DATE NOT NULL,
    
    -- Amount details
    total_hours DECIMAL(6,2) NOT NULL,
    hourly_rate DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(12,2) NOT NULL,
    tax_amount DECIMAL(12,2) DEFAULT 0,
    total_amount DECIMAL(12,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'CAD',
    
    -- Status
    status ENUM('Generated', 'Sent', 'Paid', 'Overdue', 'Cancelled') DEFAULT 'Generated',
    
    -- Payment tracking
    due_date DATE,
    sent_at TIMESTAMP NULL,
    paid_at TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (timesheet_id) REFERENCES timesheets(timesheet_id) ON DELETE CASCADE,
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES company_details(company_id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Payment requests table (Freelancer payment requests)
CREATE TABLE payment_requests (
    request_id INT PRIMARY KEY AUTO_INCREMENT,
    timesheet_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    invoice_id INT NULL,
    
    -- Request details
    amount DECIMAL(12,2) NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected', 'Processing', 'Completed') DEFAULT 'Pending',
    
    -- Admin processing
    processed_by INT NULL,
    processed_at TIMESTAMP NULL,
    admin_notes TEXT,
    
    -- Rejection details
    rejection_reason TEXT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (timesheet_id) REFERENCES timesheets(timesheet_id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id) ON DELETE SET NULL,
    FOREIGN KEY (processed_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Payments table (Company and freelancer payments)
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_id INT NULL,
    timesheet_id INT NULL,
    payment_request_id INT NULL,
    
    -- Payment type
    payment_type ENUM('Company_to_Platform', 'Platform_to_Freelancer') NOT NULL,
    
    -- Payment details
    amount DECIMAL(12,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'CAD',
    status ENUM('Pending', 'Completed', 'Failed', 'Refunded') DEFAULT 'Pending',
    
    -- Transaction details
    transaction_id VARCHAR(255),
    payment_method VARCHAR(100),
    payment_date TIMESTAMP NULL,
    
    -- Verification details
    verified_by INT NULL,
    verified_at TIMESTAMP NULL,
    verification_notes TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id) ON DELETE SET NULL,
    FOREIGN KEY (timesheet_id) REFERENCES timesheets(timesheet_id) ON DELETE SET NULL,
    FOREIGN KEY (payment_request_id) REFERENCES payment_requests(request_id) ON DELETE SET NULL,
    FOREIGN KEY (verified_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Freelancer earnings table (Track total income)
CREATE TABLE freelancer_earnings (
    earning_id INT PRIMARY KEY AUTO_INCREMENT,
    freelancer_id INT NOT NULL,
    
    -- Earnings breakdown
    total_earned DECIMAL(15,2) DEFAULT 0,
    pending_amount DECIMAL(15,2) DEFAULT 0,
    completed_amount DECIMAL(15,2) DEFAULT 0,
    
    -- Stats
    total_projects INT DEFAULT 0,
    total_timesheets INT DEFAULT 0,
    
    last_payment_date TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (freelancer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE KEY unique_freelancer (freelancer_id)
);

-- =====================================================
-- 5. NOTIFICATION SYSTEM
-- =====================================================

-- Notifications table
CREATE TABLE notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('Info', 'Success', 'Warning', 'Error') DEFAULT 'Info',
    action_url VARCHAR(500),
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =====================================================
-- 6. DISPUTE SYSTEM
-- =====================================================

-- Dispute status table
CREATE TABLE dispute_status (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL UNIQUE,
    status_description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dispute tickets table
CREATE TABLE dispute_tickets (
    ticket_id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT NOT NULL,
    created_by INT NOT NULL,
    assigned_to INT NULL,
    status_id INT DEFAULT 1,
    priority ENUM('Low', 'Medium', 'High', 'Critical') DEFAULT 'Medium',
    category VARCHAR(100),
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    resolution TEXT NULL,
    resolved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (status_id) REFERENCES dispute_status(status_id)
);

-- Dispute messages table
CREATE TABLE dispute_messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT NOT NULL,
    sender_id INT NOT NULL,
    message_text TEXT NOT NULL,
    attachment_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES dispute_tickets(ticket_id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =====================================================
-- 7. SUPPORT AND CHAT SYSTEM
-- =====================================================

-- Live chat sessions table
CREATE TABLE chat_sessions (
    session_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    support_agent_id INT NULL,
    status ENUM('Active', 'Closed', 'Waiting') DEFAULT 'Waiting',
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ended_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (support_agent_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Chat messages table
CREATE TABLE chat_messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    session_id INT NOT NULL,
    sender_id INT NOT NULL,
    message_text TEXT NOT NULL,
    message_type ENUM('Text', 'Image', 'File') DEFAULT 'Text',
    attachment_url VARCHAR(500),
    is_from_agent BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES chat_sessions(session_id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =====================================================
-- 8. MENU SYSTEM
-- =====================================================

-- Menu items table
CREATE TABLE menu_items (
    menu_id INT PRIMARY KEY AUTO_INCREMENT,
    parent_menu_id INT NULL,
    menu_name VARCHAR(100) NOT NULL,
    menu_url VARCHAR(500),
    menu_icon VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_menu_id) REFERENCES menu_items(menu_id) ON DELETE CASCADE
);

-- Role menu access table
CREATE TABLE role_menu_access (
    access_id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    menu_id INT NOT NULL,
    can_view BOOLEAN DEFAULT TRUE,
    can_edit BOOLEAN DEFAULT FALSE,
    can_delete BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menu_items(menu_id) ON DELETE CASCADE,
    UNIQUE KEY unique_role_menu (role_id, menu_id)
);

-- =====================================================
-- 9. FILE MANAGEMENT
-- =====================================================

-- File uploads table
CREATE TABLE file_uploads (
    file_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    stored_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    file_category ENUM('Profile', 'Resume', 'Project', 'Invoice', 'Other') DEFAULT 'Other',
    entity_type VARCHAR(100),
    entity_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =====================================================
-- 10. SYSTEM LOGGING AND AUDIT
-- =====================================================

-- Visitor logs table
CREATE TABLE visitor_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    role_id INT NOT NULL,
    device_info TEXT,
    ip_address VARCHAR(45),
    page_visited VARCHAR(500),
    session_duration INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE RESTRICT
);

-- Activity logs table
CREATE TABLE activity_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    action VARCHAR(255) NOT NULL,
    entity_type VARCHAR(100),
    entity_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
);

-- =====================================================
-- INDEXES FOR PERFORMANCE
-- =====================================================

-- User indexes
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role_id ON users(role_id);
CREATE INDEX idx_users_is_active ON users(is_active);

-- Contract indexes
CREATE INDEX idx_contracts_project_id ON contracts(project_id);
CREATE INDEX idx_contracts_freelancer_id ON contracts(freelancer_id);
CREATE INDEX idx_contracts_company_id ON contracts(company_id);
CREATE INDEX idx_contracts_status ON contracts(status);

-- Timesheet indexes
CREATE INDEX idx_timesheets_freelancer ON timesheets(freelancer_id);
CREATE INDEX idx_timesheets_company ON timesheets(company_id);
CREATE INDEX idx_timesheets_project ON timesheets(project_id);
CREATE INDEX idx_timesheets_status ON timesheets(status_id);
CREATE INDEX idx_timesheets_dates ON timesheets(start_date, end_date);

-- Timesheet days indexes
CREATE INDEX idx_timesheet_days_timesheet ON timesheet_days(timesheet_id);
CREATE INDEX idx_timesheet_days_date ON timesheet_days(work_date);

-- Comments indexes
CREATE INDEX idx_comments_day ON timesheet_day_comments(day_id);
CREATE INDEX idx_comments_timesheet ON timesheet_day_comments(timesheet_id);
CREATE INDEX idx_comments_type ON timesheet_day_comments(comment_type);

-- Invoice indexes
CREATE INDEX idx_invoices_timesheet ON invoices(timesheet_id);
CREATE INDEX idx_invoices_company ON invoices(company_id);
CREATE INDEX idx_invoices_freelancer ON invoices(freelancer_id);
CREATE INDEX idx_invoices_status ON invoices(status);
CREATE INDEX idx_invoices_number ON invoices(invoice_number);

-- Payment request indexes
CREATE INDEX idx_payment_requests_timesheet ON payment_requests(timesheet_id);
CREATE INDEX idx_payment_requests_freelancer ON payment_requests(freelancer_id);
CREATE INDEX idx_payment_requests_status ON payment_requests(status);

-- Payment indexes
CREATE INDEX idx_payments_invoice ON payments(invoice_id);
CREATE INDEX idx_payments_timesheet ON payments(timesheet_id);
CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_payments_transaction ON payments(transaction_id);

-- Earnings indexes
CREATE INDEX idx_earnings_freelancer ON freelancer_earnings(freelancer_id);

-- Notification indexes
CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_is_read ON notifications(is_read);
CREATE INDEX idx_notifications_created_at ON notifications(created_at);

-- Dispute indexes
CREATE INDEX idx_dispute_tickets_contract_id ON dispute_tickets(contract_id);
CREATE INDEX idx_dispute_tickets_created_by ON dispute_tickets(created_by);
CREATE INDEX idx_dispute_tickets_status_id ON dispute_tickets(status_id);

-- Visitor log indexes
CREATE INDEX idx_visitor_logs_user_id ON visitor_logs(user_id);
CREATE INDEX idx_visitor_logs_role_id ON visitor_logs(role_id);
CREATE INDEX idx_visitor_logs_created_at ON visitor_logs(created_at);
