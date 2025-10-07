-- =====================================================
-- GeoSpace Database Schema
-- A comprehensive database schema for the GeoSpace platform
-- =====================================================

-- Create database (uncomment if needed)
CREATE DATABASE geospace_db;
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
    user_position VARCHAR(100), -- Geologist, Engineer, etc.
    auth_provider VARCHAR(50), -- LinkedIn, Google, etc.
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
    skills_required JSON, -- Array of required skills
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
    milestones JSON, -- Array of milestone objects
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES company_details(company_id) ON DELETE CASCADE
);

-- =====================================================
-- 3. TIMESHEET AND WORK TRACKING
-- =====================================================

-- Timesheets table
CREATE TABLE timesheets (
    timesheet_id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT NOT NULL,
    user_id INT NOT NULL, -- Freelancer who submitted
    work_date DATE NOT NULL,
    day_of_week VARCHAR(20),
    work_hours DECIMAL(4,2) NOT NULL,
    task_description TEXT NOT NULL,
    status_id INT DEFAULT 1, -- 1=Pending, 2=Approved, 3=Rejected
    status_display_name VARCHAR(50),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    approved_at TIMESTAMP NULL,
    approved_by INT NULL,
    rejected_reason TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Timesheet status table
CREATE TABLE timesheet_status (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL,
    status_description TEXT,
    is_active BOOLEAN DEFAULT TRUE
);

-- =====================================================
-- 4. PAYMENT AND FINANCIAL MANAGEMENT
-- =====================================================

-- Payments table
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT NOT NULL,
    timesheet_id INT NULL, -- NULL for milestone payments
    amount DECIMAL(12,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'CAD',
    payment_type ENUM('Hourly', 'Milestone', 'Fixed') NOT NULL,
    status ENUM('Pending', 'Processing', 'Completed', 'Failed', 'Refunded') DEFAULT 'Pending',
    payment_method VARCHAR(50),
    transaction_id VARCHAR(255),
    due_date DATE,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id) ON DELETE CASCADE,
    FOREIGN KEY (timesheet_id) REFERENCES timesheets(timesheet_id) ON DELETE SET NULL
);

-- Invoices table
CREATE TABLE invoices (
    invoice_id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT NOT NULL,
    invoice_number VARCHAR(100) UNIQUE NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'CAD',
    status ENUM('Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled') DEFAULT 'Draft',
    due_date DATE,
    sent_at TIMESTAMP NULL,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id) ON DELETE CASCADE
);

-- =====================================================
-- 5. DISPUTE AND SUPPORT SYSTEM
-- =====================================================

-- Dispute tickets table
CREATE TABLE dispute_tickets (
    ticket_id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT NOT NULL,
    created_by INT NOT NULL, -- User who created the dispute
    assigned_agent_id INT NULL,
    subject VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status_id INT DEFAULT 1,
    priority ENUM('Low', 'Medium', 'High', 'Critical') DEFAULT 'Medium',
    category VARCHAR(100),
    resolution_notes TEXT,
    resolved_at TIMESTAMP NULL,
    resolved_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contract_id) REFERENCES contracts(contract_id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_agent_id) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (resolved_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- Dispute messages table
CREATE TABLE dispute_messages (
    message_id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT NOT NULL,
    sender_id INT NOT NULL,
    message_text TEXT NOT NULL,
    attachment_url VARCHAR(500),
    is_internal BOOLEAN DEFAULT FALSE, -- Internal notes vs client messages
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES dispute_tickets(ticket_id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =====================================================
-- 6. NOTIFICATION SYSTEM
-- =====================================================

-- Notifications table
CREATE TABLE notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('Info', 'Warning', 'Error', 'Success') DEFAULT 'Info',
    is_read BOOLEAN DEFAULT FALSE,
    action_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
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
-- 8. SYSTEM LOGGING AND AUDIT
-- =====================================================

-- Visitor logs table
CREATE TABLE visitor_logs (
    log_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    role_id INT NOT NULL,
    device_info TEXT,
    ip_address VARCHAR(45),
    page_visited VARCHAR(500),
    session_duration INT, -- in seconds
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
-- 9. MENU AND PERMISSION SYSTEM
-- =====================================================

-- Menu items table
CREATE TABLE menu_items (
    menu_id INT PRIMARY KEY AUTO_INCREMENT,
    parent_menu_id INT NULL,
    menu_name VARCHAR(100) NOT NULL,
    menu_url VARCHAR(500),
    menu_icon VARCHAR(100),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_menu_id) REFERENCES menu_items(menu_id) ON DELETE SET NULL
);

-- Role permissions table
CREATE TABLE role_permissions (
    permission_id INT PRIMARY KEY AUTO_INCREMENT,
    role_id INT NOT NULL,
    menu_id INT NOT NULL,
    can_view BOOLEAN DEFAULT TRUE,
    can_create BOOLEAN DEFAULT FALSE,
    can_edit BOOLEAN DEFAULT FALSE,
    can_delete BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE,
    FOREIGN KEY (menu_id) REFERENCES menu_items(menu_id) ON DELETE CASCADE,
    UNIQUE KEY unique_role_menu (role_id, menu_id)
);

-- =====================================================
-- 10. BLOG AND CONTENT MANAGEMENT
-- =====================================================

-- Blogs table
CREATE TABLE blogs (
    blog_id INT PRIMARY KEY AUTO_INCREMENT,
    author_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(500),
    category VARCHAR(100),
    tags JSON,
    status ENUM('Draft', 'Published', 'Archived') DEFAULT 'Draft',
    published_at TIMESTAMP NULL,
    view_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =====================================================
-- 11. DROPDOWN DATA MANAGEMENT
-- =====================================================

-- Dropdown categories table
CREATE TABLE dropdown_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dropdown values table
CREATE TABLE dropdown_values (
    value_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT NOT NULL,
    display_name VARCHAR(255) NOT NULL,
    value VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES dropdown_categories(category_id) ON DELETE CASCADE
);

-- =====================================================
-- 12. FILE UPLOAD MANAGEMENT
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
    entity_type VARCHAR(100), -- e.g., 'user', 'contract', 'project'
    entity_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
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
CREATE INDEX idx_timesheets_contract_id ON timesheets(contract_id);
CREATE INDEX idx_timesheets_user_id ON timesheets(user_id);
CREATE INDEX idx_timesheets_work_date ON timesheets(work_date);
CREATE INDEX idx_timesheets_status_id ON timesheets(status_id);

-- Payment indexes
CREATE INDEX idx_payments_contract_id ON payments(contract_id);
CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_payments_due_date ON payments(due_date);

-- Dispute indexes
CREATE INDEX idx_dispute_tickets_contract_id ON dispute_tickets(contract_id);
CREATE INDEX idx_dispute_tickets_created_by ON dispute_tickets(created_by);
CREATE INDEX idx_dispute_tickets_status_id ON dispute_tickets(status_id);

-- Notification indexes
CREATE INDEX idx_notifications_user_id ON notifications(user_id);
CREATE INDEX idx_notifications_is_read ON notifications(is_read);
CREATE INDEX idx_notifications_created_at ON notifications(created_at);

-- Visitor log indexes
CREATE INDEX idx_visitor_logs_user_id ON visitor_logs(user_id);
CREATE INDEX idx_visitor_logs_role_id ON visitor_logs(role_id);
CREATE INDEX idx_visitor_logs_created_at ON visitor_logs(created_at);

