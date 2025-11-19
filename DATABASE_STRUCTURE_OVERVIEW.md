# GeoSpace Database Structure Overview

## Complete Table List (38 Tables + 1 System Table)

### 1. Authentication & User Management (6 tables)
- **roles** - User role definitions (Admin, Freelancer, Company, Support)
- **users** - Main user accounts
- **admin_details** - Admin user profile information
- **freelancer_details** - Freelancer profile information  
- **company_details** - Company profile information
- **support_details** - Support staff profile information

### 2. Freelancer Profile & Skills (7 tables)
- **education** - Educational background
- **certifications** - Professional certifications
- **skills** - Technical and soft skills
- **expertise** - Areas of expertise
- **work_experience** - Employment history
- **portfolio** - Portfolio items and projects
- **freelancer_bank_information** - Banking details for payments

### 3. Projects & Contracts (2 tables)
- **projects** - Job postings by companies
- **contracts** - Agreements between companies and freelancers

### 4. Time Tracking (4 tables)
- **timesheet_status** - Status lookup table
- **timesheets** - Weekly time records
- **timesheet_days** - Daily time entries
- **timesheet_day_comments** - Comments on daily entries

### 5. Financial System (4 tables)
- **invoices** - Generated invoices from timesheets
- **payment_requests** - Freelancer payment requests
- **payments** - Payment transactions
- **freelancer_earnings** - Earnings summary per freelancer

### 6. Feedback & Quality (1 table)
- **feedback** - Project completion feedback from companies

### 7. Dispute Management (3 tables)
- **dispute_status** - Status lookup table
- **dispute_tickets** - Dispute cases
- **dispute_messages** - Communication within disputes

### 8. Communication System (3 tables)
- **conversations** - Chat conversation threads
- **conversation_participants** - Participants in conversations
- **messages** - Individual chat messages

### 9. Support & Assistance (1 table)
- **video_support_requests** - Video call support scheduling

### 10. System Administration (4 tables)
- **menu_items** - Application menu structure
- **role_permissions** - Role-based access control
- **notifications** - User notifications
- **file_uploads** - File management

### 11. Logging & Analytics (2 tables)
- **activity_logs** - User activity tracking
- **visitor_logs** - Website visitor analytics

### 12. System Tables (2 tables)
- **migrations** - Laravel migration tracking
- **jobs** - Queue job management

## Key Relationships

### User Hierarchy
```
roles (1) -> (many) users
users (1) -> (1) admin_details
users (1) -> (1) freelancer_details
users (1) -> (1) company_details
users (1) -> (1) support_details
```

### Freelancer Profile
```
users (1) -> (many) education
users (1) -> (many) certifications
users (1) -> (many) skills
users (1) -> (many) expertise
users (1) -> (many) work_experience
users (1) -> (many) portfolio
freelancer_details (1) -> (many) freelancer_bank_information
```

### Project Workflow
```
company_details (1) -> (many) projects
projects (1) -> (many) contracts
contracts (1) -> (many) timesheets
timesheets (1) -> (1) invoices
timesheets (1) -> (many) payment_requests
contracts (1) -> (1) feedback
```

### Time Tracking Chain
```
contracts (1) -> (many) timesheets
timesheets (1) -> (many) timesheet_days
timesheet_days (1) -> (many) timesheet_day_comments
timesheet_status (1) -> (many) timesheets
```

### Financial Flow
```
timesheets (1) -> (1) invoices
timesheets (1) -> (many) payment_requests
invoices (1) -> (many) payments
payment_requests (1) -> (many) payments
users (freelancers) (1) -> (1) freelancer_earnings
```

### Dispute Resolution
```
contracts (1) -> (many) dispute_tickets
dispute_status (1) -> (many) dispute_tickets
dispute_tickets (1) -> (many) dispute_messages
users (1) -> (many) dispute_tickets (as creator)
users (1) -> (many) dispute_tickets (as assigned to)
```

### Communication
```
conversations (1) -> (many) conversation_participants
conversations (1) -> (many) messages
users (polymorphic) -> conversation_participants
users (polymorphic) -> messages
```

### System & Logging
```
users (1) -> (many) notifications
users (1) -> (many) activity_logs
users (1) -> (many) visitor_logs
users (1) -> (many) file_uploads
users (1) -> (many) video_support_requests
roles (1) -> (many) role_permissions
menu_items (1) -> (many) role_permissions
```

## Table Dependencies (Migration Order)

### Level 0 (No Dependencies)
- roles
- dispute_status
- timesheet_status
- menu_items
- migrations
- jobs
- conversations

### Level 1 (Depends on Level 0)
- users (depends on roles)

### Level 2 (Depends on Level 1)
- admin_details
- freelancer_details
- company_details
- support_details
- education
- certifications
- skills
- expertise
- work_experience
- portfolio
- file_uploads
- notifications
- activity_logs
- visitor_logs (also depends on roles)
- freelancer_earnings

### Level 3 (Depends on Level 2)
- freelancer_bank_information (depends on freelancer_details)
- projects (depends on company_details)

