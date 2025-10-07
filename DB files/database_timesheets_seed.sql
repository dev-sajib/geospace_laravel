-- =====================================================
-- COMPREHENSIVE TIMESHEET WORKFLOW DATA SEEDER
-- =====================================================

-- Note: This assumes you have existing users, companies, projects, and contracts
-- Adjust the IDs based on your actual data

-- =====================================================
-- 1. SAMPLE TIMESHEETS
-- =====================================================

-- Timesheet 1: Pending (Just submitted)
INSERT INTO timesheets (
    contract_id, freelancer_id, company_id, project_id,
    start_date, end_date, status_id, status_display_name,
    total_hours, hourly_rate, total_amount,
    submitted_at, resubmission_count
) VALUES (
    1, 2, 1, 1,
    '2025-09-29', '2025-10-05', 1, 'Pending',
    40.00, 75.00, 3000.00,
    '2025-10-06 09:30:00', 0
);

-- Timesheet 2: Rejected (Company found issues)
INSERT INTO timesheets (
    contract_id, freelancer_id, company_id, project_id,
    start_date, end_date, status_id, status_display_name,
    total_hours, hourly_rate, total_amount,
    submitted_at, reviewed_at, reviewed_by, resubmission_count
) VALUES (
    2, 3, 2, 2,
    '2025-09-22', '2025-09-28', 3, 'Rejected',
    35.00, 80.00, 2800.00,
    '2025-09-29 10:00:00', '2025-09-30 14:30:00', 4, 0
);

-- Timesheet 3: Resubmitted (After rejection)
INSERT INTO timesheets (
    contract_id, freelancer_id, company_id, project_id,
    start_date, end_date, status_id, status_display_name,
    total_hours, hourly_rate, total_amount,
    submitted_at, reviewed_at, reviewed_by, resubmission_count, last_resubmitted_at
) VALUES (
    3, 4, 3, 3,
    '2025-09-15', '2025-09-21', 4, 'Resubmitted',
    42.00, 70.00, 2940.00,
    '2025-09-22 08:00:00', '2025-09-23 11:00:00', 5, 1, '2025-09-24 16:00:00'
);

-- Timesheet 4: Approved (Waiting for payment request)
INSERT INTO timesheets (
    contract_id, freelancer_id, company_id, project_id,
    start_date, end_date, status_id, status_display_name,
    total_hours, hourly_rate, total_amount,
    submitted_at, reviewed_at, reviewed_by, resubmission_count
) VALUES (
    4, 5, 4, 4,
    '2025-09-08', '2025-09-14', 2, 'Approved',
    38.00, 85.00, 3230.00,
    '2025-09-15 09:00:00', '2025-09-16 10:30:00', 6, 0
);

-- Timesheet 5: Payment Requested
INSERT INTO timesheets (
    contract_id, freelancer_id, company_id, project_id,
    start_date, end_date, status_id, status_display_name,
    total_hours, hourly_rate, total_amount,
    submitted_at, reviewed_at, reviewed_by, payment_requested_at, resubmission_count
) VALUES (
    5, 6, 5, 5,
    '2025-09-01', '2025-09-07', 5, 'Payment_Requested',
    40.00, 90.00, 3600.00,
    '2025-09-08 08:30:00', '2025-09-09 09:00:00', 7, '2025-09-10 10:00:00', 0
);

-- Timesheet 6: Payment Processing
INSERT INTO timesheets (
    contract_id, freelancer_id, company_id, project_id,
    start_date, end_date, status_id, status_display_name,
    total_hours, hourly_rate, total_amount,
    submitted_at, reviewed_at, reviewed_by, payment_requested_at, resubmission_count
) VALUES (
    6, 7, 6, 6,
    '2025-08-25', '2025-08-31', 6, 'Payment_Processing',
    37.50, 95.00, 3562.50,
    '2025-09-01 09:00:00', '2025-09-02 10:00:00', 8, '2025-09-03 11:00:00', 0
);

