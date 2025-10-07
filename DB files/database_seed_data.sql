-- =====================================================
-- GeoSpace Database Seed Data
-- Initial data for the GeoSpace platform
-- =====================================================

-- =====================================================
-- 1. ROLES DATA
-- =====================================================

INSERT INTO roles (role_id, role_name, role_description) VALUES
(1, 'Admin', 'System administrator with full access'),
(2, 'Freelancer', 'Independent contractor providing services'),
(3, 'Company', 'Company hiring freelancers'),
(4, 'Support', 'Customer support agent'),
(5, 'Visitor', 'Non-authenticated website visitor');

-- =====================================================
-- 2. TIMESHEET STATUS DATA
-- =====================================================

INSERT INTO timesheet_status (status_id, status_name, status_description) VALUES
(1, 'Pending', 'Timesheet submitted and awaiting approval'),
(2, 'Approved', 'Timesheet approved by company/client'),
(3, 'Rejected', 'Timesheet rejected with reason'),
(4, 'Under Review', 'Timesheet under review by admin'),
(5, 'Disputed', 'Timesheet disputed by either party');

-- =====================================================
-- 3. DROPDOWN CATEGORIES AND VALUES
-- =====================================================

-- Support Agents Category
INSERT INTO dropdown_categories (category_name, description) VALUES
('SupportAgents', 'List of available support agents');

-- Dispute Status Category
INSERT INTO dropdown_categories (category_name, description) VALUES
('DisputeStatus', 'Available dispute resolution statuses');

-- Professional Roles Category
INSERT INTO dropdown_categories (category_name, description) VALUES
('ProfessionalRoles', 'Available professional roles for freelancers');

-- Project Types Category
INSERT INTO dropdown_categories (category_name, description) VALUES
('ProjectTypes', 'Available project types');

-- Skills Category
INSERT INTO dropdown_categories (category_name, description) VALUES
('Skills', 'Available technical skills');

-- Insert dropdown values
INSERT INTO dropdown_values (category_id, display_name, value, sort_order) VALUES
-- Professional Roles
(3, 'Geologist', 'Geologist', 1),
(3, 'Miner', 'Miner', 2),
(3, 'Engineer', 'Engineer', 3),
(3, 'Electrician', 'Electrician', 4),
(3, 'Environmental Specialist', 'Environmental Specialist', 5),
(3, 'Data Specialist', 'Data Specialist', 6),
(3, 'Professional Driller', 'Professional Driller', 7),
(3, 'Petroleum Expert', 'Petroleum Expert', 8),

-- Project Types
(4, 'Geological Mapping', 'Geological Mapping', 1),
(4, 'Structural Geology', 'Structural Geology', 2),
(4, 'Mining Engineering', 'Mining Engineering', 3),
(4, 'Environmental Assessment', 'Environmental Assessment', 4),
(4, 'Data Analysis', 'Data Analysis', 5),
(4, 'Drilling Operations', 'Drilling Operations', 6),

-- Skills
(5, 'Geological mapping', 'Geological mapping', 1),
(5, 'Structural geology interpretation', 'Structural geology interpretation', 2),
(5, 'Lithological logging & core description', 'Lithological logging & core description', 3),
(5, 'Remote sensing & satellite imagery', 'Remote sensing & satellite imagery', 4),
(5, 'Geochemical sampling & analysis', 'Geochemical sampling & analysis', 5),
(5, 'Geophysical survey interpretation', 'Geophysical survey interpretation', 6),
(5, 'Mineral resource estimation', 'Mineral resource estimation', 7),
(5, 'Reserve calculation & reporting', 'Reserve calculation & reporting', 8),
(5, 'Ore body modelling', 'Ore body modelling', 9),
(5, 'Grade control monitoring', 'Grade control monitoring', 10),
(5, 'Mine feasibility studies', 'Mine feasibility studies', 11),
(5, 'Drill site planning & supervision', 'Drill site planning & supervision', 12),
(5, 'Diamond RC drilling oversight', 'Diamond RC drilling oversight', 13),
(5, 'Drill core handling & sampling', 'Drill core handling & sampling', 14),
(5, 'Downhole survey coordination', 'Downhole survey coordination', 15),
(5, 'Water well drilling & monitoring', 'Water well drilling & monitoring', 16),
(5, 'Hydrogeological assessments', 'Hydrogeological assessments', 17),
(5, 'Environmental impact studies', 'Environmental impact studies', 18),
(5, 'Geotechnical site investigations', 'Geotechnical site investigations', 19),
(5, 'Soil stability & slope analysis', 'Soil stability & slope analysis', 20);