### Level 4 (Depends on Level 3)
- contracts (depends on projects, users, company_details)

### Level 5 (Depends on Level 4)
- timesheets (depends on contracts)
- feedback (depends on contracts)
- dispute_tickets (depends on contracts)
- video_support_requests (depends on users)

### Level 6 (Depends on Level 5)
- timesheet_days (depends on timesheets)
- invoices (depends on timesheets)
- payment_requests (depends on timesheets)
- dispute_messages (depends on dispute_tickets)

### Level 7 (Depends on Level 6)
- timesheet_day_comments (depends on timesheet_days)
- payments (depends on invoices, payment_requests)

### Level 8 (Depends on conversations)
- conversation_participants
- messages

### Level 9 (No foreign keys but logically dependent)
- role_permissions

## Data Types Used

### Text Types
- VARCHAR - Fixed or variable length strings
- TEXT - Long text content
- LONGTEXT - Very long text (for jobs table payload)

### Numeric Types
- INT - Standard integers
- BIGINT - Large integers (for IDs in conversation system)
- DECIMAL - Precise decimal numbers (for money and hours)
- TINYINT - Small integers (for boolean values)

### Date/Time Types
- DATE - Date only
- TIME - Time only
- TIMESTAMP - Date and time with timezone

### Special Types
- JSON - Flexible structured data
- ENUM - Fixed set of values
- BOOLEAN - True/false values (stored as TINYINT)

## Common Patterns

### Soft Deletes
Tables use foreign key cascade actions instead of soft deletes:
- CASCADE - Delete child records
- SET NULL - Preserve child records but null the foreign key
- RESTRICT - Prevent deletion if children exist

### Timestamps
Most tables include:
- created_at - Record creation timestamp
- updated_at - Last modification timestamp (auto-updated)

### Status Tracking
Multiple tables use status fields:
- contracts.status (Pending, Active, Completed, Cancelled, Disputed)
- timesheets.status_id (references timesheet_status)
- invoices.status (Generated, Sent, Paid, Overdue, Cancelled)
- payments.status (Pending, Completed, Failed, Refunded)

### Audit Fields
Several tables track who made changes:
- timesheets.reviewed_by
- payment_requests.processed_by
- payments.verified_by
- dispute_tickets.created_by, assigned_to

## Indexes

### Primary Keys
All tables use auto-incrementing integer primary keys

### Foreign Key Indexes
All foreign keys have indexes for query performance

### Additional Indexes
- User lookups (email, role_id, is_active)
- Status filters
- Date ranges (timesheet dates)
- Composite indexes for common query patterns

## Unique Constraints

- users.email
- admin_details.user_id
- freelancer_details.user_id
- support_details.user_id
- feedback.contract_id
- freelancer_earnings.freelancer_id
- invoices.invoice_number
- dispute_tickets.ticket_number
- dispute_status.status_name
- timesheet_status.status_name
- roles.role_name

## Check Constraints

### Feedback Ratings (1-5)
- attendance_rating
- work_quality_rating
- execution_speed_rating
- adaptability_rating
- general_feedback_rating

### Video Support Requests
- Must have either freelancer_id OR company_id (not both null)

## File Storage Fields

Tables that reference file storage:
- admin_details.profile_image
- freelancer_details.profile_image, resume_or_cv
- company_details.logo
- support_details.profile_image
- portfolio.image_url
- file_uploads (entire table)
- conversations.attachment_path
- messages.attachment_path
- dispute_tickets.attachment
- dispute_messages.attachment_url
- video_support_requests.video_link
- freelancer_bank_information.verification_document

## JSON Storage Fields

Tables using JSON for flexible data:
- activity_logs.old_values, new_values
- contracts.milestones
- portfolio.tags
- projects.skills_required

## Database Size Estimates

Based on column types and typical usage:

### Small Tables (< 1000 rows)
- roles, menu_items, dispute_status, timesheet_status

### Medium Tables (1K-100K rows)
- admin_details, support_details, company_details
- projects, contracts, feedback

### Large Tables (100K+ rows potential)
- users, freelancer_details
- education, certifications, skills, work_experience, portfolio
- timesheets, timesheet_days, invoices
- notifications, messages, activity_logs

### Very Large Tables (1M+ rows potential)
- visitor_logs (depending on tracking frequency)
- jobs (queue processing)

## Performance Considerations

1. **Indexes on Foreign Keys**: All foreign keys are indexed for join performance
2. **Composite Indexes**: Used for common query patterns (status + date ranges)
3. **JSON Columns**: May need indexing if frequently queried (MySQL 5.7+)
4. **Partitioning Candidates**: Consider for very large tables (visitor_logs, activity_logs)
5. **Archiving Strategy**: Old records in logs tables should be archived

## Maintenance Tasks

1. **Regular Backups**: Daily full backups recommended
2. **Index Optimization**: Monthly ANALYZE TABLE on large tables
3. **Log Rotation**: Archive old activity_logs and visitor_logs
4. **Dead Job Cleanup**: Clear completed jobs from jobs table
5. **File Cleanup**: Remove orphaned files not referenced in database
