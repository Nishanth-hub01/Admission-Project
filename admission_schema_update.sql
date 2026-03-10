-- Update students table to include admission_id
ALTER TABLE students ADD COLUMN admission_id VARCHAR(20) UNIQUE AFTER id;

-- Create index on admission_id for faster search
CREATE INDEX idx_admission_id ON students(admission_id);

-- Add admission_id to existing records (optional - for migration)
-- UPDATE students SET admission_id = CONCAT('SI', DATE_FORMAT(created_at, '%d%m'), LPAD(ROW_NUMBER() OVER (PARTITION BY DATE(created_at) ORDER BY id), 3, '0')) WHERE admission_id IS NULL;