-- Update students table to include admission_id
ALTER TABLE students ADD COLUMN IF NOT EXISTS admission_id VARCHAR(20) UNIQUE AFTER id;

-- Update community field to include Tamil Nadu categories
ALTER TABLE students MODIFY COLUMN community ENUM('General', 'OC', 'BC', 'BCM', 'MBC/DNC', 'SC', 'SCA', 'ST', 'Other') DEFAULT 'General';

-- Add community_other field for manual entry when Other is selected
ALTER TABLE students ADD COLUMN IF NOT EXISTS community_other VARCHAR(100) AFTER community;

-- Update application_status to include new workflow statuses
ALTER TABLE students MODIFY COLUMN application_status ENUM('Enquiry', 'Payment Pending', 'Confirmed', 'Rejected') DEFAULT 'Enquiry';

-- Add first graduate fields
ALTER TABLE students ADD COLUMN IF NOT EXISTS first_graduate ENUM('Yes','No') AFTER blood_group;
ALTER TABLE students ADD COLUMN IF NOT EXISTS first_graduate_certificate VARCHAR(255) AFTER first_graduate;

-- Add programme_choice to store selected programme options
ALTER TABLE students ADD COLUMN IF NOT EXISTS class_12_subject_1_marks DECIMAL(5,2) AFTER class_12_percentage;
ALTER TABLE students ADD COLUMN IF NOT EXISTS class_12_subject_2_marks DECIMAL(5,2) AFTER class_12_subject_1_marks;
ALTER TABLE students ADD COLUMN IF NOT EXISTS class_12_subject_3_marks DECIMAL(5,2) AFTER class_12_subject_2_marks;
ALTER TABLE students ADD COLUMN IF NOT EXISTS class_12_subject_4_marks DECIMAL(5,2) AFTER class_12_subject_3_marks;
ALTER TABLE students ADD COLUMN IF NOT EXISTS class_12_subject_5_marks DECIMAL(5,2) AFTER class_12_subject_4_marks;
ALTER TABLE students ADD COLUMN IF NOT EXISTS programme_choice TEXT AFTER class_12_marksheet;

-- Create index on admission_id for faster search
CREATE INDEX IF NOT EXISTS idx_admission_id ON students(admission_id);

-- Add admission_id to existing records (optional - for migration)
-- UPDATE students SET admission_id = CONCAT('SI', DATE_FORMAT(created_at, '%d%m'), LPAD(ROW_NUMBER() OVER (PARTITION BY DATE(created_at) ORDER BY id), 3, '0')) WHERE admission_id IS NULL;