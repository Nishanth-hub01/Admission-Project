-- Create department_fees table if it doesn't exist
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

-- Insert sample department fees
INSERT INTO `department_fees` (`degree_type`, `department_name`, `tuition_fee`, `hostel_fee`, `transport_fee`, `admission_fee`) VALUES
('B.Tech', 'Computer Science and Engineering', 150000, 20000, 15000, 10000),
('B.Tech', 'Information Technology', 150000, 20000, 15000, 10000),
('B.Tech', 'Artificial Intelligence and Data Science', 160000, 20000, 15000, 10000),
('B.Tech', 'Electronics and Communication', 140000, 20000, 15000, 10000),
('B.Tech', 'Mechanical Engineering', 130000, 20000, 15000, 10000),
('B.Tech', 'Civil Engineering', 125000, 20000, 15000, 10000),
('B.Sc', 'Physics', 80000, 15000, 10000, 5000),
('B.Sc', 'Chemistry', 80000, 15000, 10000, 5000),
('B.Sc', 'Mathematics', 75000, 15000, 10000, 5000),
('B.Sc', 'Biology', 85000, 15000, 10000, 5000),
('B.Com', 'Commerce with Accounting', 100000, 15000, 10000, 5000),
('B.Com', 'Commerce with Finance', 100000, 15000, 10000, 5000),
('B.A', 'Bachelor of Arts', 60000, 12000, 8000, 3000);

-- Verify the table was created
SHOW TABLES LIKE 'department_fees';