-- =====================================================
-- 4. MENU ITEMS
-- =====================================================

-- Admin Menu Items
INSERT INTO menu_items (menu_id, parent_menu_id, menu_name, menu_url, menu_icon, sort_order) VALUES
(1, NULL, 'Dashboard', '/admin/active-users', 'dashboard', 1),
(2, NULL, 'Users', NULL, 'users', 2),
(3, 2, 'Active Users', '/admin/active-users', 'user-check', 1),
(4, 2, 'Verified Users', '/admin/users/verified', 'user-check-circle', 2),
(5, 2, 'Pending Verification', '/admin/users/pending', 'user-clock', 3),
(6, 2, 'Suspended Accounts', '/admin/users/suspended', 'user-x', 4),
(7, NULL, 'Projects', NULL, 'briefcase', 3),
(8, 7, 'Active Contracts', '/admin/contracts', 'file-text', 1),
(9, 7, 'Project Milestones', '/admin/projects/milestones', 'target', 2),
(10, 7, 'Platform Satisfaction', '/admin/projects/satisfaction', 'star', 3),
(11, NULL, 'Timesheets', NULL, 'clock', 4),
(12, 11, 'Pending Timesheets', '/admin/pending-timesheets', 'clock', 1),
(13, 11, 'Timesheet Pipeline', '/admin/timesheet/pipeline', 'workflow', 2),
(14, 11, 'Timesheet Logs', '/admin/timesheet/logs', 'list', 3),
(15, 11, 'Manual Override', '/admin/timesheet/override', 'settings', 4),
(16, NULL, 'Support', NULL, 'headphones', 5),
(17, 16, 'Dispute Tickets', '/admin/dispute-tickets', 'alert-triangle', 1),
(18, 16, 'Support Agents', '/admin/support/agents', 'users', 2),
(19, 16, 'Live Chat', '/admin/support/chat', 'message-circle', 3),
(20, 16, 'Video Chat', '/admin/support/video-chat', 'video', 4),
(21, NULL, 'Financial', NULL, 'dollar-sign', 6),
(22, 21, 'Payment from Company', '/admin/financial-management/payment-from-company', 'credit-card', 1),
(23, 21, 'Payment to Freelancer', '/admin/financial-management/payment-to-freelancer', 'banknote', 2),
(24, NULL, 'Platform', NULL, 'bar-chart', 7),
(25, 24, 'Platform Metrics', '/admin/platform-metrics', 'trending-up', 1),
(26, NULL, 'Content', NULL, 'edit', 8),
(27, 26, 'Blog Management', '/admin/blogs', 'book-open', 1);

-- Company Menu Items
INSERT INTO menu_items (menu_id, parent_menu_id, menu_name, menu_url, menu_icon, sort_order) VALUES
(28, NULL, 'Dashboard', '/company/home/current-projects', 'dashboard', 1),
(29, NULL, 'Projects', NULL, 'briefcase', 2),
(30, 29, 'Current Projects', '/company/home/current-projects', 'folder', 1),
(31, 29, 'Active Freelancers', '/company/home/active-freelancers', 'users', 2),
(32, 29, 'Pending Timesheets', '/company/home/pending-timesheet', 'clock', 3),
(33, NULL, 'Freelancers', NULL, 'users', 3),
(34, 33, 'Profiles & Ratings', '/company/freelancers/profiles', 'star', 1),
(35, 33, 'Monitor Performance', '/company/freelancers/performance', 'activity', 2),
(36, 33, 'Feedback', '/company/freelancers/feedback', 'message-square', 3),
(37, NULL, 'Jobs', NULL, 'briefcase', 4),
(38, 37, 'Post New Opportunity', '/company/jobs/new', 'plus', 1),
(39, 37, 'Pre-Certified Freelancers', '/company/jobs/certified', 'award', 2),
(40, 37, 'Track Applications', '/company/jobs/track', 'eye', 3),
(41, NULL, 'Profile', NULL, 'user', 5),
(42, 41, 'Update Profile', '/company/profile/update', 'edit', 1),
(43, 41, 'List of Services', '/company/profile/services', 'list', 2),
(44, 41, 'Portfolio Show', '/company/profile/portfolio', 'image', 3),
(45, NULL, 'Financial', NULL, 'dollar-sign', 6),
(46, 45, 'Timesheet', '/company/timesheet', 'clock', 1),
(47, 45, 'Upcoming Payments', '/company/payments/upcoming', 'calendar', 2),
(48, 45, 'Invoices', '/company/payments/invoices', 'file-text', 3),
(49, NULL, 'Support', NULL, 'headphones', 7),
(50, 49, 'Compliance Documents', '/company/support/compliance', 'file-check', 1),
(51, 49, 'Dispute Resolution', '/company/support/disputes', 'alert-triangle', 2),
(52, 49, 'Support Panel', '/company/support/panel', 'message-circle', 3),
(53, NULL, 'Notifications', '/company/home/notifications', 'bell', 8);

