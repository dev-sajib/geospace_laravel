-- =====================================================
-- GEOSPACE DATABASE - DATA SEEDER
-- Comprehensive dummy data for testing the timesheet workflow
-- =====================================================

USE geospace_db;

-- =====================================================
-- 1. ROLES DATA
-- =====================================================

INSERT INTO roles (role_id, role_name, role_description) VALUES
(1, 'Admin', 'System administrator with full access'),
(2, 'Freelancer', 'Freelance geologists and professionals'),
(3, 'Company', 'Companies hiring freelancers'),
(4, 'Support', 'Support agents handling disputes and chat'),
(5, 'Visitor', 'Guest users browsing the platform');

-- =====================================================
-- 2. USERS DATA
-- =====================================================

-- Admin User
INSERT INTO users (user_id, email, password_hash, role_id, user_position, is_active, is_verified, email_verified_at) VALUES
(1, 'admin@geospace.com', '$2y$10$example_hash_admin', 1, 'System Administrator', TRUE, TRUE, NOW());

-- Freelancers (8 freelancers)
INSERT INTO users (user_id, email, password_hash, role_id, user_position, is_active, is_verified, email_verified_at) VALUES
(2, 'john.smith@gmail.com', '$2y$10$example_hash_freelancer1', 2, 'Senior Geologist', TRUE, TRUE, NOW()),
(3, 'sarah.jones@gmail.com', '$2y$10$example_hash_freelancer2', 2, 'Geophysicist', TRUE, TRUE, NOW()),
(4, 'michael.brown@gmail.com', '$2y$10$example_hash_freelancer3', 2, 'Geological Engineer', TRUE, TRUE, NOW()),
(5, 'emily.davis@gmail.com', '$2y$10$example_hash_freelancer4', 2, 'Mining Geologist', TRUE, TRUE, NOW()),
(6, 'david.wilson@gmail.com', '$2y$10$example_hash_freelancer5', 2, 'Environmental Geologist', TRUE, TRUE, NOW()),
(7, 'lisa.martinez@gmail.com', '$2y$10$example_hash_freelancer6', 2, 'Hydrogeologist', TRUE, TRUE, NOW()),
(8, 'robert.anderson@gmail.com', '$2y$10$example_hash_freelancer7', 2, 'Petroleum Geologist', TRUE, TRUE, NOW()),
(9, 'jennifer.taylor@gmail.com', '$2y$10$example_hash_freelancer8', 2, 'Engineering Geologist', TRUE, TRUE, NOW());

-- Company Users (8 companies)
INSERT INTO users (user_id, email, password_hash, role_id, user_position, is_active, is_verified, email_verified_at) VALUES
(10, 'contact@northernmining.com', '$2y$10$example_hash_company1', 3, 'HR Manager', TRUE, TRUE, NOW()),
(11, 'info@geodata.com', '$2y$10$example_hash_company2', 3, 'Project Manager', TRUE, TRUE, NOW()),
(12, 'hr@explorationcorp.com', '$2y$10$example_hash_company3', 3, 'Operations Manager', TRUE, TRUE, NOW()),
(13, 'admin@geoservices.com', '$2y$10$example_hash_company4', 3, 'Director', TRUE, TRUE, NOW()),
(14, 'contact@mineralsolutions.com', '$2y$10$example_hash_company5', 3, 'CEO', TRUE, TRUE, NOW()),
(15, 'info@earthtech.com', '$2y$10$example_hash_company6', 3, 'VP Operations', TRUE, TRUE, NOW()),
(16, 'hr@geologyconsult.com', '$2y$10$example_hash_company7', 3, 'HR Director', TRUE, TRUE, NOW()),
(17, 'contact@resourcegroup.com', '$2y$10$example_hash_company8', 3, 'Project Lead', TRUE, TRUE, NOW());

-- Support Users (2 support agents)
INSERT INTO users (user_id, email, password_hash, role_id, user_position, is_active, is_verified, email_verified_at) VALUES
(18, 'support1@geospace.com', '$2y$10$example_hash_support1', 4, 'Support Agent', TRUE, TRUE, NOW()),
(19, 'support2@geospace.com', '$2y$10$example_hash_support2', 4, 'Senior Support Agent', TRUE, TRUE, NOW());

-- =====================================================
-- 3. USER DETAILS
-- =====================================================

-- Admin Details
INSERT INTO user_details (user_id, first_name, last_name, phone, city, state, country, hourly_rate) VALUES
(1, 'System', 'Administrator', '+1-800-555-0001', 'Toronto', 'Ontario', 'Canada', NULL);

