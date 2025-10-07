-- Insert dummy projects
INSERT INTO projects (company_id, project_title, project_description, project_type, budget_min, budget_max, currency, duration_weeks, status, skills_required, location, is_remote, deadline) VALUES
(1, 'Gold Deposit Exploration - Northern Ontario', 'Comprehensive geological survey and resource estimation for a potential gold deposit. Requires detailed mapping, sampling, and preliminary resource calculations.', 'Exploration', 50000.00, 75000.00, 'CAD', 12, 'Published', '["Geology", "Resource Estimation", "Field Mapping", "Sampling"]', 'Timmins, Ontario', 0, '2025-06-15'),
(2, 'Seismic Data Interpretation Project', 'Analysis and interpretation of 3D seismic data for oil and gas exploration. Requires expertise in seismic processing software and structural interpretation.', 'Data Analysis', 30000.00, 45000.00, 'CAD', 8, 'Published', '["Geophysics", "Seismic Interpretation", "Petrel", "Kingdom Suite"]', 'Calgary, Alberta', 1, '2025-05-30'),
(3, 'Environmental Impact Assessment', 'Complete environmental assessment for proposed copper mining operation including baseline studies and impact mitigation strategies.', 'Environmental', 40000.00, 60000.00, 'CAD', 16, 'In Progress', '["Environmental Assessment", "Mining", "Regulatory Compliance"]', 'Sudbury, Ontario', 0, '2025-08-20'),
(4, 'Hydrogeological Study - Mine Dewatering', 'Comprehensive hydrogeological assessment for mine dewatering system design. Includes groundwater modeling and pumping system recommendations.', 'Hydrology', 25000.00, 35000.00, 'CAD', 10, 'Published', '["Hydrogeology", "Groundwater Modeling", "Mine Dewatering"]', 'Val-dOr, Quebec', 0, '2025-07-10'),
(1, 'GIS Mapping and Database Development', 'Development of comprehensive GIS database for mineral claims and geological data. Includes digitization of historical maps and data integration.', 'GIS/Mapping', 20000.00, 30000.00, 'CAD', 6, 'Published', '["GIS", "ArcGIS", "Database Management", "Geological Mapping"]', 'Toronto, Ontario', 1, '2025-04-25'),
(2, 'Petroleum Reservoir Analysis', 'Detailed analysis of petroleum reservoir characteristics including porosity, permeability, and production potential assessment.', 'Engineering', 35000.00, 50000.00, 'CAD', 14, 'Published', '["Petroleum Engineering", "Reservoir Analysis", "Production Optimization"]', 'Edmonton, Alberta', 0, '2025-09-15'),
(3, 'Mining Feasibility Study', 'Complete feasibility study for proposed underground mining operation including economic analysis and technical assessment.', 'Feasibility', 80000.00, 120000.00, 'CAD', 20, 'Draft', '["Mining Engineering", "Economic Analysis", "Technical Assessment"]', 'Sudbury, Ontario', 0, '2025-12-31');

-- Insert dummy contracts
INSERT INTO contracts (project_id, freelancer_id, company_id, contract_title, contract_description, hourly_rate, total_amount, start_date, end_date, status, payment_terms, milestones) VALUES
(3, 8, 3, 'Environmental Assessment - Phase 1', 'Initial phase of environmental impact assessment including baseline data collection and preliminary analysis.', 75.00, 18000.00, '2025-02-01', '2025-04-30', 'Active', 'Net 30 days', '["Baseline Study Completion", "Data Analysis", "Preliminary Report"]'),
(1, 6, 1, 'Geological Survey Contract', 'Detailed geological mapping and sampling program for gold exploration project.', 85.00, 25500.00, '2025-03-15', '2025-06-15', 'Pending', 'Bi-weekly payments', '["Field Mapping", "Sample Collection", "Preliminary Analysis", "Final Report"]'),
(2, 9, 2, 'Seismic Interpretation Services', 'Complete seismic data interpretation and structural analysis for oil and gas exploration.', 90.00, 28800.00, '2025-03-01', '2025-04-30', 'Active', 'Monthly payments', '["Data Processing", "Structural Interpretation", "Report Generation"]'),
(5, 12, 1, 'GIS Database Development', 'Creation and maintenance of comprehensive GIS database for mineral exploration data.', 70.00, 16800.00, '2025-02-15', '2025-04-15', 'Completed', 'Net 15 days', '["Database Design", "Data Entry", "Quality Control", "Documentation"]'),
(4, 11, 4, 'Hydrogeological Assessment', 'Groundwater assessment and modeling for mine dewatering system design.', 80.00, 19200.00, '2025-03-10', '2025-05-10', 'Active', 'Net 30 days', '["Site Investigation", "Data Analysis", "Modeling", "Final Report"]');

-- Insert timesheet statuses if not exists
INSERT IGNORE INTO timesheet_status (status_id, status_name, status_description) VALUES
(1, 'Pending', 'Timesheet submitted and awaiting approval'),
(2, 'Approved', 'Timesheet approved for payment'),
(3, 'Rejected', 'Timesheet rejected and requires revision'),
(4, 'Paid', 'Timesheet processed and payment completed');

-- Insert dummy timesheets
INSERT INTO timesheets (contract_id, user_id, work_date, work_hours, task_description, status_id) VALUES
(1, 8, '2025-03-01', 8.0, 'Site reconnaissance and initial environmental baseline measurements', 2),
(1, 8, '2025-03-02', 7.5, 'Water quality sampling and soil sample collection', 2),
(1, 8, '2025-03-05', 8.0, 'Flora and fauna survey in project area', 1),
(1, 8, '2025-03-06', 6.0, 'Data analysis and report preparation', 1),
(3, 9, '2025-03-01', 8.0, 'Initial seismic data review and quality assessment', 2),
(3, 9, '2025-03-02', 8.0, 'Structural interpretation of seismic sections', 2),
(3, 9, '2025-03-05', 7.0, 'Horizon picking and fault interpretation', 1),
(5, 11, '2025-03-10', 8.0, 'Site visit and groundwater monitoring well installation', 2),
(5, 11, '2025-03-11', 6.5, 'Groundwater sampling and field measurements', 1),
(4, 12, '2025-02-15', 8.0, 'GIS database design and structure planning', 4),
(4, 12, '2025-02-16', 8.0, 'Historical map digitization and georeferencing', 4),
(4, 12, '2025-02-19', 7.0, 'Data quality control and validation', 4);

-- Update last_login for some users to make data more realistic
UPDATE users SET last_login = '2025-03-10 09:30:00' WHERE email = 'sarah.johnson@example.com';
UPDATE users SET last_login = '2025-03-09 14:45:00' WHERE email = 'mike.thompson@example.com';
UPDATE users SET last_login = '2025-03-10 11:15:00' WHERE email = 'emma.davis@example.com';
UPDATE users SET last_login = '2025-03-08 16:20:00' WHERE email = 'alex.rodriguez@example.com';
UPDATE users SET last_login = '2025-03-10 08:45:00' WHERE email = 'lisa.chen@example.com';
UPDATE users SET last_login = '2025-03-09 13:30:00' WHERE email = 'contact@goldminingcorp.com';
UPDATE users SET last_login = '2025-03-10 10:00:00' WHERE email = 'hr@energyexplorers.com';