-- Timesheet 7: Payment Completed
INSERT INTO timesheets (
    contract_id, freelancer_id, company_id, project_id,
    start_date, end_date, status_id, status_display_name,
    total_hours, hourly_rate, total_amount,
    submitted_at, reviewed_at, reviewed_by, payment_requested_at, payment_completed_at, resubmission_count
) VALUES (
    7, 8, 7, 7,
    '2025-08-18', '2025-08-24', 7, 'Payment_Completed',
    40.00, 100.00, 4000.00,
    '2025-08-25 08:00:00', '2025-08-26 09:00:00', 9, '2025-08-27 10:00:00', '2025-08-30 15:00:00', 0
);

-- =====================================================
-- 2. TIMESHEET DAYS (7 days for each timesheet)
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

-- =====================================================
-- 3. TIMESHEET DAY COMMENTS
-- =====================================================

-- Comments for Timesheet 2 (Rejected - Company found issues)
INSERT INTO timesheet_day_comments (day_id, timesheet_id, comment_by, comment_type, comment_text) VALUES
(9, 2, 4, 'Company', '10 hours seems excessive for this task. Please clarify or adjust.'),
(10, 2, 4, 'Company', '9 hours for bug fixing seems high. Can you provide more details?'),
(11, 2, 4, 'Company', 'Only 3 hours for a full work day? Please explain.');

-- Comments for Timesheet 3 (Resubmitted - Freelancer responses)
INSERT INTO timesheet_day_comments (day_id, timesheet_id, comment_by, comment_type, comment_text) VALUES
(16, 3, 5, 'Company', 'Original comment: 8 hours seems high for this task'),
(16, 3, 4, 'Freelancer', 'Adjusted from 8 to 7 hours after reviewing the actual time spent'),
(18, 3, 5, 'Company', 'Original comment: Please provide more task breakdown'),
(18, 3, 4, 'Freelancer', 'Added detailed breakdown: 3hrs optimization, 2hrs testing, 2hrs documentation');

-- =====================================================
-- 4. INVOICES (Generated after approval)
-- =====================================================

-- Invoice for Timesheet 4 (Approved)
INSERT INTO invoices (
    timesheet_id, contract_id, company_id, freelancer_id,
    invoice_number, invoice_date,
    total_hours, hourly_rate, subtotal, tax_amount, total_amount, currency,
    status, due_date
) VALUES (
    4, 4, 4, 5,
    'INV-2025-001', '2025-09-16',
    38.00, 85.00, 3230.00, 484.50, 3714.50, 'CAD',
    'Generated', '2025-09-30'
);

-- Invoice for Timesheet 5 (Payment Requested)
INSERT INTO invoices (
    timesheet_id, contract_id, company_id, freelancer_id,
    invoice_number, invoice_date,
    total_hours, hourly_rate, subtotal, tax_amount, total_amount, currency,
    status, sent_at, due_date
) VALUES (
    5, 5, 5, 6,
    'INV-2025-002', '2025-09-09',
    40.00, 90.00, 3600.00, 540.00, 4140.00, 'CAD',
    'Sent', '2025-09-10 10:00:00', '2025-09-24'
);

-- Invoice for Timesheet 6 (Payment Processing)
INSERT INTO invoices (
    timesheet_id, contract_id, company_id, freelancer_id,
    invoice_number, invoice_date,
    total_hours, hourly_rate, subtotal, tax_amount, total_amount, currency,
    status, sent_at, due_date
) VALUES (
    6, 6, 6, 7,
    'INV-2025-003', '2025-09-02',
    37.50, 95.00, 3562.50, 534.38, 4096.88, 'CAD',
    'Sent', '2025-09-03 11:00:00', '2025-09-17'
);

-- Invoice for Timesheet 7 (Payment Completed)
INSERT INTO invoices (
    timesheet_id, contract_id, company_id, freelancer_id,
    invoice_number, invoice_date,
    total_hours, hourly_rate, subtotal, tax_amount, total_amount, currency,
    status, sent_at, paid_at, due_date
) VALUES (
    7, 7, 7, 8,
    'INV-2025-004', '2025-08-26',
    40.00, 100.00, 4000.00, 600.00, 4600.00, 'CAD',
    'Paid', '2025-08-27 10:00:00', '2025-08-30 15:00:00', '2025-09-10'
);

-- =====================================================
-- 5. PAYMENT REQUESTS (From freelancers)
-- =====================================================