-- Freelancer Details
INSERT INTO user_details (user_id, first_name, last_name, phone, city, state, country, hourly_rate) VALUES
(2, 'John', 'Smith', '+1-416-555-0101', 'Toronto', 'Ontario', 'Canada', 85.00),
(3, 'Sarah', 'Jones', '+1-604-555-0102', 'Vancouver', 'British Columbia', 'Canada', 90.00),
(4, 'Michael', 'Brown', '+1-403-555-0103', 'Calgary', 'Alberta', 'Canada', 80.00),
(5, 'Emily', 'Davis', '+1-514-555-0104', 'Montreal', 'Quebec', 'Canada', 85.00),
(6, 'David', 'Wilson', '+1-416-555-0105', 'Toronto', 'Ontario', 'Canada', 90.00),
(7, 'Lisa', 'Martinez', '+1-604-555-0106', 'Vancouver', 'British Columbia', 'Canada', 95.00),
(8, 'Robert', 'Anderson', '+1-403-555-0107', 'Calgary', 'Alberta', 'Canada', 100.00),
(9, 'Jennifer', 'Taylor', '+1-416-555-0108', 'Toronto', 'Ontario', 'Canada', 75.00);

-- =====================================================
-- 4. COMPANY DETAILS
-- =====================================================

INSERT INTO company_details (company_id, user_id, company_name, company_type, industry, company_size, website, headquarters) VALUES
(1, 10, 'Northern Mining Corp', 'Mining', 'Exploration', '201-500', 'www.northernmining.com', 'Toronto, ON'),
(2, 11, 'GeoData Analytics', 'Technology', 'Geospatial', '51-200', 'www.geodata.com', 'Vancouver, BC'),
(3, 12, 'Exploration Corp International', 'Mining', 'Mineral Exploration', '500+', 'www.explorationcorp.com', 'Calgary, AB'),
(4, 13, 'Geo Services Ltd', 'Consulting', 'Geological Services', '11-50', 'www.geoservices.com', 'Montreal, QC'),
(5, 14, 'Mineral Solutions Inc', 'Mining', 'Resource Development', '51-200', 'www.mineralsolutions.com', 'Toronto, ON'),
(6, 15, 'EarthTech Engineering', 'Engineering', 'Geotechnical', '201-500', 'www.earthtech.com', 'Vancouver, BC'),
(7, 16, 'Geology Consultants', 'Consulting', 'Geological Consulting', '11-50', 'www.geologyconsult.com', 'Calgary, AB'),
(8, 17, 'Resource Exploration Group', 'Mining', 'Exploration', '51-200', 'www.resourcegroup.com', 'Toronto, ON');

-- =====================================================
-- 5. PROJECTS
-- =====================================================

INSERT INTO projects (project_id, company_id, project_title, project_description, project_type, budget_min, budget_max, currency, duration_weeks, status, is_remote) VALUES
(1, 1, 'Northern Ontario Gold Survey', 'Comprehensive geological survey for gold exploration', 'Geological Mapping', 50000, 75000, 'CAD', 12, 'In Progress', FALSE),
(2, 2, 'GIS Data Analysis Project', 'Analyze geological data using GIS technology', 'Data Analysis', 30000, 45000, 'CAD', 8, 'In Progress', TRUE),
(3, 3, 'BC Copper Exploration', 'Copper deposit exploration in British Columbia', 'Mineral Exploration', 80000, 120000, 'CAD', 16, 'In Progress', FALSE),
(4, 4, 'Environmental Impact Assessment', 'Geological assessment for environmental project', 'Environmental', 25000, 35000, 'CAD', 6, 'In Progress', TRUE),
(5, 5, 'Diamond Prospecting Study', 'Diamond prospecting in northern territories', 'Exploration', 60000, 90000, 'CAD', 10, 'In Progress', FALSE),
(6, 6, 'Geotechnical Site Investigation', 'Site investigation for infrastructure project', 'Geotechnical', 40000, 55000, 'CAD', 8, 'In Progress', FALSE),
(7, 7, 'Oil and Gas Basin Analysis', 'Basin analysis for petroleum exploration', 'Petroleum', 70000, 100000, 'CAD', 12, 'In Progress', TRUE),
(8, 8, 'Mineral Resource Estimation', 'Resource estimation for mining project', 'Mining', 45000, 65000, 'CAD', 10, 'In Progress', FALSE);

-- =====================================================
-- 6. CONTRACTS
-- =====================================================

