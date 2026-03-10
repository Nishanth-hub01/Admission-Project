-- Updated Database Schema with Complete Student Information

-- Drop existing tables if they exist
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS admin_logs;
DROP TABLE IF EXISTS users;

-- Create Users Table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Support Staff', 'Counselor', 'Cashier', 'Management') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create Students Table with Extended Fields
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    
    -- Personal Details
    full_name VARCHAR(100) NOT NULL,
    date_of_birth DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    nationality VARCHAR(50) DEFAULT 'Indian',
    religion VARCHAR(50),
    community ENUM('General', 'OBC', 'SC', 'ST') DEFAULT 'General',
    aadhaar_number VARCHAR(12) UNIQUE,
    blood_group VARCHAR(5),
    passport_photo VARCHAR(255),
    
    -- Contact Details
    mobile_number VARCHAR(15) NOT NULL UNIQUE,
    alternate_mobile VARCHAR(15),
    email_id VARCHAR(100) NOT NULL UNIQUE,
    permanent_address TEXT NOT NULL,
    current_address TEXT,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    pincode VARCHAR(6) NOT NULL,
    
    -- Parent/Guardian Details
    father_name VARCHAR(100),
    mother_name VARCHAR(100),
    guardian_name VARCHAR(100),
    parent_occupation VARCHAR(100),
    parent_mobile VARCHAR(15),
    parent_email VARCHAR(100),
    annual_family_income BIGINT,
    
    -- Academic Details (10th)
    class_10_school VARCHAR(150),
    class_10_board ENUM('State Board', 'CBSE', 'ICSE', 'IB') DEFAULT 'State Board',
    class_10_register_number VARCHAR(50) UNIQUE,
    class_10_percentage DECIMAL(5, 2),
    class_10_marksheet VARCHAR(255),
    
    -- Academic Details (12th)
    class_12_school VARCHAR(150),
    class_12_board ENUM('State Board', 'CBSE', 'ICSE', 'IB') DEFAULT 'State Board',
    class_12_register_number VARCHAR(50) UNIQUE,
    class_12_percentage DECIMAL(5, 2),
    class_12_subjects VARCHAR(255),
    class_12_marksheet VARCHAR(255),
    
    -- Entrance Exam Details
    entrance_exam_type ENUM('JEE Main', 'NEET', 'CUET', 'State CET', 'Other'),
    entrance_exam_score DECIMAL(5, 2),
    entrance_exam_scorecard VARCHAR(255),
    
    -- Course Selection
    degree_type ENUM('B.Tech', 'B.Sc', 'B.Com', 'B.A', 'Other') DEFAULT 'B.Tech',
    course_department VARCHAR(100),
    preferred_specialization VARCHAR(100),
    admission_type ENUM('Merit', 'Management', 'Entrance') DEFAULT 'Merit',
    
    -- Document Details
    transfer_certificate VARCHAR(255),
    community_certificate VARCHAR(255),
    income_certificate VARCHAR(255),
    aadhaar_card VARCHAR(255),
    migration_certificate VARCHAR(255),
    
    -- Bank Details
    account_holder_name VARCHAR(100),
    bank_name VARCHAR(100),
    account_number VARCHAR(20),
    ifsc_code VARCHAR(11),
    branch_name VARCHAR(100),
    
    -- Additional Information
    hostel_requirement ENUM('Yes', 'No') DEFAULT 'No',
    transport_requirement ENUM('Yes', 'No') DEFAULT 'No',
    scholarship_details TEXT,
    sports_achievements TEXT,
    medical_information TEXT,
    
    -- Document Verification
    documents_verified ENUM('Yes', 'No') DEFAULT 'No',
    verified_by INT,
    verified_date TIMESTAMP NULL,
    
    -- Status Fields
    application_status ENUM('Submitted', 'Under Review', 'Approved', 'Rejected') DEFAULT 'Submitted',
    department VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (verified_by) REFERENCES users(id),
    INDEX idx_mobile (mobile_number),
    INDEX idx_aadhaar (aadhaar_number),
    INDEX idx_name (full_name),
    INDEX idx_10th_register (class_10_register_number),
    INDEX idx_12th_register (class_12_register_number)
);

-- Create Payments Table
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    total_fee DECIMAL(10, 2) NOT NULL,
    paid_amount DECIMAL(10, 2) DEFAULT 0,
    payment_status ENUM('Paid', 'Pending') DEFAULT 'Pending',
    payment_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Create Admin Logs Table
CREATE TABLE admin_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NOT NULL,
    action VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id)
);

-- Insert Default Users
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$D3e3X5Z6K8L9M2N3O4P5Q6R7S8T9U0V1W2X3Y4Z5A6B7C8D9E0F1G2H', 'Admin'),
('support1', '$2y$10$D3e3X5Z6K8L9M2N3O4P5Q6R7S8T9U0V1W2X3Y4Z5A6B7C8D9E0F1G2H', 'Support Staff'),
('counselor1', '$2y$10$D3e3X5Z6K8L9M2N3O4P5Q6R7S8T9U0V1W2X3Y4Z5A6B7C8D9E0F1G2H', 'Counselor'),
('cashier1', '$2y$10$D3e3X5Z6K8L9M2N3O4P5Q6R7S8T9U0V1W2X3Y4Z5A6B7C8D9E0F1G2H', 'Cashier'),
('management1', '$2y$10$D3e3X5Z6K8L9M2N3O4P5Q6R7S8T9U0V1W2X3Y4Z5A6B7C8D9E0F1G2H', 'Management');