-- Payment Request 1 (Pending - just requested)
INSERT INTO payment_requests (
    timesheet_id, invoice_id, freelancer_id, company_id,
    requested_amount, currency, status, requested_at
) VALUES (
    5, 2, 6, 5,
    4140.00, 'CAD', 'Pending', '2025-09-10 10:00:00'
);

-- Payment Request 2 (Approved by admin, processing)
INSERT INTO payment_requests (
    timesheet_id, invoice_id, freelancer_id, company_id,
    requested_amount, currency, status, processed_by, processed_at, admin_notes, requested_at
) VALUES (
    6, 3, 7, 6,
    4096.88, 'CAD', 'Approved', 1, '2025-09-05 14:30:00', 'Transaction verified with bank. Processing payment.', '2025-09-03 11:00:00'
);

-- Payment Request 3 (Completed)
INSERT INTO payment_requests (
    timesheet_id, invoice_id, freelancer_id, company_id,
    requested_amount, currency, status, processed_by, processed_at, admin_notes, requested_at
) VALUES (
    7, 4, 8, 7,
    4600.00, 'CAD', 'Completed', 1, '2025-08-30 15:00:00', 'Payment completed successfully. Transaction ID: TXN-20250830-001', '2025-08-27 10:00:00'
);

-- =====================================================
-- 6. PAYMENTS (Actual payment records)
-- =====================================================

-- Payment 1: Company to Platform (For Timesheet 6)
INSERT INTO payments (
    payment_request_id, invoice_id, timesheet_id,
    payment_type, amount, currency,
    transaction_id, payment_method, payment_gateway,
    status, initiated_by, verified_by, verified_at,
    due_date, paid_at, payment_notes
) VALUES (
    2, 3, 6,
    'Company_To_Platform', 4096.88, 'CAD',
    'TXN-COMP-20250904-001', 'Bank Transfer', 'Stripe',
    'Completed', 11, 1, '2025-09-05 14:30:00',
    '2025-09-17', '2025-09-04 16:00:00', 'Company payment received and verified'
);

-- Payment 2: Platform to Freelancer (For Timesheet 6 - Processing)
INSERT INTO payments (
    payment_request_id, invoice_id, timesheet_id,
    payment_type, amount, currency,
    transaction_id, payment_method, payment_gateway,
    status, initiated_by, verified_by,
    due_date, admin_notes
) VALUES (
    2, 3, 6,
    'Platform_To_Freelancer', 4096.88, 'CAD',
    'TXN-FREE-20250906-001', 'Bank Transfer', 'PayPal',
    'Processing', 1, 1,
    '2025-09-17', 'Payment initiated to freelancer. Awaiting confirmation.'
);

-- Payment 3: Company to Platform (For Timesheet 7)
INSERT INTO payments (
    payment_request_id, invoice_id, timesheet_id,
    payment_type, amount, currency,
    transaction_id, payment_method, payment_gateway,
    status, initiated_by, verified_by, verified_at,
    due_date, paid_at, payment_notes
) VALUES (
    3, 4, 7,
    'Company_To_Platform', 4600.00, 'CAD',
    'TXN-COMP-20250828-001', 'Bank Transfer', 'Stripe',
    'Completed', 12, 1, '2025-08-29 10:00:00',
    '2025-09-10', '2025-08-28 14:00:00', 'Company payment received and verified'
);

-- Payment 4: Platform to Freelancer (For Timesheet 7 - Completed)
INSERT INTO payments (
    payment_request_id, invoice_id, timesheet_id,
    payment_type, amount, currency,
    transaction_id, payment_method, payment_gateway,
    status, initiated_by, verified_by, verified_at,
    due_date, paid_at, payment_notes, admin_notes
) VALUES (
    3, 4, 7,
    'Platform_To_Freelancer', 4600.00, 'CAD',
    'TXN-FREE-20250830-001', 'Bank Transfer', 'PayPal',
    'Completed', 1, 1, '2025-08-30 15:00:00',
    '2025-09-10', '2025-08-30 15:00:00', 'Payment successfully transferred to freelancer', 'Freelancer confirmed receipt'
);

-- =====================================================
-- 7. FREELANCER EARNINGS (Track total income)
-- =====================================================