INSERT INTO contracts (contract_id, project_id, freelancer_id, company_id, contract_title, contract_description, hourly_rate, total_amount, start_date, end_date, status) VALUES
(1, 1, 2, 1, 'Gold Survey Contract', 'Geological mapping and sampling', 85.00, 65000.00, '2025-09-01', '2025-11-30', 'Active'),
(2, 2, 3, 2, 'GIS Analysis Contract', 'Data analysis and visualization', 90.00, 45000.00, '2025-09-15', '2025-11-15', 'Active'),
(3, 3, 4, 3, 'Copper Exploration Contract', 'Exploration and sampling', 80.00, 95000.00, '2025-09-08', '2025-12-31', 'Active'),
(4, 4, 5, 4, 'Environmental Assessment Contract', 'Impact assessment and reporting', 85.00, 32000.00, '2025-09-01', '2025-10-15', 'Active'),
(5, 5, 6, 5, 'Diamond Prospecting Contract', 'Prospecting and analysis', 90.00, 75000.00, '2025-09-22', '2025-12-01', 'Active'),
(6, 6, 7, 6, 'Geotechnical Investigation Contract', 'Site investigation and testing', 95.00, 48000.00, '2025-08-25', '2025-10-20', 'Active'),
(7, 7, 8, 7, 'Basin Analysis Contract', 'Petroleum basin analysis', 100.00, 85000.00, '2025-08-18', '2025-11-18', 'Active'),
(8, 8, 9, 8, 'Resource Estimation Contract', 'Mineral resource estimation', 75.00, 55000.00, '2025-10-01', '2025-12-10', 'Active');

-- =====================================================
-- 7. TIMESHEET STATUS
-- =====================================================

INSERT INTO timesheet_status (status_id, status_name, status_description) VALUES
(1, 'Pending', 'Timesheet submitted and waiting for company review'),
(2, 'Approved', 'Timesheet approved by company'),
(3, 'Rejected', 'Timesheet rejected by company'),
(4, 'Resubmitted', 'Timesheet resubmitted after rejection'),
(5, 'Payment_Requested', 'Freelancer has requested payment'),
(6, 'Payment_Processing', 'Admin is processing the payment'),
(7, 'Payment_Completed', 'Payment completed successfully');

-- =====================================================
-- 8. TIMESHEETS (Various statuses for testing)
-- =====================================================

-- Timesheet 1: Pending (just submitted)
INSERT INTO timesheets (timesheet_id, contract_id, freelancer_id, company_id, project_id, start_date, end_date, status_id, status_display_name, total_hours, hourly_rate, total_amount, submitted_at, resubmission_count) VALUES
(1, 1, 2, 1, 1, '2025-09-29', '2025-10-05', 1, 'Pending', 40.00, 85.00, 3400.00, '2025-10-06 09:00:00', 0);

-- Timesheet 2: Rejected (company found issues)
INSERT INTO timesheets (timesheet_id, contract_id, freelancer_id, company_id, project_id, start_date, end_date, status_id, status_display_name, total_hours, hourly_rate, total_amount, submitted_at, reviewed_at, reviewed_by, resubmission_count) VALUES
(2, 2, 3, 2, 2, '2025-09-22', '2025-09-28', 3, 'Rejected', 35.00, 90.00, 3150.00, '2025-09-29 10:00:00', '2025-09-30 14:00:00', 11, 0);

-- Timesheet 3: Resubmitted (freelancer corrected and resubmitted)
INSERT INTO timesheets (timesheet_id, contract_id, freelancer_id, company_id, project_id, start_date, end_date, status_id, status_display_name, total_hours, hourly_rate, total_amount, submitted_at, reviewed_at, reviewed_by, resubmission_count, last_resubmitted_at) VALUES
(3, 3, 4, 3, 3, '2025-09-15', '2025-09-21', 4, 'Resubmitted', 42.00, 80.00, 3360.00, '2025-09-22 08:00:00', '2025-09-24 11:00:00', 12, 1, '2025-09-25 09:30:00');

-- Timesheet 4: Approved (company approved)
INSERT INTO timesheets (timesheet_id, contract_id, freelancer_id, company_id, project_id, start_date, end_date, status_id, status_display_name, total_hours, hourly_rate, total_amount, submitted_at, reviewed_at, reviewed_by, resubmission_count) VALUES
(4, 4, 5, 4, 4, '2025-09-08', '2025-09-14', 2, 'Approved', 38.00, 85.00, 3230.00, '2025-09-15 08:00:00', '2025-09-16 10:00:00', 13, 0);

-- Timesheet 5: Payment Requested (freelancer requested payment)
INSERT INTO timesheets (timesheet_id, contract_id, freelancer_id, company_id, project_id, start_date, end_date, status_id, status_display_name, total_hours, hourly_rate, total_amount, submitted_at, reviewed_at, reviewed_by, payment_requested_at, resubmission_count) VALUES
(5, 5, 6, 5, 5, '2025-09-01', '2025-09-07', 5, 'Payment_Requested', 40.00, 90.00, 3600.00, '2025-09-08 09:00:00', '2025-09-09 10:00:00', 14, '2025-09-10 11:00:00', 0);