-- Freelancer Menu Items
INSERT INTO menu_items (menu_id, parent_menu_id, menu_name, menu_url, menu_icon, sort_order) VALUES
(54, NULL, 'Dashboard', '/freelancer/home/current-contracts', 'dashboard', 1),
(55, NULL, 'Work', NULL, 'briefcase', 2),
(56, 55, 'Current Contracts', '/freelancer/home/current-contracts', 'file-text', 1),
(57, 55, 'Job Recommendations', '/freelancer/home/job-recommendations', 'target', 2),
(58, 55, 'Earning Overview', '/freelancer/home/earning-overview', 'trending-up', 3),
(59, NULL, 'My Work', NULL, 'work', 3),
(60, 59, 'My Contract', '/freelancer/contracts', 'file-text', 1),
(61, 59, 'Products', '/freelancer/products', 'package', 2),
(62, 59, 'Timesheet', '/freelancer/timesheet', 'clock', 3),
(63, 59, 'Applications', '/freelancer/applications', 'send', 4),
(64, NULL, 'Profile', NULL, 'user', 4),
(65, 64, 'Manage Profile', '/freelancer/profile', 'edit', 1),
(66, 64, 'Recommendations', '/freelancer/recommendations', 'thumbs-up', 2),
(67, 64, 'Reviews', '/freelancer/reviews', 'star', 3),
(68, NULL, 'Earnings', NULL, 'dollar-sign', 5),
(69, 68, 'Earnings Overview', '/freelancer/earnings/overview', 'trending-up', 1),
(70, 68, 'Earning Statement', '/freelancer/earnings/statement', 'file-text', 2),
(71, 68, 'Invoice & Pending Payments', '/freelancer/earnings/invoices', 'credit-card', 3),
(72, 68, 'Bank Information', '/freelancer/earnings/bank-info', 'banknote', 4),
(73, NULL, 'Support', '/freelancer/support', 'headphones', 6);

-- Support Menu Items
INSERT INTO menu_items (menu_id, parent_menu_id, menu_name, menu_url, menu_icon, sort_order) VALUES
(74, NULL, 'Dashboard', '/support/disputes', 'dashboard', 1),
(75, NULL, 'Disputes', '/support/disputes', 'alert-triangle', 2),
(76, NULL, 'Live Chat', '/support/chat', 'message-circle', 3),
(77, NULL, 'Video Chat', '/support/video-chat', 'video', 4);

-- =====================================================
-- 5. ROLE PERMISSIONS
-- =====================================================