-- Freelancer 2 (Has 1 pending timesheet)
INSERT INTO freelancer_earnings (freelancer_id, total_earned, pending_amount, completed_amount, total_projects, total_timesheets) 
VALUES (2, 0.00, 3000.00, 0.00, 1, 1);

-- Freelancer 3 (Has 1 rejected timesheet)
INSERT INTO freelancer_earnings (freelancer_id, total_earned, pending_amount, completed_amount, total_projects, total_timesheets) 
VALUES (3, 0.00, 2800.00, 0.00, 1, 1);

-- Freelancer 4 (Has 1 resubmitted timesheet)
INSERT INTO freelancer_earnings (freelancer_id, total_earned, pending_amount, completed_amount, total_projects, total_timesheets) 
VALUES (4, 0.00, 2940.00, 0.00, 1, 1);

-- Freelancer 5 (Has 1 approved timesheet)
INSERT INTO freelancer_earnings (freelancer_id, total_earned, pending_amount, completed_amount, total_projects, total_timesheets) 
VALUES (5, 0.00, 3230.00, 0.00, 1, 1);

-- Freelancer 6 (Has 1 payment requested)
INSERT INTO freelancer_earnings (freelancer_id, total_earned, pending_amount, completed_amount, total_projects, total_timesheets) 
VALUES (6, 0.00, 3600.00, 0.00, 1, 1);

-- Freelancer 7 (Has 1 payment processing)
INSERT INTO freelancer_earnings (freelancer_id, total_earned, pending_amount, completed_amount, total_projects, total_timesheets) 
VALUES (7, 0.00, 3562.50, 0.00, 1, 1);

-- Freelancer 8 (Has 1 completed payment)
INSERT INTO freelancer_earnings (
    freelancer_id, total_earned, pending_amount, completed_amount, 
    total_projects, total_timesheets, last_payment_date
) VALUES (
    8, 4000.00, 0.00, 4000.00, 1, 1, '2025-08-30 15:00:00'
);

-- =====================================================
-- 8. ADDITIONAL SAMPLE DATA FOR MORE REALISM
-- =====================================================

-- More timesheets for different scenarios

-- Timesheet 8: Another Pending
INSERT INTO timesheets (
    contract_id, freelancer_id, company_id, project_id,
    start_date, end_date, status_id, status_display_name,
    total_hours, hourly_rate, total_amount,
    submitted_at, resubmission_count
) VALUES (
    8, 9, 8, 8,
    '2025-10-01', '2025-10-07', 1, 'Pending',
    44.00, 65.00, 2860.00,
    '2025-10-07 17:30:00', 0
);

-- Days for Timesheet 8
INSERT INTO timesheet_days (timesheet_id, work_date, day_name, day_number, hours_worked, task_description) VALUES
(50, '2025-10-01', 'Tuesday', 1, 8.00, 'Requirements gathering and analysis'),
(51, '2025-10-02', 'Wednesday', 2, 8.00, 'System architecture design'),
(52, '2025-10-03', 'Thursday', 3, 8.00, 'Database design and setup'),
(53, '2025-10-04', 'Friday', 4, 8.00, 'Core module development'),
(54, '2025-10-05', 'Saturday', 5, 6.00, 'Testing and bug fixes'),
(55, '2025-10-06', 'Sunday', 6, 3.00, 'Code review and documentation'),
(56, '2025-10-07', 'Monday', 7, 3.00, 'Final adjustments and deployment');

-- Timesheet 9: Multiple resubmissions (2 times)
INSERT INTO timesheets (
    contract_id, freelancer_id, company_id, project_id,
    start_date, end_date, status_id, status_display_name,
    total_hours, hourly_rate, total_amount,
    submitted_at, reviewed_at, reviewed_by, resubmission_count, last_resubmitted_at
) VALUES (
    9, 10, 9, 9,
    '2025-09-11', '2025-09-17', 4, 'Resubmitted',
    36.00, 72.00, 2592.00,
    '2025-09-18 09:00:00', '2025-09-20 15:00:00', 13, 2, '2025-09-23 10:30:00'
);