-- Timesheet 6: Payment Processing (admin processing)
INSERT INTO timesheets (timesheet_id, contract_id, freelancer_id, company_id, project_id, start_date, end_date, status_id, status_display_name, total_hours, hourly_rate, total_amount, submitted_at, reviewed_at, reviewed_by, payment_requested_at, resubmission_count) VALUES
(6, 6, 7, 6, 6, '2025-08-25', '2025-08-31', 6, 'Payment_Processing', 37.50, 95.00, 3562.50, '2025-09-01 09:00:00', '2025-09-02 10:00:00', 15, '2025-09-03 11:00:00', 0);

-- Timesheet 7: Payment Completed (payment done)
INSERT INTO timesheets (timesheet_id, contract_id, freelancer_id, company_id, project_id, start_date, end_date, status_id, status_display_name, total_hours, hourly_rate, total_amount, submitted_at, reviewed_at, reviewed_by, payment_requested_at, payment_completed_at, resubmission_count) VALUES
(7, 7, 8, 7, 7, '2025-08-18', '2025-08-24', 7, 'Payment_Completed', 40.00, 100.00, 4000.00, '2025-08-25 08:00:00', '2025-08-26 09:00:00', 16, '2025-08-27 10:00:00', '2025-08-30 15:00:00', 0);

-- Timesheet 8: Another Pending
INSERT INTO timesheets (timesheet_id, contract_id, freelancer_id, company_id, project_id, start_date, end_date, status_id, status_display_name, total_hours, hourly_rate, total_amount, submitted_at, resubmission_count) VALUES
(8, 8, 9, 8, 8, '2025-10-01', '2025-10-07', 1, 'Pending', 44.00, 75.00, 3300.00, '2025-10-07 17:30:00', 0);

-- =====================================================
-- 9. TIMESHEET DAYS (7 days for each timesheet)
-- =====================================================

-- Days for Timesheet 1 (Pending)
INSERT INTO timesheet_days (timesheet_id, work_date, day_name, day_number, hours_worked, task_description) VALUES
(1, '2025-09-29', 'Monday', 1, 8.00, 'Initial project setup and requirements analysis'),
(1, '2025-09-30', 'Tuesday', 2, 7.50, 'Database schema design and implementation'),
(1, '2025-10-01', 'Wednesday', 3, 8.00, 'API development for user authentication'),
(1, '2025-10-02', 'Thursday', 4, 6.50, 'Frontend component development'),
(1, '2025-10-03', 'Friday', 5, 6.00, 'Integration testing and bug fixes'),
(1, '2025-10-04', 'Saturday', 6, 2.00, 'Code review and documentation'),
(1, '2025-10-05', 'Sunday', 7, 2.00, 'Final testing and deployment preparation');

-- Days for Timesheet 2 (Rejected - has issues)
INSERT INTO timesheet_days (timesheet_id, work_date, day_name, day_number, hours_worked, task_description) VALUES
(2, '2025-09-22', 'Monday', 1, 8.00, 'Feature development for payment module'),
(2, '2025-09-23', 'Tuesday', 2, 10.00, 'Extended work on complex payment integration'),
(2, '2025-09-24', 'Wednesday', 3, 9.00, 'Bug fixing and refactoring'),
(2, '2025-09-25', 'Thursday', 4, 3.00, 'Team meeting and code review'),
(2, '2025-09-26', 'Friday', 5, 5.00, 'Documentation and testing'),
(2, '2025-09-27', 'Saturday', 6, 0.00, 'No work'),
(2, '2025-09-28', 'Sunday', 7, 0.00, 'No work');

-- Days for Timesheet 3 (Resubmitted - corrected)
INSERT INTO timesheet_days (timesheet_id, work_date, day_name, day_number, hours_worked, task_description) VALUES
(3, '2025-09-15', 'Monday', 1, 8.00, 'Dashboard UI development'),
(3, '2025-09-16', 'Tuesday', 2, 7.00, 'Chart integration and data visualization'),
(3, '2025-09-17', 'Wednesday', 3, 8.00, 'Backend API optimization'),
(3, '2025-09-18', 'Thursday', 4, 7.00, 'Performance testing and improvements'),
(3, '2025-09-19', 'Friday', 5, 8.00, 'Security audit and fixes'),
(3, '2025-09-20', 'Saturday', 6, 2.00, 'Documentation updates'),
(3, '2025-09-21', 'Sunday', 7, 2.00, 'Code cleanup and final review');