-- Admin permissions (role_id = 1) - Full access to all menus
INSERT INTO role_permissions (role_id, menu_id, can_view, can_create, can_edit, can_delete) VALUES
(1, 1, 1, 1, 1, 1), (1, 2, 1, 1, 1, 1), (1, 3, 1, 1, 1, 1), (1, 4, 1, 1, 1, 1),
(1, 5, 1, 1, 1, 1), (1, 6, 1, 1, 1, 1), (1, 7, 1, 1, 1, 1), (1, 8, 1, 1, 1, 1),
(1, 9, 1, 1, 1, 1), (1, 10, 1, 1, 1, 1), (1, 11, 1, 1, 1, 1), (1, 12, 1, 1, 1, 1),
(1, 13, 1, 1, 1, 1), (1, 14, 1, 1, 1, 1), (1, 15, 1, 1, 1, 1), (1, 16, 1, 1, 1, 1),
(1, 17, 1, 1, 1, 1), (1, 18, 1, 1, 1, 1), (1, 19, 1, 1, 1, 1), (1, 20, 1, 1, 1, 1),
(1, 21, 1, 1, 1, 1), (1, 22, 1, 1, 1, 1), (1, 23, 1, 1, 1, 1), (1, 24, 1, 1, 1, 1),
(1, 25, 1, 1, 1, 1), (1, 26, 1, 1, 1, 1), (1, 27, 1, 1, 1, 1);

-- Company permissions (role_id = 3)
INSERT INTO role_permissions (role_id, menu_id, can_view, can_create, can_edit, can_delete) VALUES
(3, 28, 1, 0, 0, 0), (3, 29, 1, 1, 1, 0), (3, 30, 1, 1, 1, 0), (3, 31, 1, 0, 0, 0),
(3, 32, 1, 0, 1, 0), (3, 33, 1, 0, 0, 0), (3, 34, 1, 0, 0, 0), (3, 35, 1, 0, 0, 0),
(3, 36, 1, 1, 1, 0), (3, 37, 1, 1, 1, 0), (3, 38, 1, 1, 1, 0), (3, 39, 1, 0, 0, 0),
(3, 40, 1, 0, 0, 0), (3, 41, 1, 0, 1, 0), (3, 42, 1, 0, 1, 0), (3, 43, 1, 1, 1, 1),
(3, 44, 1, 0, 1, 0), (3, 45, 1, 0, 0, 0), (3, 46, 1, 0, 1, 0), (3, 47, 1, 0, 0, 0),
(3, 48, 1, 0, 0, 0), (3, 49, 1, 0, 0, 0), (3, 50, 1, 0, 0, 0), (3, 51, 1, 1, 1, 0),
(3, 52, 1, 0, 0, 0), (3, 53, 1, 0, 1, 0);

-- Freelancer permissions (role_id = 2)
INSERT INTO role_permissions (role_id, menu_id, can_view, can_create, can_edit, can_delete) VALUES
(2, 54, 1, 0, 0, 0), (2, 55, 1, 0, 0, 0), (2, 56, 1, 0, 0, 0), (2, 57, 1, 0, 0, 0),
(2, 58, 1, 0, 0, 0), (2, 59, 1, 0, 0, 0), (2, 60, 1, 0, 0, 0), (2, 61, 1, 1, 1, 1),
(2, 62, 1, 1, 1, 0), (2, 63, 1, 1, 1, 0), (2, 64, 1, 0, 1, 0), (2, 65, 1, 0, 1, 0),
(2, 66, 1, 0, 0, 0), (2, 67, 1, 0, 0, 0), (2, 68, 1, 0, 0, 0), (2, 69, 1, 0, 0, 0),
(2, 70, 1, 0, 0, 0), (2, 71, 1, 0, 0, 0), (2, 72, 1, 0, 1, 0), (2, 73, 1, 1, 1, 0);

-- Support permissions (role_id = 4)
INSERT INTO role_permissions (role_id, menu_id, can_view, can_create, can_edit, can_delete) VALUES
(4, 74, 1, 0, 0, 0), (4, 75, 1, 1, 1, 0), (4, 76, 1, 1, 1, 0), (4, 77, 1, 1, 1, 0);

-- =====================================================
-- 6. SAMPLE USERS (for testing)
-- =====================================================

-- Create sample admin user
INSERT INTO users (user_id, email, password_hash, role_id, user_position, is_active, is_verified) VALUES
(1, 'admin@geospace.com', '$2b$10$rQZ8K9mN2vP1sT3uW4xY5eF6gH7iJ8kL9mN0oP1qR2sT3uV4wX5yZ6', 1, 'System Administrator', 1, 1);

INSERT INTO user_details (user_id, first_name, last_name, phone, city, country) VALUES
(1, 'Admin', 'User', '+1-555-0101', 'Toronto', 'Canada');

