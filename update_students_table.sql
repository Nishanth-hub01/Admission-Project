-- Add missing columns to students table if they don't exist

ALTER TABLE `students` 
ADD COLUMN IF NOT EXISTS `admission_confirmed_date` TIMESTAMP NULL AFTER `application_status`;

-- Create index for faster queries
CREATE INDEX IF NOT EXISTS `idx_application_status` ON `students`(`application_status`);
CREATE INDEX IF NOT EXISTS `idx_course_department` ON `students`(`course_department`);

-- Verify the structure
DESCRIBE `students`;