-- Days for Timesheet 4 (Approved)
INSERT INTO timesheet_days (timesheet_id, work_date, day_name, day_number, hours_worked, task_description) VALUES
(4, '2025-09-08', 'Monday', 1, 7.50, 'Email notification system development'),
(4, '2025-09-09', 'Tuesday', 2, 8.00, 'SMS integration and testing'),
(4, '2025-09-10', 'Wednesday', 3, 7.50, 'User preference settings implementation'),
(4, '2025-09-11', 'Thursday', 4, 6.00, 'Mobile responsive design fixes'),
(4, '2025-09-12', 'Friday', 5, 7.00, 'Cross-browser compatibility testing'),
(4, '2025-09-13', 'Saturday', 6, 1.00, 'Quick bug fixes'),
(4, '2025-09-14', 'Sunday', 7, 1.00, 'Final deployment checks');

-- Days for Timesheet 5 (Payment Requested)
INSERT INTO timesheet_days (timesheet_id, work_date, day_name, day_number, hours_worked, task_description) VALUES
(5, '2025-09-01', 'Monday', 1, 8.00, 'Search functionality implementation'),
(5, '2025-09-02', 'Tuesday', 2, 8.00, 'Advanced filtering and sorting features'),
(5, '2025-09-03', 'Wednesday', 3, 8.00, 'Database indexing and optimization'),
(5, '2025-09-04', 'Thursday', 4, 8.00, 'Caching layer implementation'),
(5, '2025-09-05', 'Friday', 5, 8.00, 'Load testing and performance tuning'),
(5, '2025-09-06', 'Saturday', 6, 0.00, 'Weekend off'),
(5, '2025-09-07', 'Sunday', 7, 0.00, 'Weekend off');

-- Days for Timesheet 6 (Payment Processing)
INSERT INTO timesheet_days (timesheet_id, work_date, day_name, day_number, hours_worked, task_description) VALUES
(6, '2025-08-25', 'Monday', 1, 7.50, 'Real-time chat feature development'),
(6, '2025-08-26', 'Tuesday', 2, 8.00, 'WebSocket integration and testing'),
(6, '2025-08-27', 'Wednesday', 3, 7.50, 'File upload functionality'),
(6, '2025-08-28', 'Thursday', 4, 7.00, 'Image processing and optimization'),
(6, '2025-08-29', 'Friday', 5, 7.50, 'Unit testing and code coverage'),
(6, '2025-08-30', 'Saturday', 6, 0.00, 'No work'),
(6, '2025-08-31', 'Sunday', 7, 0.00, 'No work');

-- Days for Timesheet 7 (Payment Completed)
INSERT INTO timesheet_days (timesheet_id, work_date, day_name, day_number, hours_worked, task_description) VALUES
(7, '2025-08-18', 'Monday', 1, 8.00, 'Admin panel development'),
(7, '2025-08-19', 'Tuesday', 2, 8.00, 'User management features'),
(7, '2025-08-20', 'Wednesday', 3, 8.00, 'Role and permission system'),
(7, '2025-08-21', 'Thursday', 4, 8.00, 'Analytics and reporting module'),
(7, '2025-08-22', 'Friday', 5, 8.00, 'Export functionality (PDF, Excel)'),
(7, '2025-08-23', 'Saturday', 6, 0.00, 'Weekend off'),
(7, '2025-08-24', 'Sunday', 7, 0.00, 'Weekend off');

-- Days for Timesheet 8 (Another Pending)
INSERT INTO timesheet_days (timesheet_id, work_date, day_name, day_number, hours_worked, task_description) VALUES
(8, '2025-10-01', 'Tuesday', 1, 8.00, 'Requirements gathering and analysis'),
(8, '2025-10-02', 'Wednesday', 2, 8.00, 'System architecture design'),
(8, '2025-10-03', 'Thursday', 3, 8.00, 'Database design and setup'),
(8, '2025-10-04', 'Friday', 4, 8.00, 'Core module development'),
(8, '2025-10-05', 'Saturday', 5, 6.00, 'Testing and bug fixes'),
(8, '2025-10-06', 'Sunday', 6, 3.00, 'Code review and documentation'),
(8, '2025-10-07', 'Monday', 7, 3.00, 'Final adjustments and deployment');

-- =====================================================
-- 10. TIMESHEET DAY COMMENTS
-- =====================================================

-- Comments for Timesheet 2 (Rejected - Company found issues)
INSERT INTO timesheet_day_comments (day_id, timesheet_id, comment_by, comment_type, comment_text) VALUES
(9, 2, 11, 'Company', '10 hours seems excessive for this task. Please clarify or adjust.'),
(10, 2, 11, 'Company', '9 hours for bug fixing seems high. Can you provide more details?'),
(11, 2, 11, 'Company', 'Only 3 hours for a full work day? Please explain.');

