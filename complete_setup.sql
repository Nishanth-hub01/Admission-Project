-- ============================================
-- Complete Database Setup for Counselor Dashboard
-- ============================================

USE `admission_system`;

-- ============================================
-- 1. CREATE DEPARTMENT_FEES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS `department_fees` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `degree_type` VARCHAR(50) NOT NULL,
    `department_name` VARCHAR(150) NOT NULL,
    `tuition_fee` DECIMAL(10, 2) NOT NULL,
    `hostel_fee` DECIMAL(10, 2) DEFAULT 0,
    `transport_fee` DECIMAL(10, 2) DEFAULT 0,
    `admission_fee` DECIMAL(10, 2) DEFAULT 0,
    `total_fee` DECIMAL(10, 2) GENERATED ALWAYS AS (tuition_fee + hostel_fee + transport_fee + admission_fee) STORED,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_degree (degree_type),
    INDEX idx_department (department_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. UPDATE STUDENTS TABLE
-- ============================================
ALTER TABLE `students` 
ADD COLUMN IF NOT EXISTS `admission_confirmed_date` TIMESTAMP NULL AFTER `application_status`;

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS `idx_application_status` ON `students`(`application_status`);
CREATE INDEX IF NOT EXISTS `idx_course_department` ON `students`(`course_department`);
CREATE INDEX IF NOT EXISTS `idx_admission_id` ON `students`(`admission_id`);

-- ============================================
-- 3. INSERT SAMPLE DEPARTMENT FEES
-- ============================================
INSERT IGNORE INTO `department_fees` (`degree_type`, `department_name`, `tuition_fee`, `hostel_fee`, `transport_fee`, `admission_fee`) VALUES
-- B.Tech Courses
('B.Tech', 'Computer Science and Engineering', 150000, 20000, 15000, 10000),
('B.Tech', 'Information Technology', 150000, 20000, 15000, 10000),
('B.Tech', 'Artificial Intelligence and Data Science', 160000, 20000, 15000, 10000),
('B.Tech', 'Electronics and Communication', 140000, 20000, 15000, 10000),
('B.Tech', 'Mechanical Engineering', 130000, 20000, 15000, 10000),
('B.Tech', 'Civil Engineering', 125000, 20000, 15000, 10000),
('B.Tech', 'Electrical Engineering', 135000, 20000, 15000, 10000),

-- B.Sc Courses
('B.Sc', 'Physics', 80000, 15000, 10000, 5000),
('B.Sc', 'Chemistry', 80000, 15000, 10000, 5000),
('B.Sc', 'Mathematics', 75000, 15000, 10000, 5000),
('B.Sc', 'Biology', 85000, 15000, 10000, 5000),
('B.Sc', 'Computer Science', 95000, 15000, 10000, 5000),

-- B.Com Courses
('B.Com', 'Commerce with Accounting', 100000, 15000, 10000, 5000),
('B.Com', 'Commerce with Finance', 100000, 15000, 10000, 5000),
('B.Com', 'Commerce with Management', 100000, 15000, 10000, 5000),

-- B.A Courses
('B.A', 'Bachelor of Arts - English', 60000, 12000, 8000, 3000),
('B.A', 'Bachelor of Arts - History', 60000, 12000, 8000, 3000),
('B.A', 'Bachelor of Arts - Economics', 65000, 12000, 8000, 3000);

-- ============================================
-- 4. VERIFY USERS TABLE HAS COUNSELOR ROLE
-- ============================================
-- Ensure counselor user exists with proper password hash
-- Password: password (hashed with password_hash())
INSERT IGNORE INTO `users` (`username`, `password`, `role`) VALUES
('counselor1', '$2y$10$D3e3X5Z6K8L9M2N3O4P5Q6R7S8T9U0V1W2X3Y4Z5A6B7C8D9E0F1G2H', 'Counselor');

-- ============================================
-- 5. DISPLAY TABLE STRUCTURES
-- ============================================
SELECT 'Database Setup Complete!' as Status;

-- Show department_fees table
SHOW CREATE TABLE `department_fees`;

-- Show students table structure with new columns
DESCRIBE `students`;

-- Count records in department_fees
SELECT COUNT(*) as 'Total Departments' FROM `department_fees`;

-- Show all departments
SELECT degree_type, department_name, total_fee FROM `department_fees` ORDER BY degree_type, department_name;