-- Insert dummy dropdown categories and values
INSERT IGNORE INTO dropdown_categories (category_name, description, is_active) VALUES
('Skills', 'Professional skills and expertise areas', 1),
('Industries', 'Industry sectors and specializations', 1),
('Experience_Levels', 'Professional experience levels', 1),
('Project_Types', 'Types of projects available', 1);

INSERT IGNORE INTO dropdown_values (category_id, display_name, value, sort_order, is_active) VALUES
-- Skills (category_id = 1)
(1, 'Geology', 'geology', 1, 1),
(1, 'Geophysics', 'geophysics', 2, 1),
(1, 'Mining Engineering', 'mining_engineering', 3, 1),
(1, 'Environmental Assessment', 'environmental_assessment', 4, 1),
(1, 'Hydrogeology', 'hydrogeology', 5, 1),
(1, 'GIS', 'gis', 6, 1),
(1, 'Petroleum Engineering', 'petroleum_engineering', 7, 1),
-- Industries (category_id = 2)
(2, 'Gold Mining', 'gold_mining', 1, 1),
(2, 'Base Metals', 'base_metals', 2, 1),
(2, 'Oil & Gas', 'oil_gas', 3, 1),
(2, 'Environmental Consulting', 'environmental_consulting', 4, 1),
-- Experience Levels (category_id = 3)
(3, 'Entry Level', 'entry_level', 1, 1),
(3, 'Mid Level', 'mid_level', 2, 1),
(3, 'Senior Level', 'senior_level', 3, 1),
(3, 'Expert Level', 'expert_level', 4, 1),
-- Project Types (category_id = 4)
(4, 'Exploration', 'exploration', 1, 1),
(4, 'Environmental', 'environmental', 2, 1),
(4, 'Engineering', 'engineering', 3, 1),
(4, 'Consulting', 'consulting', 4, 1);

-- Insert dummy visitor logs
INSERT IGNORE INTO visitor_logs (user_id, role_id, device_info, ip_address, page_visited, session_duration) VALUES
(6, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '192.168.1.100', '/dashboard', 1800),
(7, 2, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', '192.168.1.101', '/projects', 2400),
(8, 2, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36', '192.168.1.102', '/contracts', 1200),
(14, 3, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36', '192.168.1.103', '/freelancers', 3600);

-- Insert dummy activity logs
INSERT IGNORE INTO activity_logs (user_id, action, ip_address, user_agent) VALUES
(6, 'User logged in successfully', '192.168.1.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(7, 'User updated their profile information', '192.168.1.101', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36'),
(8, 'User submitted timesheet for approval', '192.168.1.102', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'),
(14, 'Company posted new project', '192.168.1.103', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36');