-- Comments for Timesheet 3 (Resubmitted - Freelancer responses)
INSERT INTO timesheet_day_comments (day_id, timesheet_id, comment_by, comment_type, comment_text) VALUES
(15, 3, 12, 'Company', 'Original comment: 8 hours seems high for this task'),
(15, 3, 4, 'Freelancer', 'Adjusted from 8 to 7 hours after reviewing the actual time spent'),
(17, 3, 12, 'Company', 'Original comment: Please provide more task breakdown'),
(17, 3, 4, 'Freelancer', 'Added detailed breakdown: 3hrs optimization, 2hrs testing, 2hrs documentation');

-- =====================================================
-- 11. INVOICES (Generated after approval)
-- =====================================================

-- Invoice for Timesheet 4 (Approved)
INSERT INTO invoices (invoice_id, timesheet_id, contract_id, company_id, freelancer_id, invoice_number, invoice_date, total_hours, hourly_rate, subtotal, tax_amount, total_amount, currency, status, due_date) VALUES
(1, 4, 4, 4, 5, 'INV-2025-001', '2025-09-16', 38.00, 85.00, 3230.00, 484.50, 3714.50, 'CAD', 'Generated', '2025-09-30');

-- Invoice for Timesheet 5 (Payment Requested)
INSERT INTO invoices (invoice_id, timesheet_id, contract_id, company_id, freelancer_id, invoice_number, invoice_date, total_hours, hourly_rate, subtotal, tax_amount, total_amount, currency, status, sent_at, due_date) VALUES
(2, 5, 5, 5, 6, 'INV-2025-002', '2025-09-09', 40.00, 90.00, 3600.00, 540.00, 4140.00, 'CAD', 'Sent', '2025-09-10 10:00:00', '2025-09-24');

-- Invoice for Timesheet 6 (Payment Processing)
INSERT INTO invoices (invoice_id, timesheet_id, contract_id, company_id, freelancer_id, invoice_number, invoice_date, total_hours, hourly_rate, subtotal, tax_amount, total_amount, currency, status, sent_at, due_date) VALUES
(3, 6, 6, 6, 7, 'INV-2025-003', '2025-09-02', 37.50, 95.00, 3562.50, 534.38, 4096.88, 'CAD', 'Sent', '2025-09-03 11:00:00', '2025-09-17');

-- Invoice for Timesheet 7 (Payment Completed)
INSERT INTO invoices (invoice_id, timesheet_id, contract_id, company_id, freelancer_id, invoice_number, invoice_date, total_hours, hourly_rate, subtotal, tax_amount, total_amount, currency, status, sent_at, paid_at, due_date) VALUES
(4, 7, 7, 7, 8, 'INV-2025-004', '2025-08-26', 40.00, 100.00, 4000.00, 600.00, 4600.00, 'CAD', 'Paid', '2025-08-27 10:00:00', '2025-08-30 15:00:00', '2025-09-10');

-- =====================================================
-- 12. PAYMENT REQUESTS
-- =====================================================

-- Payment request for Timesheet 5
INSERT INTO payment_requests (request_id, timesheet_id, freelancer_id, invoice_id, amount, status) VALUES
(1, 5, 6, 2, 4140.00, 'Pending');

-- Payment request for Timesheet 6
INSERT INTO payment_requests (request_id, timesheet_id, freelancer_id, invoice_id, amount, status, processed_by, processed_at) VALUES
(2, 6, 7, 3, 4096.88, 'Processing', 1, '2025-09-04 09:00:00');

-- Payment request for Timesheet 7 (completed)
INSERT INTO payment_requests (request_id, timesheet_id, freelancer_id, invoice_id, amount, status, processed_by, processed_at) VALUES
(3, 7, 8, 4, 4600.00, 'Completed', 1, '2025-08-28 14:00:00');

-- =====================================================
-- 13. PAYMENTS
-- =====================================================

-- Company payment for Invoice 4
INSERT INTO payments (payment_id, invoice_id, timesheet_id, payment_type, amount, currency, status, transaction_id, payment_method, payment_date, verified_by, verified_at) VALUES
(1, 4, 7, 'Company_to_Platform', 4600.00, 'CAD', 'Completed', 'TXN-COMP-20250830-001', 'Bank Transfer', '2025-08-30 10:00:00', 1, '2025-08-30 11:00:00');

