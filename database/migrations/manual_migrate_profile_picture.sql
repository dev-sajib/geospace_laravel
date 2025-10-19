-- Migration Script: Move profile_picture from users to user_details.profile_image
-- Execute this BEFORE running the Laravel migration

-- Step 1: Backup existing data (optional but recommended)
-- You can skip this if you're confident

-- Step 2: Copy profile_picture from users to user_details.profile_image
-- This will only update records where profile_picture exists in users table
UPDATE user_details ud
INNER JOIN users u ON ud.user_id = u.user_id
SET ud.profile_image = u.profile_picture
WHERE u.profile_picture IS NOT NULL 
  AND (ud.profile_image IS NULL OR ud.profile_image = '');

-- Step 3: Verify the data was copied correctly
-- Run this query to check - should return 0 rows if everything is correct
SELECT 
    u.user_id,
    u.email,
    u.profile_picture as users_profile_picture,
    ud.profile_image as user_details_profile_image
FROM users u
LEFT JOIN user_details ud ON u.user_id = ud.user_id
WHERE u.profile_picture IS NOT NULL 
  AND u.profile_picture != ''
  AND (ud.profile_image IS NULL OR ud.profile_image = '');

-- Step 4: After verification, run the Laravel migration:
-- php artisan migrate
-- This will drop the profile_picture column from users table
