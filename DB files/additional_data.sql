-- Insert dummy notifications
INSERT INTO notifications (user_id, title, message, type, is_read, action_url) VALUES
(6, 'New Project Match', 'A new gold exploration project matches your skills and location preferences.', 'Info', 0, '/projects/1'),
(7, 'Contract Approved', 'Your contract proposal for the mining engineering project has been approved.', 'Success', 0, '/contracts/2'),
(8, 'Timesheet Approved', 'Your timesheet for March 1-2, 2025 has been approved for payment.', 'Success', 1, '/timesheets'),
(9, 'Payment Processed', 'Your payment for contract #3 has been processed and will be deposited within 2 business days.', 'Success', 0, '/payments'),
(10, 'Profile Views', 'Your profile has been viewed 15 times this week by potential clients.', 'Info', 1, '/profile'),
(14, 'New Proposal Received', 'You have received a new proposal for your Gold Deposit Exploration project.', 'Info', 0, '/proposals'),
(15, 'Project Milestone Completed', 'Phase 1 of your seismic interpretation project has been completed.', 'Success', 0, '/projects/2'),
(16, 'Contract Renewal', 'Your contract with Maria Garcia is up for renewal. Please review and approve.', 'Warning', 0, '/contracts/4'),
(17, 'New Freelancer Application', 'A new freelancer has applied for your hydrogeological study project.', 'Info', 0, '/applications');

-- Insert dummy blogs
INSERT INTO blogs (title, slug, content, excerpt, author_id, category, status, featured_image, tags) VALUES
('The Future of Gold Mining in Canada', 'future-gold-mining-canada', 'Exploring the latest technologies and sustainable practices shaping the future of gold mining operations across Canada. From autonomous vehicles to AI-powered ore sorting, the mining industry is experiencing a technological revolution that promises to make operations more efficient, safer, and environmentally responsible...', 'Discover how new technologies are revolutionizing gold mining in Canada', 1, 'Mining Technology', 'Published', '/images/blog/gold-mining-future.jpg', '["gold mining", "technology", "sustainability", "Canada"]'),
('Environmental Best Practices in Mineral Exploration', 'environmental-best-practices-exploration', 'A comprehensive guide to implementing environmental best practices during mineral exploration activities. This article covers everything from initial site assessment to post-exploration restoration, ensuring minimal environmental impact while maximizing exploration efficiency...', 'Learn about environmental best practices for responsible mineral exploration', 1, 'Environment', 'Published', '/images/blog/environmental-practices.jpg', '["environment", "exploration", "best practices", "mining"]'),
('Career Opportunities in Geosciences', 'career-opportunities-geosciences', 'Overview of growing career opportunities in the geosciences sector, from geology to geophysics and environmental consulting. The demand for skilled geoscience professionals continues to grow as the world seeks sustainable resource development...', 'Explore exciting career opportunities in the geosciences industry', 1, 'Career', 'Published', '/images/blog/geoscience-careers.jpg', '["careers", "geosciences", "geology", "opportunities"]'),
('Digital Transformation in Mining Operations', 'digital-transformation-mining', 'How digital technologies are transforming traditional mining operations. From IoT sensors to blockchain supply chain tracking, discover the innovations reshaping the industry...', 'Explore how digital transformation is changing mining operations', 1, 'Technology', 'Published', '/images/blog/digital-mining.jpg', '["digital", "mining", "IoT", "blockchain", "innovation"]'),
('Sustainable Mining Practices for the 21st Century', 'sustainable-mining-practices-21st-century', 'An in-depth look at sustainable mining practices that balance economic viability with environmental stewardship. Learn about circular economy principles in mining...', 'Discover sustainable mining practices for environmental stewardship', 1, 'Sustainability', 'Draft', '/images/blog/sustainable-mining.jpg', '["sustainability", "mining", "environment", "circular economy"]');

-- Insert dummy dropdown categories and values
INSERT IGNORE INTO dropdown_categories (category_name, description, is_active) VALUES
('Skills', 'Professional skills and expertise areas', 1),
('Industries', 'Industry sectors and specializations', 1),
('Experience_Levels', 'Professional experience levels', 1),
('Project_Types', 'Types of projects available', 1),
('Countries', 'Available countries for location', 1),
('Provinces', 'Canadian provinces and territories', 1);