-- Freelancer payment for Invoice 4
INSERT INTO payments (payment_id, invoice_id, timesheet_id, payment_request_id, payment_type, amount, currency, status, transaction_id, payment_method, payment_date, verified_by, verified_at) VALUES
(2, 4, 7, 3, 'Platform_to_Freelancer', 4600.00, 'CAD', 'Completed', 'TXN-FREEL-20250830-001', 'Bank Transfer', '2025-08-30 15:00:00', 1, '2025-08-30 15:30:00');

-- Company payment for Invoice 3 (processing)
INSERT INTO payments (payment_id, invoice_id, timesheet_id, payment_type, amount, currency, status, transaction_id, payment_method, payment_date, verified_by, verified_at) VALUES
(3, 3, 6, 'Company_to_Platform', 4096.88, 'CAD', 'Completed', 'TXN-COMP-20250903-001', 'Bank Transfer', '2025-09-03 14:00:00', 1, '2025-09-03 14:30:00');

-- =====================================================
-- 14. FREELANCER EARNINGS
-- =====================================================

INSERT INTO freelancer_earnings (freelancer_id, total_earned, pending_amount, completed_amount, total_projects, total_timesheets, last_payment_date) VALUES
(2, 3400.00, 3400.00, 0.00, 1, 1, NULL),
(3, 3150.00, 3150.00, 0.00, 1, 1, NULL),
(4, 3360.00, 3360.00, 0.00, 1, 1, NULL),
(5, 3714.50, 3714.50, 0.00, 1, 1, NULL),
(6, 4140.00, 4140.00, 0.00, 1, 1, NULL),
(7, 4096.88, 0.00, 4096.88, 1, 1, '2025-09-04 09:00:00'),
(8, 4600.00, 0.00, 4600.00, 1, 1, '2025-08-30 15:00:00'),
(9, 3300.00, 3300.00, 0.00, 1, 1, NULL);

-- =====================================================
-- 15. NOTIFICATIONS
-- =====================================================

-- Timesheet submitted notifications
INSERT INTO notifications (user_id, title, message, type, action_url, is_read) VALUES
(10, 'New Timesheet Submitted', 'John Smith has submitted a timesheet for review', 'Info', '/company/home/pending-timesheet', FALSE),
(11, 'Timesheet Rejected', 'Your timesheet for Sept 22-28 has been rejected. Please review comments and resubmit.', 'Warning', '/freelancer/timesheet', FALSE);

-- Timesheet approved notifications
INSERT INTO notifications (user_id, title, message, type, action_url, is_read) VALUES
(5, 'Timesheet Approved', 'Your timesheet for Sept 8-14 has been approved!', 'Success', '/freelancer/timesheet', TRUE);

-- Payment notifications
INSERT INTO notifications (user_id, title, message, type, action_url, is_read) VALUES
(8, 'Payment Completed', 'Payment of $4600.00 has been transferred to your account', 'Success', '/freelancer/earnings/overview', TRUE),
(1, 'Payment Request', 'David Wilson has requested payment for timesheet INV-2025-002', 'Info', '/admin/financial-management/payment-to-freelancer', FALSE);

-- =====================================================
-- 16. DISPUTE STATUS
-- =====================================================

INSERT INTO dispute_status (status_id, status_name, status_description) VALUES
(1, 'Open', 'Dispute ticket is open and awaiting review'),
(2, 'In Progress', 'Dispute is being investigated'),
(3, 'Resolved', 'Dispute has been resolved'),
(4, 'Closed', 'Dispute ticket is closed');

-- =====================================================
-- 17. MENU ITEMS
-- =====================================================

-- Admin Menu Items
INSERT INTO menu_items (menu_id, parent_menu_id, menu_name, menu_url, menu_icon, sort_order) VALUES
(1, NULL, 'Dashboard', '/admin/active-users', 'dashboard', 1),
(2, NULL, 'Users', NULL, 'users', 2),
(3, 2, 'Active Users', '/admin/active-users', 'user-check', 1),
(4, 2, 'Verified Users', '/admin/users/verified', 'user-check-circle', 2),
(5, NULL, 'Timesheets', NULL, 'clock', 3),
(6, 5, 'Pending Timesheets', '/admin/pending-timesheets', 'clock', 1),
(7, 5, 'Approved Timesheets', '/admin/approved-timesheets', 'check-circle', 2),
(8, NULL, 'Financial', NULL, 'dollar-sign', 4),
(9, 8, 'Payment from Company', '/admin/financial-management/payment-from-company', 'credit-card', 1),
(10, 8, 'Payment to Freelancer', '/admin/financial-management/payment-to-freelancer', 'banknote', 2);