-- Days for Timesheet 9
INSERT INTO timesheet_days (timesheet_id, work_date, day_name, day_number, hours_worked, task_description) VALUES
(57, '2025-09-11', 'Thursday', 1, 7.00, 'Initial setup and configuration'),
(58, '2025-09-12', 'Friday', 2, 6.00, 'Feature implementation'),
(59, '2025-09-13', 'Saturday', 3, 0.00, 'Weekend off'),
(60, '2025-09-14', 'Sunday', 4, 0.00, 'Weekend off'),
(61, '2025-09-15', 'Monday', 5, 8.00, 'Bug fixing and improvements'),
(62, '2025-09-16', 'Tuesday', 6, 8.00, 'Testing and validation'),
(63, '2025-09-17', 'Wednesday', 7, 7.00, 'Documentation and code cleanup');

-- Comments showing multiple rounds of review
INSERT INTO timesheet_day_comments (day_id, timesheet_id, comment_by, comment_type, comment_text) VALUES
(57, 9, 13, 'Company', 'First review: 8 hours seems high for setup'),
(57, 9, 10, 'Freelancer', 'First response: Reduced to 7 hours after review'),
(58, 9, 13, 'Company', 'Second review: Still need more detail on the 6 hours'),
(58, 9, 10, 'Freelancer', 'Second response: Added breakdown: 2hrs research, 2hrs coding, 2hrs testing'),
(61, 9, 13, 'Company', 'First review: What bugs were fixed?'),
(61, 9, 10, 'Freelancer', 'Added detailed list of 5 bugs fixed with descriptions');

-- =====================================================
-- 9. NOTIFICATIONS (Auto-generated for workflow)
-- =====================================================

-- Notification for Timesheet 1 (Company receives new timesheet)
INSERT INTO notifications (user_id, title, message, type, action_url) VALUES
(4, 'New Timesheet Submitted', 'Freelancer John Doe has submitted a timesheet for review. Period: Sep 29 - Oct 05, 2025', 'Info', '/company/timesheets/1');

-- Notification for Timesheet 2 (Freelancer receives rejection)
INSERT INTO notifications (user_id, title, message, type, action_url, created_at) VALUES
(3, 'Timesheet Rejected', 'Your timesheet for period Sep 22-28 has been rejected. Please review comments and resubmit.', 'Warning', '/freelancer/timesheets/2', '2025-09-30 14:30:00');

-- Notification for Timesheet 3 (Company receives resubmission)
INSERT INTO notifications (user_id, title, message, type, action_url, created_at) VALUES
(5, 'Timesheet Resubmitted', 'Freelancer has resubmitted timesheet for period Sep 15-21. Please review the changes.', 'Info', '/company/timesheets/3', '2025-09-24 16:00:00');

-- Notification for Timesheet 4 (Freelancer receives approval)
INSERT INTO notifications (user_id, title, message, type, action_url, created_at) VALUES
(5, 'Timesheet Approved', 'Your timesheet for period Sep 08-14 has been approved! Invoice INV-2025-001 generated.', 'Success', '/freelancer/timesheets/4', '2025-09-16 10:30:00');

-- Notification for Timesheet 5 (Admin receives payment request)
INSERT INTO notifications (user_id, title, message, type, action_url, created_at) VALUES
(1, 'Payment Request Received', 'Freelancer has requested payment for timesheet. Amount: $4,140.00 CAD', 'Info', '/admin/payment-requests/1', '2025-09-10 10:00:00');

-- Notification for Timesheet 6 (Freelancer payment processing)
INSERT INTO notifications (user_id, title, message, type, action_url, created_at) VALUES
(7, 'Payment Processing', 'Your payment of $4,096.88 CAD is being processed. Transaction ID: TXN-FREE-20250906-001', 'Info', '/freelancer/payments/2', '2025-09-06 09:00:00');

-- Notification for Timesheet 7 (Freelancer payment completed)
INSERT INTO notifications (user_id, title, message, type, action_url, created_at) VALUES
(8, 'Payment Completed', 'Payment of $4,600.00 CAD has been successfully transferred to your account!', 'Success', '/freelancer/payments/4', '2025-08-30 15:00:00');

-- =====================================================
-- 10. ACTIVITY LOGS (Track all important actions)
-- =====================================================