-- Create sample freelancer
INSERT INTO users (user_id, email, password_hash, role_id, user_position, is_active, is_verified) VALUES
(2, 'freelancer@geospace.com', '$2b$10$rQZ8K9mN2vP1sT3uW4xY5eF6gH7iJ8kL9mN0oP1qR2sT3uV4wX5yZ6', 2, 'Geologist', 1, 1);

INSERT INTO user_details (user_id, first_name, last_name, phone, city, country, hourly_rate, bio) VALUES
(2, 'John', 'Smith', '+1-555-0102', 'Vancouver', 'Canada', 85.00, 'Experienced geologist with 10+ years in mining and exploration.');

-- Create sample company
INSERT INTO users (user_id, email, password_hash, role_id, user_position, is_active, is_verified) VALUES
(3, 'company@geospace.com', '$2b$10$rQZ8K9mN2vP1sT3uW4xY5eF6gH7iJ8kL9mN0oP1qR2sT3uV4wX5yZ6', 3, 'Project Manager', 1, 1);

INSERT INTO user_details (user_id, first_name, last_name, phone, city, country) VALUES
(3, 'Jane', 'Doe', '+1-555-0103', 'Montreal', 'Canada');

INSERT INTO company_details (user_id, company_name, company_type, industry, company_size, description) VALUES
(3, 'Mining Solutions Inc.', 'Corporation', 'Mining', '51-200', 'Leading mining company specializing in mineral exploration and extraction.');

-- Create sample support agent
INSERT INTO users (user_id, email, password_hash, role_id, user_position, is_active, is_verified) VALUES
(4, 'support@geospace.com', '$2b$10$rQZ8K9mN2vP1sT3uW4xY5eF6gH7iJ8kL9mN0oP1qR2sT3uV4wX5yZ6', 4, 'Support Agent', 1, 1);

INSERT INTO user_details (user_id, first_name, last_name, phone, city, country) VALUES
(4, 'Support', 'Agent', '+1-555-0104', 'Calgary', 'Canada');

-- =====================================================
-- 7. SAMPLE PROJECT AND CONTRACT
-- =====================================================

-- Sample project
INSERT INTO projects (project_id, company_id, project_title, project_description, project_type, budget_min, budget_max, duration_weeks, status, skills_required) VALUES
(1, 1, 'Geological Survey - Northern Ontario', 'Comprehensive geological survey of mining properties in Northern Ontario including mapping, sampling, and analysis.', 'Geological Mapping', 50000.00, 75000.00, 12, 'Published', '["Geological mapping", "Structural geology interpretation", "Geochemical sampling & analysis"]');

-- Sample contract
INSERT INTO contracts (contract_id, project_id, freelancer_id, company_id, contract_title, contract_description, hourly_rate, total_amount, start_date, end_date, status) VALUES
(1, 1, 2, 1, 'Geological Survey Contract', 'Contract for geological survey work including field mapping and report preparation.', 85.00, 65000.00, '2024-01-15', '2024-04-15', 'Active');

-- =====================================================
-- 8. SAMPLE TIMESHEET
-- =====================================================

INSERT INTO timesheets (timesheet_id, contract_id, user_id, work_date, day_of_week, work_hours, task_description, status_id, status_display_name) VALUES
(1, 1, 2, '2024-01-15', 'Monday', 8.00, 'Field mapping and GPS data collection', 1, 'Pending'),
(2, 1, 2, '2024-01-16', 'Tuesday', 7.50, 'Sample collection and documentation', 2, 'Approved'),
(3, 1, 2, '2024-01-17', 'Wednesday', 8.00, 'Data analysis and report writing', 1, 'Pending');

-- =====================================================
-- 9. SAMPLE NOTIFICATIONS
-- =====================================================

INSERT INTO notifications (user_id, title, message, type, action_url) VALUES
(2, 'Timesheet Approved', 'Your timesheet for January 16, 2024 has been approved.', 'Success', '/freelancer/timesheet'),
(3, 'New Timesheet Submitted', 'A new timesheet has been submitted for your project.', 'Info', '/company/home/pending-timesheet'),
(1, 'System Update', 'Database schema has been updated successfully.', 'Info', '/admin/active-users');