-- Company Menu Items
INSERT INTO menu_items (menu_id, parent_menu_id, menu_name, menu_url, menu_icon, sort_order) VALUES
(20, NULL, 'Dashboard', '/company/home/current-projects', 'dashboard', 1),
(21, NULL, 'Projects', NULL, 'briefcase', 2),
(22, 21, 'Current Projects', '/company/home/current-projects', 'folder', 1),
(23, 21, 'Post New Project', '/company/post-project', 'plus-circle', 2),
(24, NULL, 'Timesheets', NULL, 'clock', 3),
(25, 24, 'Pending Timesheets', '/company/home/pending-timesheet', 'clock', 1),
(26, 24, 'Approved Timesheets', '/company/home/approved-timesheet', 'check-circle', 2),
(27, NULL, 'Payments', '/company/payments', 'dollar-sign', 4);

-- Freelancer Menu Items
INSERT INTO menu_items (menu_id, parent_menu_id, menu_name, menu_url, menu_icon, sort_order) VALUES
(40, NULL, 'Dashboard', '/freelancer/home/current-contracts', 'dashboard', 1),
(41, NULL, 'My Work', NULL, 'work', 2),
(42, 41, 'My Contracts', '/freelancer/contracts', 'file-text', 1),
(43, 41, 'Timesheets', '/freelancer/timesheet', 'clock', 2),
(44, NULL, 'Earnings', NULL, 'dollar-sign', 3),
(45, 44, 'Earnings Overview', '/freelancer/earnings/overview', 'trending-up', 1),
(46, 44, 'Invoices & Payments', '/freelancer/earnings/invoices', 'credit-card', 2);

-- =====================================================
-- 18. ROLE MENU ACCESS
-- =====================================================

-- Admin access (full access to admin menus)
INSERT INTO role_menu_access (role_id, menu_id, can_view, can_edit, can_delete) VALUES
(1, 1, TRUE, TRUE, TRUE),
(1, 2, TRUE, TRUE, TRUE),
(1, 3, TRUE, TRUE, TRUE),
(1, 4, TRUE, TRUE, TRUE),
(1, 5, TRUE, TRUE, TRUE),
(1, 6, TRUE, TRUE, TRUE),
(1, 7, TRUE, TRUE, TRUE),
(1, 8, TRUE, TRUE, TRUE),
(1, 9, TRUE, TRUE, TRUE),
(1, 10, TRUE, TRUE, TRUE);

-- Company access (company menus)
INSERT INTO role_menu_access (role_id, menu_id, can_view, can_edit, can_delete) VALUES
(3, 20, TRUE, TRUE, FALSE),
(3, 21, TRUE, TRUE, FALSE),
(3, 22, TRUE, TRUE, FALSE),
(3, 23, TRUE, TRUE, FALSE),
(3, 24, TRUE, TRUE, FALSE),
(3, 25, TRUE, TRUE, FALSE),
(3, 26, TRUE, TRUE, FALSE),
(3, 27, TRUE, FALSE, FALSE);

-- Freelancer access (freelancer menus)
INSERT INTO role_menu_access (role_id, menu_id, can_view, can_edit, can_delete) VALUES
(2, 40, TRUE, TRUE, FALSE),
(2, 41, TRUE, TRUE, FALSE),
(2, 42, TRUE, TRUE, FALSE),
(2, 43, TRUE, TRUE, FALSE),
(2, 44, TRUE, FALSE, FALSE),
(2, 45, TRUE, FALSE, FALSE),
(2, 46, TRUE, FALSE, FALSE);

-- =====================================================
-- 19. VISITOR LOGS (Sample)
-- =====================================================

INSERT INTO visitor_logs (user_id, role_id, device_info, ip_address, page_visited, session_duration) VALUES
(2, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '192.168.1.100', '/freelancer/timesheet', 1800),
(10, 3, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', '192.168.1.101', '/company/home/pending-timesheet', 2400),
(1, 1, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '192.168.1.102', '/admin/pending-timesheets', 3600);

-- =====================================================
-- 20. ACTIVITY LOGS (Sample)
-- =====================================================

INSERT INTO activity_logs (user_id, action, entity_type, entity_id, ip_address, user_agent) VALUES
(2, 'Submitted timesheet', 'timesheet', 1, '192.168.1.100', 'Mozilla/5.0'),
(11, 'Rejected timesheet', 'timesheet', 2, '192.168.1.101', 'Mozilla/5.0'),
(12, 'Approved timesheet', 'timesheet', 4, '192.168.1.102', 'Mozilla/5.0'),
(1, 'Processed payment', 'payment', 2, '192.168.1.103', 'Mozilla/5.0');

-- =====================================================
-- END OF DATA SEEDER
-- =====================================================