-- Timesheet submission logs
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, new_values, ip_address, created_at) VALUES
(2, 'Timesheet Submitted', 'timesheet', 1, '{"status":"Pending","total_hours":40,"total_amount":3000}', '192.168.1.100', '2025-10-06 09:30:00'),
(3, 'Timesheet Submitted', 'timesheet', 2, '{"status":"Pending","total_hours":35,"total_amount":2800}', '192.168.1.101', '2025-09-29 10:00:00');

-- Timesheet review logs
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, old_values, new_values, ip_address, created_at) VALUES
(4, 'Timesheet Rejected', 'timesheet', 2, '{"status":"Pending"}', '{"status":"Rejected","reviewer_comments":"Multiple days need clarification"}', '192.168.1.50', '2025-09-30 14:30:00'),
(5, 'Timesheet Approved', 'timesheet', 3, '{"status":"Resubmitted"}', '{"status":"Approved"}', '192.168.1.51', '2025-09-23 11:00:00');

-- Invoice generation logs
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, new_values, ip_address, created_at) VALUES
(1, 'Invoice Generated', 'invoice', 1, '{"invoice_number":"INV-2025-001","amount":3714.50}', '192.168.1.1', '2025-09-16 10:30:00'),
(1, 'Invoice Generated', 'invoice', 2, '{"invoice_number":"INV-2025-002","amount":4140.00}', '192.168.1.1', '2025-09-09 09:00:00');

-- Payment request logs
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, new_values, ip_address, created_at) VALUES
(6, 'Payment Requested', 'payment_request', 1, '{"amount":4140.00,"timesheet_id":5}', '192.168.1.102', '2025-09-10 10:00:00'),
(7, 'Payment Requested', 'payment_request', 2, '{"amount":4096.88,"timesheet_id":6}', '192.168.1.103', '2025-09-03 11:00:00');

-- Payment processing logs
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, old_values, new_values, ip_address, created_at) VALUES
(1, 'Payment Request Approved', 'payment_request', 2, '{"status":"Pending"}', '{"status":"Approved","admin_notes":"Transaction verified"}', '192.168.1.1', '2025-09-05 14:30:00'),
(1, 'Payment Completed', 'payment', 4, '{"status":"Processing"}', '{"status":"Completed","transaction_id":"TXN-FREE-20250830-001"}', '192.168.1.1', '2025-08-30 15:00:00');

-- =====================================================
-- 11. VERIFICATION QUERIES
-- =====================================================

-- Check timesheet summary
-- SELECT 
--     t.timesheet_id,
--     t.status_display_name,
--     CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name,
--     cd.company_name,
--     t.total_hours,
--     t.total_amount,
--     t.submitted_at
-- FROM timesheets t
-- JOIN users u ON t.freelancer_id = u.user_id
-- JOIN user_details ud ON u.user_id = ud.user_id
-- JOIN company_details cd ON t.company_id = cd.company_id
-- ORDER BY t.submitted_at DESC;

-- Check payment flow
-- SELECT 
--     t.timesheet_id,
--     t.status_display_name as timesheet_status,
--     i.invoice_number,
--     i.total_amount as invoice_amount,
--     pr.status as payment_request_status,
--     p.payment_type,
--     p.status as payment_status,
--     p.transaction_id
-- FROM timesheets t
-- LEFT JOIN invoices i ON t.timesheet_id = i.timesheet_id
-- LEFT JOIN payment_requests pr ON t.timesheet_id = pr.timesheet_id
-- LEFT JOIN payments p ON i.invoice_id = p.invoice_id
-- WHERE t.status_id >= 2
-- ORDER BY t.timesheet_id;

-- Check freelancer earnings
-- SELECT 
--     u.user_id,
--     CONCAT(ud.first_name, ' ', ud.last_name) as freelancer_name,
--     fe.total_earned,
--     fe.pending_amount,
--     fe.completed_amount,
--     fe.total_timesheets
-- FROM freelancer_earnings fe
-- JOIN users u ON fe.freelancer_id = u.user_id
-- JOIN user_details ud ON u.user_id = ud.user_id;

-- =====================================================
-- END OF SEEDER
-- =====================================================