INSERT IGNORE INTO dropdown_values (category_id, display_name, value, sort_order, is_active) VALUES
-- Skills (category_id = 1)
(1, 'Geology', 'General geology and geological mapping', 1, 1),
(1, 'Geophysics', 'Geophysical surveys and data interpretation', 2, 1),
(1, 'Mining Engineering', 'Mining operations and engineering', 3, 1),
(1, 'Environmental Assessment', 'Environmental impact studies', 4, 1),
(1, 'Hydrogeology', 'Groundwater and hydrogeological studies', 5, 1),
(1, 'GIS', 'Geographic Information Systems', 6, 1),
(1, 'Petroleum Engineering', 'Oil and gas engineering', 7, 1),
(1, 'Geochemistry', 'Chemical analysis of geological materials', 8, 1),
(1, 'Structural Geology', 'Analysis of rock structures and deformation', 9, 1),
(1, 'Resource Estimation', 'Mineral resource calculations and modeling', 10, 1),
-- Industries (category_id = 2)
(2, 'Gold Mining', 'Gold exploration and mining', 1, 1),
(2, 'Base Metals', 'Copper, zinc, lead mining', 2, 1),
(2, 'Oil & Gas', 'Petroleum exploration and production', 3, 1),
(2, 'Environmental Consulting', 'Environmental services', 4, 1),
(2, 'Geotechnical', 'Geotechnical engineering', 5, 1),
(2, 'Coal Mining', 'Coal exploration and mining', 6, 1),
(2, 'Diamond Mining', 'Diamond exploration and mining', 7, 1),
(2, 'Aggregates', 'Sand, gravel, and construction materials', 8, 1),
-- Experience Levels (category_id = 3)
(3, 'Entry Level', '0-2 years experience', 1, 1),
(3, 'Mid Level', '3-7 years experience', 2, 1),
(3, 'Senior Level', '8-15 years experience', 3, 1),
(3, 'Expert Level', '15+ years experience', 4, 1),
-- Project Types (category_id = 4)
(4, 'Exploration', 'Mineral exploration projects', 1, 1),
(4, 'Environmental', 'Environmental assessment projects', 2, 1),
(4, 'Engineering', 'Engineering and design projects', 3, 1),
(4, 'Consulting', 'Consulting and advisory projects', 4, 1),
(4, 'Data Analysis', 'Data processing and interpretation', 5, 1),
(4, 'Feasibility Study', 'Technical and economic feasibility studies', 6, 1),
-- Countries (category_id = 5)
(5, 'Canada', 'Canada', 1, 1),
(5, 'United States', 'United States of America', 2, 1),
(5, 'Australia', 'Australia', 3, 1),
(5, 'Chile', 'Chile', 4, 1),
(5, 'Peru', 'Peru', 5, 1),
-- Provinces (category_id = 6)
(6, 'Alberta', 'Alberta, Canada', 1, 1),
(6, 'British Columbia', 'British Columbia, Canada', 2, 1),
(6, 'Saskatchewan', 'Saskatchewan, Canada', 3, 1),
(6, 'Manitoba', 'Manitoba, Canada', 4, 1),
(6, 'Ontario', 'Ontario, Canada', 5, 1),
(6, 'Quebec', 'Quebec, Canada', 6, 1),
(6, 'New Brunswick', 'New Brunswick, Canada', 7, 1),
(6, 'Nova Scotia', 'Nova Scotia, Canada', 8, 1),
(6, 'Prince Edward Island', 'Prince Edward Island, Canada', 9, 1),
(6, 'Newfoundland and Labrador', 'Newfoundland and Labrador, Canada', 10, 1),
(6, 'Northwest Territories', 'Northwest Territories, Canada', 11, 1),
(6, 'Nunavut', 'Nunavut, Canada', 12, 1),
(6, 'Yukon', 'Yukon, Canada', 13, 1);

-- Insert dummy visitor logs
INSERT INTO visitor_logs (user_id, ip_address, user_agent, page_visited, session_duration) VALUES
(6, '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '/dashboard', 1800),
(7, '192.168.1.101', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', '/projects', 2400),
(8, '192.168.1.102', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '/contracts', 1200),
(9, '192.168.1.103', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15', '/profile', 900),
(10, '192.168.1.104', 'Mozilla/5.0 (Android 11; Mobile; rv:68.0) Gecko/68.0 Firefox/88.0', '/search', 600),
(14, '192.168.1.105', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', '/freelancers', 3600),
(15, '192.168.1.106', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '/post-project', 2100),
(16, '192.168.1.107', 'Mozilla/5.0 (iPad; CPU OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15', '/dashboard', 1500);

-- Insert dummy activity logs
INSERT INTO activity_logs (user_id, action_type, action_description, ip_address, user_agent) VALUES
(6, 'login', 'User logged in successfully', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(7, 'profile_update', 'User updated their profile information', '192.168.1.101', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'),
(8, 'timesheet_submit', 'User submitted timesheet for approval', '192.168.1.102', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(9, 'contract_sign', 'User signed contract agreement', '192.168.1.103', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15'),
(10, 'project_apply', 'User applied for project opportunity', '192.168.1.104', 'Mozilla/5.0 (Android 11; Mobile; rv:68.0) Gecko/68.0 Firefox/88.0'),
(14, 'project_post', 'Company posted new project', '192.168.1.105', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'),
(15, 'freelancer_hire', 'Company hired freelancer for project', '192.168.1.106', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(16, 'payment_process', 'Company processed payment to freelancer', '192.168.1.107', 'Mozilla/5.0 (iPad; CPU OS 14_7_1 like Mac OS X) AppleWebKit/605.1.15');
