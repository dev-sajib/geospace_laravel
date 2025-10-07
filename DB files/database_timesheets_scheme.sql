-- =====================================================
-- UPDATED TIMESHEET WORKFLOW SCHEMA
-- =====================================================

-- Drop existing timesheet-related tables
DROP TABLE IF EXISTS timesheet_day_comments;
DROP TABLE IF EXISTS timesheet_days;
DROP TABLE IF EXISTS payment_requests;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS invoices;
DROP TABLE IF EXISTS timesheets;
DROP TABLE IF EXISTS timesheet_status;

-- =====================================================
-- 1. TIMESHEET STATUS TABLE
-- =====================================================
CREATE TABLE timesheet_status (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) NOT NULL UNIQUE,
    status_description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default statuses
INSERT INTO timesheet_status (status_name, status_description) VALUES
('Pending', 'Timesheet submitted and waiting for company review'),
('Approved', 'Timesheet approved by company'),
('Rejected', 'Timesheet rejected by company'),
('Resubmitted', 'Timesheet resubmitted after rejection'),
('Payment_Requested', 'Freelancer has requested payment'),
('Payment_Processing', 'Admin is processing the payment'),
('Payment_Completed', 'Payment completed successfully');

-- =====================================================
-- 2. TIMESHEETS TABLE (Main timesheet record)
-- =====================================================
CREATE TABLE timesheets (
    timesheet_id INT PRIMARY KEY AUTO_INCREMENT,
    contract_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    company_id INT NOT NULL,
    project_id INT NOT NULL,
    
    -- Date range
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

-- =====================================================
-- 3. TIMESHEET DAYS TABLE (7 days breakdown)
-- =====================================================
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

-- =====================================================
-- 4. TIMESHEET DAY COMMENTS TABLE (Comments per day)
-- =====================================================
CREATE TABLE timesheet_day_comments (
    comment_id INT PRIMARY KEY AUTO_INCREMENT,
    day_id INT NOT NULL,
    timesheet_id INT NOT NULL,
    
    -- Comment details
    comment_by INT NOT NULL, -- User ID who made the comment
    comment_type ENUM('Company', 'Freelancer') NOT NULL,
    comment_text TEXT NOT NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (day_id) REFERENCES timesheet_days(day_id) ON DELETE CASCADE,
    FOREIGN KEY (timesheet_id) REFERENCES timesheets(timesheet_id) ON DELETE CASCADE,
    FOREIGN KEY (comment_by) REFERENCES users(user_id) ON DELETE CASCADE
);

-- =====================================================
-- 5. INVOICES TABLE (Generated after timesheet approval)
-- =====================================================
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

-- =====================================================
-- 6. PAYMENT REQUESTS TABLE (Freelancer payment requests)
-- =====================================================
CREATE TABLE payment_requests (
    request_id INT PRIMARY KEY AUTO_INCREMENT,
    timesheet_id INT NOT NULL,
    invoice_id INT NOT NULL,
    freelancer_id INT NOT NULL,
    company_id INT NOT NULL,
    
    -- Request details
    requested_amount DECIMAL(12,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'CAD',
    
    -- Status tracking
    status ENUM('Pending', 'Approved', 'Rejected', 'Completed') DEFAULT 'Pending',
    
    -- Admin processing
    processed_by INT NULL, -- Admin user ID
    processed_at TIMESTAMP NULL,
    admin_notes TEXT,
    
    -- Rejection details
    rejection_reason TEXT NULL,
    rejected_at TIMESTAMP NULL,
    
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (timesheet_id) REFERENCES timesheets(timesheet_id) ON DELETE CASCADE,
    FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id) ON DELETE CASCADE,
    FOREIGN KEY (freelancer_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (company_id) REFERENCES company_details(company_id) ON DELETE CASCADE,
    FOREIGN KEY (processed_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- =====================================================
-- 7. PAYMENTS TABLE (Actual payment records)
-- =====================================================
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    payment_request_id INT NULL,
    invoice_id INT NOT NULL,
    timesheet_id INT NOT NULL,
    
    -- Payment details
    payment_type ENUM('Company_To_Platform', 'Platform_To_Freelancer') NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'CAD',
    
    -- Transaction details
    transaction_id VARCHAR(255) UNIQUE,
    payment_method VARCHAR(50),
    payment_gateway VARCHAR(50),
    
    -- Status
    status ENUM('Pending', 'Processing', 'Completed', 'Failed', 'Refunded') DEFAULT 'Pending',
    
    -- Payment tracking
    initiated_by INT NULL,
    verified_by INT NULL, -- Admin who verified
    
    due_date DATE,
    paid_at TIMESTAMP NULL,
    verified_at TIMESTAMP NULL,
    
    -- Additional info
    payment_notes TEXT,
    admin_notes TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (payment_request_id) REFERENCES payment_requests(request_id) ON DELETE SET NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(invoice_id) ON DELETE CASCADE,
    FOREIGN KEY (timesheet_id) REFERENCES timesheets(timesheet_id) ON DELETE CASCADE,
    FOREIGN KEY (initiated_by) REFERENCES users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (verified_by) REFERENCES users(user_id) ON DELETE SET NULL
);

-- =====================================================
-- 8. FREELANCER EARNINGS TABLE (Track total income)
-- =====================================================
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
-- INDEXES FOR PERFORMANCE
-- =====================================================

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