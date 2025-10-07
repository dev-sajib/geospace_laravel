-- Dummy Data for GeoSpace Database

-- Insert dummy freelancer users
INSERT INTO users (email, password_hash, role_id, user_position, auth_provider, is_active, is_verified, email_verified_at, created_at, updated_at) VALUES
('sarah.johnson@example.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 2, 'Senior Geologist', NULL, 1, 1, '2025-01-15 10:30:00', '2025-01-15 09:00:00', '2025-01-15 09:00:00'),
('mike.thompson@example.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 2, 'Mining Engineer', NULL, 1, 1, '2025-01-20 14:20:00', '2025-01-20 11:30:00', '2025-01-20 11:30:00'),
('emma.davis@example.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 2, 'Environmental Specialist', NULL, 1, 1, '2025-02-01 16:45:00', '2025-02-01 08:15:00', '2025-02-01 08:15:00'),
('alex.rodriguez@example.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 2, 'Geophysicist', NULL, 1, 1, '2025-02-10 12:00:00', '2025-02-10 10:45:00', '2025-02-10 10:45:00'),
('lisa.chen@example.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 2, 'Petroleum Engineer', NULL, 1, 1, '2025-02-15 09:30:00', '2025-02-15 07:20:00', '2025-02-15 07:20:00'),
('david.wilson@example.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 2, 'Hydrogeologist', NULL, 1, 0, NULL, '2025-02-20 13:15:00', '2025-02-20 13:15:00'),
('maria.garcia@example.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 2, 'GIS Specialist', NULL, 1, 1, '2025-02-25 11:40:00', '2025-02-25 09:50:00', '2025-02-25 09:50:00'),
('james.brown@example.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 2, 'Drilling Supervisor', NULL, 1, 1, '2025-03-01 15:20:00', '2025-03-01 14:10:00', '2025-03-01 14:10:00');

-- Insert dummy company users
INSERT INTO users (email, password_hash, role_id, user_position, auth_provider, is_active, is_verified, email_verified_at, created_at, updated_at) VALUES
('contact@goldminingcorp.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 3, 'Project Manager', NULL, 1, 1, '2025-01-10 08:00:00', '2025-01-10 08:00:00', '2025-01-10 08:00:00'),
('hr@energyexplorers.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 3, 'HR Manager', NULL, 1, 1, '2025-01-25 10:30:00', '2025-01-25 10:30:00', '2025-01-25 10:30:00'),
('projects@mineraltech.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 3, 'Operations Director', NULL, 1, 1, '2025-02-05 12:15:00', '2025-02-05 12:15:00', '2025-02-05 12:15:00'),
('team@geosolutions.com', '$2y$12$yEYycmwh6STiB5oxH6/K6OEBWZTirwR2xCcugkeTTfb231EuRxPhi', 3, 'Technical Lead', NULL, 1, 1, '2025-02-12 14:45:00', '2025-02-12 14:45:00', '2025-02-12 14:45:00');

-- Insert freelancer user details (assuming user_ids 5-12 for new freelancers)
INSERT INTO user_details (user_id, first_name, last_name, phone, address, city, state, postal_code, country, profile_image, bio, linkedin_url, website_url, resume_or_cv, hourly_rate, availability_status) VALUES
(5, 'Sarah', 'Johnson', '+1-416-555-0101', '123 Geology Ave', 'Toronto', 'Ontario', 'M5V 3A8', 'Canada', '/images/profiles/sarah_johnson.jpg', 'Experienced geologist with 8+ years in mineral exploration and resource evaluation. Specialized in structural geology and ore deposit modeling.', 'https://linkedin.com/in/sarahjohnson', 'https://sarahgeology.com', '/resumes/sarah_johnson_cv.pdf', 85.00, 'Available'),
(6, 'Mike', 'Thompson', '+1-604-555-0102', '456 Mining Road', 'Vancouver', 'British Columbia', 'V6B 1A1', 'Canada', '/images/profiles/mike_thompson.jpg', 'Mining engineer with expertise in underground operations and safety protocols. 10+ years experience in gold and copper mining.', 'https://linkedin.com/in/mikethompson', NULL, '/resumes/mike_thompson_cv.pdf', 95.00, 'Available'),
(7, 'Emma', 'Davis', '+1-403-555-0103', '789 Environmental St', 'Calgary', 'Alberta', 'T2P 1J9', 'Canada', '/images/profiles/emma_davis.jpg', 'Environmental specialist focused on mining impact assessment and remediation. Certified in environmental management systems.', 'https://linkedin.com/in/emmadavis', 'https://emmadavis.ca', '/resumes/emma_davis_cv.pdf', 75.00, 'Available'),
(8, 'Alex', 'Rodriguez', '+1-514-555-0104', '321 Geophysics Blvd', 'Montreal', 'Quebec', 'H3A 0G4', 'Canada', '/images/profiles/alex_rodriguez.jpg', 'Geophysicist specializing in seismic interpretation and gravity surveys. Expert in data processing and geological modeling software.', 'https://linkedin.com/in/alexrodriguez', NULL, '/resumes/alex_rodriguez_cv.pdf', 90.00, 'Busy'),
(9, 'Lisa', 'Chen', '+1-780-555-0105', '654 Petroleum Way', 'Edmonton', 'Alberta', 'T5J 0N3', 'Canada', '/images/profiles/lisa_chen.jpg', 'Petroleum engineer with extensive experience in reservoir engineering and drilling operations. PhD in Petroleum Engineering.', 'https://linkedin.com/in/lisachen', 'https://lisachen-consulting.com', '/resumes/lisa_chen_cv.pdf', 110.00, 'Available'),
(10, 'David', 'Wilson', '+1-306-555-0106', '987 Hydro Lane', 'Saskatoon', 'Saskatchewan', 'S7K 3J7', 'Canada', '/images/profiles/david_wilson.jpg', 'Hydrogeologist with 6+ years experience in groundwater assessment and contamination studies. Specialized in mining hydrology.', 'https://linkedin.com/in/davidwilson', NULL, '/resumes/david_wilson_cv.pdf', 80.00, 'Unavailable'),
(11, 'Maria', 'Garcia', '+1-204-555-0107', '147 GIS Street', 'Winnipeg', 'Manitoba', 'R3C 0V8', 'Canada', '/images/profiles/maria_garcia.jpg', 'GIS specialist with expertise in spatial analysis and geological mapping. Proficient in ArcGIS, QGIS, and remote sensing technologies.', 'https://linkedin.com/in/mariagarcia', 'https://mariagisgeo.com', '/resumes/maria_garcia_cv.pdf', 70.00, 'Available'),
(12, 'James', 'Brown', '+1-709-555-0108', '258 Drilling Ave', 'St. Johns', 'Newfoundland and Labrador', 'A1C 5M5', 'Canada', '/images/profiles/james_brown.jpg', 'Drilling supervisor with 12+ years in diamond drilling and exploration. Certified in safety management and equipment operation.', 'https://linkedin.com/in/jamesbrown', NULL, '/resumes/james_brown_cv.pdf', 100.00, 'Available');

-- Insert company details (assuming user_ids 13-16 for new companies)
INSERT INTO company_details (user_id, company_name, company_type, industry, company_size, website, description, founded_year, headquarters, logo) VALUES
(13, 'Gold Mining Corporation', 'Private Corporation', 'Mining', '201-500', 'https://goldminingcorp.com', 'Leading gold mining company with operations across North America. Committed to sustainable mining practices and community development.', 1995, 'Toronto, Ontario, Canada', '/images/logos/goldmining_corp.png'),
(14, 'Energy Explorers Ltd.', 'Limited Company', 'Oil & Gas', '51-200', 'https://energyexplorers.com', 'Innovative energy exploration company specializing in unconventional oil and gas resources. Focus on advanced drilling technologies.', 2008, 'Calgary, Alberta, Canada', '/images/logos/energy_explorers.png'),
(15, 'Mineral Tech Solutions', 'Corporation', 'Consulting', '11-50', 'https://mineraltech.com', 'Specialized consulting firm providing geological and mining engineering services to the mineral exploration industry.', 2012, 'Vancouver, British Columbia, Canada', '/images/logos/mineral_tech.png'),
(16, 'GeoSolutions Inc.', 'Incorporated', 'Geosciences', '51-200', 'https://geosolutions.com', 'Full-service geoscience company offering geological, geophysical, and environmental consulting services to mining and energy sectors.', 2005, 'Montreal, Quebec, Canada', '/images/logos/geosolutions.png');

-- Insert dummy projects
INSERT INTO projects (company_id, project_title, project_description, project_type, budget_min, budget_max, currency, duration_weeks, status, skills_required, location, is_remote, deadline) VALUES
(1, 'Gold Deposit Exploration - Northern Ontario', 'Comprehensive geological survey and resource estimation for a potential gold deposit. Requires detailed mapping, sampling, and preliminary resource calculations.', 'Exploration', 50000.00, 75000.00, 'CAD', 12, 'Published', '["Geology", "Resource Estimation", "Field Mapping", "Sampling"]', 'Timmins, Ontario', 0, '2025-06-15'),
(2, 'Seismic Data Interpretation Project', 'Analysis and interpretation of 3D seismic data for oil and gas exploration. Requires expertise in seismic processing software and structural interpretation.', 'Data Analysis', 30000.00, 45000.00, 'CAD', 8, 'Published', '["Geophysics", "Seismic Interpretation", "Petrel", "Kingdom Suite"]', 'Calgary, Alberta', 1, '2025-05-30'),
(3, 'Environmental Impact Assessment', 'Complete environmental assessment for proposed copper mining operation including baseline studies and impact mitigation strategies.', 'Environmental', 40000.00, 60000.00, 'CAD', 16, 'In Progress', '["Environmental Assessment", "Mining", "Regulatory Compliance"]', 'Sudbury, Ontario', 0, '2025-08-20'),
(4, 'Hydrogeological Study - Mine Dewatering', 'Comprehensive hydrogeological assessment for mine dewatering system design. Includes groundwater modeling and pumping system recommendations.', 'Hydrology', 25000.00, 35000.00, 'CAD', 10, 'Published', '["Hydrogeology", "Groundwater Modeling", "Mine Dewatering"]', 'Val-dOr, Quebec', 0, '2025-07-10'),
(1, 'GIS Mapping and Database Development', 'Development of comprehensive GIS database for mineral claims and geological data. Includes digitization of historical maps and data integration.', 'GIS/Mapping', 20000.00, 30000.00, 'CAD', 6, 'Published', '["GIS", "ArcGIS", "Database Management", "Geological Mapping"]', 'Toronto, Ontario', 1, '2025-04-25');

-- Insert dummy contracts
INSERT INTO contracts (project_id, freelancer_id, company_id, contract_title, contract_description, hourly_rate, total_amount, start_date, end_date, status, payment_terms, milestones) VALUES
(3, 7, 3, 'Environmental Assessment - Phase 1', 'Initial phase of environmental impact assessment including baseline data collection and preliminary analysis.', 75.00, 18000.00, '2025-02-01', '2025-04-30', 'Active', 'Net 30 days', '["Baseline Study Completion", "Data Analysis", "Preliminary Report"]'),
(1, 5, 1, 'Geological Survey Contract', 'Detailed geological mapping and sampling program for gold exploration project.', 85.00, 25500.00, '2025-03-15', '2025-06-15', 'Pending', 'Bi-weekly payments', '["Field Mapping", "Sample Collection", "Preliminary Analysis", "Final Report"]');

-- Insert dummy timesheets
INSERT INTO timesheets (contract_id, freelancer_id, date_worked, hours_worked, task_description, status_id, hourly_rate, total_amount) VALUES
(1, 7, '2025-03-01', 8.0, 'Site reconnaissance and initial environmental baseline measurements', 2, 75.00, 600.00),
(1, 7, '2025-03-02', 7.5, 'Water quality sampling and soil sample collection', 2, 75.00, 562.50),
(1, 7, '2025-03-05', 8.0, 'Flora and fauna survey in project area', 1, 75.00, 600.00),
(1, 7, '2025-03-06', 6.0, 'Data analysis and report preparation', 1, 75.00, 450.00);

-- Insert dummy notifications
INSERT INTO notifications (user_id, title, message, type, is_read, priority) VALUES
(5, 'New Project Match', 'A new gold exploration project matches your skills and location preferences.', 'project_match', 0, 'medium'),
(6, 'Contract Approved', 'Your contract proposal for the mining engineering project has been approved.', 'contract_update', 0, 'high'),
(7, 'Timesheet Approved', 'Your timesheet for March 1-2, 2025 has been approved for payment.', 'timesheet_update', 1, 'low'),
(13, 'New Proposal Received', 'You have received a new proposal for your Gold Deposit Exploration project.', 'proposal_received', 0, 'medium'),
(14, 'Project Milestone Completed', 'Phase 1 of your seismic interpretation project has been completed.', 'milestone_completed', 0, 'medium');

-- Insert dummy blogs
INSERT INTO blogs (title, content, author_id, category, status, featured_image, meta_description, tags) VALUES
('The Future of Gold Mining in Canada', 'Exploring the latest technologies and sustainable practices shaping the future of gold mining operations across Canada...', 1, 'Mining Technology', 'published', '/images/blog/gold-mining-future.jpg', 'Discover how new technologies are revolutionizing gold mining in Canada', 'gold mining, technology, sustainability, Canada'),
('Environmental Best Practices in Mineral Exploration', 'A comprehensive guide to implementing environmental best practices during mineral exploration activities...', 1, 'Environment', 'published', '/images/blog/environmental-practices.jpg', 'Learn about environmental best practices for responsible mineral exploration', 'environment, exploration, best practices, mining'),
('Career Opportunities in Geosciences', 'Overview of growing career opportunities in the geosciences sector, from geology to geophysics and environmental consulting...', 1, 'Career', 'published', '/images/blog/geoscience-careers.jpg', 'Explore exciting career opportunities in the geosciences industry', 'careers, geosciences, geology, opportunities');

-- Insert dummy dropdown categories and values
INSERT INTO dropdown_categories (category_name, description, is_active) VALUES
('Skills', 'Professional skills and expertise areas', 1),
('Industries', 'Industry sectors and specializations', 1),
('Experience_Levels', 'Professional experience levels', 1),
('Project_Types', 'Types of projects available', 1);

INSERT INTO dropdown_values (category_id, value_name, value_description, sort_order, is_active) VALUES
-- Skills
(1, 'Geology', 'General geology and geological mapping', 1, 1),
(1, 'Geophysics', 'Geophysical surveys and data interpretation', 2, 1),
(1, 'Mining Engineering', 'Mining operations and engineering', 3, 1),
(1, 'Environmental Assessment', 'Environmental impact studies', 4, 1),
(1, 'Hydrogeology', 'Groundwater and hydrogeological studies', 5, 1),
(1, 'GIS', 'Geographic Information Systems', 6, 1),
(1, 'Petroleum Engineering', 'Oil and gas engineering', 7, 1),
-- Industries
(2, 'Gold Mining', 'Gold exploration and mining', 1, 1),
(2, 'Base Metals', 'Copper, zinc, lead mining', 2, 1),
(2, 'Oil & Gas', 'Petroleum exploration and production', 3, 1),
(2, 'Environmental Consulting', 'Environmental services', 4, 1),
(2, 'Geotechnical', 'Geotechnical engineering', 5, 1),
-- Experience Levels
(3, 'Entry Level', '0-2 years experience', 1, 1),
(3, 'Mid Level', '3-7 years experience', 2, 1),
(3, 'Senior Level', '8-15 years experience', 3, 1),
(3, 'Expert Level', '15+ years experience', 4, 1),
-- Project Types
(4, 'Exploration', 'Mineral exploration projects', 1, 1),
(4, 'Environmental', 'Environmental assessment projects', 2, 1),
(4, 'Engineering', 'Engineering and design projects', 3, 1),
(4, 'Consulting', 'Consulting and advisory projects', 4, 1);

-- Insert dummy visitor logs
INSERT INTO visitor_logs (user_id, ip_address, user_agent, page_visited, session_duration) VALUES
(5, '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '/dashboard', 1800),
(6, '192.168.1.101', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', '/projects', 2400),
(7, '192.168.1.102', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '/contracts', 1200),
(13, '192.168.1.103', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', '/freelancers', 3600);

-- Update last_login for some users to make data more realistic
UPDATE users SET last_login = '2025-03-10 09:30:00' WHERE email = 'sarah.johnson@example.com';
UPDATE users SET last_login = '2025-03-09 14:45:00' WHERE email = 'mike.thompson@example.com';
UPDATE users SET last_login = '2025-03-10 11:15:00' WHERE email = 'emma.davis@example.com';
UPDATE users SET last_login = '2025-03-08 16:20:00' WHERE email = 'alex.rodriguez@example.com';
UPDATE users SET last_login = '2025-03-10 08:45:00' WHERE email = 'lisa.chen@example.com';
UPDATE users SET last_login = '2025-03-09 13:30:00' WHERE email = 'contact@goldminingcorp.com';
UPDATE users SET last_login = '2025-03-10 10:00:00' WHERE email = 'hr@energyexplorers.com';
