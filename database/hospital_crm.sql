-- Hospital & Clinic Management CRM Database
-- Created for comprehensive hospital management system

CREATE DATABASE IF NOT EXISTS hospital_crm;
USE hospital_crm;

-- =============================================
-- SYSTEM CONFIGURATION TABLES
-- =============================================

-- System Settings Table
CREATE TABLE system_settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'json') DEFAULT 'text',
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =============================================
-- USER MANAGEMENT TABLES
-- =============================================

-- Roles Table
CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    role_display_name VARCHAR(100) NOT NULL,
    description TEXT,
    permissions JSON,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Users Table (Main Authentication)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    password_reset_token VARCHAR(255) NULL,
    password_reset_expires TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- =============================================
-- ORGANIZATION STRUCTURE
-- =============================================

-- Hospitals/Clinics Table
CREATE TABLE hospitals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(200) NOT NULL,
    code VARCHAR(20) NOT NULL UNIQUE,
    type ENUM('hospital', 'clinic', 'diagnostic_center') DEFAULT 'hospital',
    address TEXT,
    city VARCHAR(100),
    state VARCHAR(100),
    country VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(150),
    license_number VARCHAR(100),
    registration_number VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    logo_path VARCHAR(255),
    favicon_path VARCHAR(255),
    theme_color VARCHAR(7) DEFAULT '#004685',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Departments Table
CREATE TABLE departments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    code VARCHAR(20) NOT NULL,
    description TEXT,
    head_doctor_id INT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    UNIQUE KEY unique_dept_code (hospital_id, code)
);

-- =============================================
-- STAFF MANAGEMENT TABLES
-- =============================================

-- Doctors Table
CREATE TABLE doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    hospital_id INT NOT NULL,
    department_id INT NULL,
    employee_id VARCHAR(50) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(200),
    qualification TEXT,
    experience_years INT DEFAULT 0,
    registration_number VARCHAR(100),
    phone VARCHAR(20),
    emergency_contact VARCHAR(20),
    address TEXT,
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    blood_group VARCHAR(5),
    profile_image VARCHAR(255),
    certificates JSON,
    awards JSON,
    consultation_fee DECIMAL(10,2) DEFAULT 0.00,
    is_available BOOLEAN DEFAULT TRUE,
    joined_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    UNIQUE KEY unique_employee_id (hospital_id, employee_id)
);

-- Nurses Table
CREATE TABLE nurses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    hospital_id INT NOT NULL,
    department_id INT NULL,
    employee_id VARCHAR(50) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    qualification VARCHAR(200),
    experience_years INT DEFAULT 0,
    registration_number VARCHAR(100),
    phone VARCHAR(20),
    emergency_contact VARCHAR(20),
    address TEXT,
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    blood_group VARCHAR(5),
    profile_image VARCHAR(255),
    shift_type ENUM('day', 'night', 'rotating') DEFAULT 'day',
    joined_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    UNIQUE KEY unique_nurse_employee_id (hospital_id, employee_id)
);

-- Staff Table (General Staff)
CREATE TABLE staff (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    hospital_id INT NOT NULL,
    department_id INT NULL,
    employee_id VARCHAR(50) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    designation VARCHAR(150),
    phone VARCHAR(20),
    emergency_contact VARCHAR(20),
    address TEXT,
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    profile_image VARCHAR(255),
    joined_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    UNIQUE KEY unique_staff_employee_id (hospital_id, employee_id)
);

-- Lab Technicians Table
CREATE TABLE lab_technicians (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    hospital_id INT NOT NULL,
    employee_id VARCHAR(50) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    specialization VARCHAR(200),
    qualification VARCHAR(200),
    experience_years INT DEFAULT 0,
    license_number VARCHAR(100),
    phone VARCHAR(20),
    profile_image VARCHAR(255),
    joined_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    UNIQUE KEY unique_lab_employee_id (hospital_id, employee_id)
);

-- Pharmacy Staff Table
CREATE TABLE pharmacy_staff (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    hospital_id INT NOT NULL,
    employee_id VARCHAR(50) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    qualification VARCHAR(200),
    license_number VARCHAR(100),
    phone VARCHAR(20),
    profile_image VARCHAR(255),
    joined_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    UNIQUE KEY unique_pharmacy_employee_id (hospital_id, employee_id)
);

-- Receptionists Table
CREATE TABLE receptionists (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    hospital_id INT NOT NULL,
    employee_id VARCHAR(50) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    emergency_contact VARCHAR(20),
    address TEXT,
    shift_type ENUM('day', 'night', 'rotating') DEFAULT 'day',
    profile_image VARCHAR(255),
    joined_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    UNIQUE KEY unique_receptionist_employee_id (hospital_id, employee_id)
);

-- =============================================
-- PATIENT MANAGEMENT TABLES
-- =============================================

-- Patients Table
CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NULL,
    hospital_id INT NOT NULL,
    patient_id VARCHAR(50) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    emergency_contact VARCHAR(20),
    email VARCHAR(150),
    address TEXT,
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    blood_group VARCHAR(5),
    marital_status ENUM('single', 'married', 'divorced', 'widowed'),
    occupation VARCHAR(100),
    insurance_details JSON,
    medical_history TEXT,
    allergies TEXT,
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    UNIQUE KEY unique_patient_id (hospital_id, patient_id)
);

-- Patient Visits Table
CREATE TABLE patient_visits (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    hospital_id INT NOT NULL,
    visit_number VARCHAR(50) NOT NULL,
    visit_type ENUM('outpatient', 'inpatient', 'emergency') DEFAULT 'outpatient',
    visit_reason TEXT,
    attendant_name VARCHAR(150),
    attendant_relation VARCHAR(50),
    attendant_contact VARCHAR(20),
    admission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    discharge_date TIMESTAMP NULL,
    status ENUM('active', 'discharged', 'transferred') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    UNIQUE KEY unique_visit_number (hospital_id, visit_number)
);

-- =============================================
-- APPOINTMENT MANAGEMENT
-- =============================================

-- Appointments Table
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT NOT NULL,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    appointment_number VARCHAR(50) NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration_minutes INT DEFAULT 30,
    type ENUM('consultation', 'followup', 'emergency', 'video_call', 'home_visit') DEFAULT 'consultation',
    status ENUM('scheduled', 'confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'scheduled',
    chief_complaint TEXT,
    notes TEXT,
    consultation_fee DECIMAL(10,2) DEFAULT 0.00,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    UNIQUE KEY unique_appointment_number (hospital_id, appointment_number)
);

-- =============================================
-- MEDICAL RECORDS
-- =============================================

-- Patient Vitals Table
CREATE TABLE patient_vitals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    visit_id INT NULL,
    recorded_by INT NOT NULL,
    height_cm DECIMAL(5,2),
    weight_kg DECIMAL(5,2),
    temperature_f DECIMAL(4,1),
    blood_pressure_systolic INT,
    blood_pressure_diastolic INT,
    heart_rate INT,
    respiratory_rate INT,
    oxygen_saturation DECIMAL(5,2),
    blood_sugar DECIMAL(6,2),
    notes TEXT,
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (visit_id) REFERENCES patient_visits(id),
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);

-- Medical Prescriptions Table
CREATE TABLE prescriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    visit_id INT NULL,
    prescription_number VARCHAR(50) NOT NULL,
    diagnosis TEXT,
    instructions TEXT,
    follow_up_date DATE,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    FOREIGN KEY (visit_id) REFERENCES patient_visits(id)
);

-- =============================================
-- PHARMACY MANAGEMENT
-- =============================================

-- Medicines Table
CREATE TABLE medicines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    generic_name VARCHAR(200),
    brand VARCHAR(100),
    category VARCHAR(100),
    dosage_form VARCHAR(50),
    strength VARCHAR(50),
    manufacturer VARCHAR(150),
    sku_code VARCHAR(50),
    batch_number VARCHAR(50),
    expiry_date DATE,
    unit_price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    min_stock_level INT DEFAULT 10,
    is_prescription_required BOOLEAN DEFAULT TRUE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id)
);

-- Prescription Medicines Table
CREATE TABLE prescription_medicines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    prescription_id INT NOT NULL,
    medicine_id INT NOT NULL,
    dosage VARCHAR(100),
    frequency VARCHAR(100),
    duration VARCHAR(100),
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2),
    total_price DECIMAL(10,2),
    instructions TEXT,
    FOREIGN KEY (prescription_id) REFERENCES prescriptions(id),
    FOREIGN KEY (medicine_id) REFERENCES medicines(id)
);

-- =============================================
-- LAB MANAGEMENT
-- =============================================

-- Lab Tests Table
CREATE TABLE lab_tests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT NOT NULL,
    test_name VARCHAR(200) NOT NULL,
    test_code VARCHAR(50) NOT NULL,
    category VARCHAR(100),
    description TEXT,
    sample_type VARCHAR(100),
    normal_range VARCHAR(200),
    cost DECIMAL(10,2) NOT NULL,
    duration_hours INT DEFAULT 24,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    UNIQUE KEY unique_test_code (hospital_id, test_code)
);

-- Patient Lab Orders Table
CREATE TABLE lab_orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT NOT NULL,
    patient_id INT NOT NULL,
    doctor_id INT NOT NULL,
    order_number VARCHAR(50) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('ordered', 'sample_collected', 'in_progress', 'completed', 'cancelled') DEFAULT 'ordered',
    priority ENUM('normal', 'urgent', 'stat') DEFAULT 'normal',
    clinical_notes TEXT,
    total_cost DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    UNIQUE KEY unique_lab_order_number (hospital_id, order_number)
);

-- Lab Order Tests Table
CREATE TABLE lab_order_tests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lab_order_id INT NOT NULL,
    lab_test_id INT NOT NULL,
    test_cost DECIMAL(10,2),
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    result_value VARCHAR(500),
    result_unit VARCHAR(50),
    reference_range VARCHAR(200),
    interpretation TEXT,
    technician_id INT NULL,
    completed_at TIMESTAMP NULL,
    FOREIGN KEY (lab_order_id) REFERENCES lab_orders(id),
    FOREIGN KEY (lab_test_id) REFERENCES lab_tests(id),
    FOREIGN KEY (technician_id) REFERENCES lab_technicians(id)
);

-- =============================================
-- BILLING SYSTEM
-- =============================================

-- Bills Table
CREATE TABLE bills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT NOT NULL,
    patient_id INT NOT NULL,
    visit_id INT NULL,
    bill_number VARCHAR(50) NOT NULL,
    bill_date DATE NOT NULL,
    bill_type ENUM('consultation', 'pharmacy', 'lab', 'admission', 'equipment', 'miscellaneous') DEFAULT 'consultation',
    subtotal DECIMAL(12,2) DEFAULT 0.00,
    discount_amount DECIMAL(12,2) DEFAULT 0.00,
    tax_amount DECIMAL(12,2) DEFAULT 0.00,
    total_amount DECIMAL(12,2) DEFAULT 0.00,
    paid_amount DECIMAL(12,2) DEFAULT 0.00,
    balance_amount DECIMAL(12,2) DEFAULT 0.00,
    payment_status ENUM('pending', 'partial', 'paid', 'refunded') DEFAULT 'pending',
    payment_method ENUM('cash', 'card', 'online', 'insurance', 'cheque') NULL,
    notes TEXT,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    FOREIGN KEY (patient_id) REFERENCES patients(id),
    FOREIGN KEY (visit_id) REFERENCES patient_visits(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    UNIQUE KEY unique_bill_number (hospital_id, bill_number)
);

-- Bill Items Table
CREATE TABLE bill_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    bill_id INT NOT NULL,
    item_type ENUM('consultation', 'medicine', 'test', 'equipment', 'bed', 'miscellaneous') NOT NULL,
    item_name VARCHAR(200) NOT NULL,
    item_code VARCHAR(50),
    quantity INT DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    discount_amount DECIMAL(10,2) DEFAULT 0.00,
    final_price DECIMAL(10,2) NOT NULL,
    reference_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bill_id) REFERENCES bills(id)
);

-- =============================================
-- HOSPITAL RESOURCES
-- =============================================

-- Beds Table
CREATE TABLE beds (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT NOT NULL,
    bed_number VARCHAR(20) NOT NULL,
    room_number VARCHAR(20),
    floor_number VARCHAR(10),
    bed_type ENUM('general', 'private', 'icu', 'emergency', 'maternity') DEFAULT 'general',
    department_id INT NULL,
    cost_per_day DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('available', 'occupied', 'maintenance', 'reserved') DEFAULT 'available',
    current_patient_id INT NULL,
    assigned_nurse_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    FOREIGN KEY (current_patient_id) REFERENCES patients(id),
    FOREIGN KEY (assigned_nurse_id) REFERENCES nurses(id),
    UNIQUE KEY unique_bed_number (hospital_id, bed_number)
);

-- Equipment Table
CREATE TABLE equipment (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    equipment_code VARCHAR(50) NOT NULL,
    category VARCHAR(100),
    manufacturer VARCHAR(150),
    model VARCHAR(100),
    serial_number VARCHAR(100),
    purchase_date DATE,
    warranty_expiry DATE,
    department_id INT NULL,
    status ENUM('available', 'in_use', 'maintenance', 'out_of_order') DEFAULT 'available',
    cost_per_use DECIMAL(10,2) DEFAULT 0.00,
    maintenance_schedule TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id),
    FOREIGN KEY (department_id) REFERENCES departments(id),
    UNIQUE KEY unique_equipment_code (hospital_id, equipment_code)
);

-- =============================================
-- AUDIT & LOGGING
-- =============================================

-- Activity Logs Table
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    hospital_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    table_name VARCHAR(100),
    record_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id)
);

-- =============================================
-- DEMO DATA INSERTION
-- =============================================

-- Insert System Settings
INSERT INTO system_settings (setting_key, setting_value, setting_type, description) VALUES
('app_name', 'Hospital CRM', 'text', 'Application Name'),
('app_version', '1.0.0', 'text', 'Application Version'),
('timezone', 'Asia/Kolkata', 'text', 'Default Timezone'),
('currency', 'INR', 'text', 'Default Currency'),
('date_format', 'Y-m-d', 'text', 'Default Date Format'),
('time_format', 'H:i:s', 'text', 'Default Time Format'),
('max_appointment_duration', '60', 'number', 'Maximum Appointment Duration in Minutes'),
('enable_sms', 'true', 'boolean', 'Enable SMS Notifications'),
('enable_email', 'true', 'boolean', 'Enable Email Notifications'),
('default_consultation_fee', '500.00', 'number', 'Default Consultation Fee');

-- Insert Roles
INSERT INTO roles (role_name, role_display_name, description, permissions) VALUES
('admin', 'Administrator', 'System Administrator with full access', '{"all": true}'),
('doctor', 'Doctor', 'Medical practitioners', '{"patients": "assigned", "appointments": "own", "prescriptions": "create"}'),
('patient', 'Patient', 'Hospital patients', '{"appointments": "own", "bills": "own", "reports": "own"}'),
('nurse', 'Nurse', 'Nursing staff', '{"patients": "assigned", "vitals": "update", "medications": "view"}'),
('staff', 'Staff', 'General hospital staff', '{"basic": "limited"}'),
('pharmacy', 'Pharmacy', 'Pharmacy staff', '{"medicines": "manage", "prescriptions": "fulfill"}'),
('lab_tech', 'Lab Technician', 'Laboratory technicians', '{"tests": "conduct", "results": "upload"}'),
('receptionist', 'Receptionist', 'Front desk staff', '{"patients": "manage", "appointments": "manage"}');

-- Insert Demo Hospital
INSERT INTO hospitals (name, code, type, address, city, state, country, phone, email, license_number) VALUES
('City General Hospital', 'CGH001', 'hospital', '123 Main Street, Medical District', 'Mumbai', 'Maharashtra', 'India', '+91-22-12345678', 'info@citygeneralhospital.com', 'LIC/2023/CGH/001');

-- Insert Demo Users
INSERT INTO users (username, email, password_hash, role_id) VALUES
('admin', 'admin@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('dr.sharma', 'dr.sharma@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('patient001', 'john.doe@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3),
('nurse.priya', 'priya.nurse@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4),
('reception', 'reception@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 8);

-- Insert Demo Departments
INSERT INTO departments (hospital_id, name, code, description) VALUES
(1, 'Cardiology', 'CARD', 'Heart and cardiovascular diseases'),
(1, 'Neurology', 'NEUR', 'Brain and nervous system disorders'),
(1, 'Pediatrics', 'PEDI', 'Child healthcare'),
(1, 'Emergency', 'EMER', 'Emergency medical services'),
(1, 'Laboratory', 'LAB', 'Diagnostic laboratory services');

-- Insert Demo Doctor
INSERT INTO doctors (user_id, hospital_id, department_id, employee_id, first_name, middle_name, last_name, specialization, qualification, experience_years, registration_number, phone, consultation_fee, joined_date) VALUES
(2, 1, 1, 'DOC001', 'Rajesh', 'Kumar', 'Sharma', 'Cardiology', 'MBBS, MD (Cardiology)', 15, 'MCI/2010/12345', '+91-98765-43210', 1000.00, '2020-01-15');

-- Insert Demo Nurse
INSERT INTO nurses (user_id, hospital_id, department_id, employee_id, first_name, last_name, qualification, experience_years, phone, shift_type, joined_date) VALUES
(4, 1, 1, 'NUR001', 'Priya', 'Singh', 'BSc Nursing', 8, '+91-98765-43211', 'day', '2021-03-10');

-- Insert Demo Receptionist
INSERT INTO receptionists (user_id, hospital_id, employee_id, first_name, last_name, phone, shift_type, joined_date) VALUES
(5, 1, 'REC001', 'Sunita', 'Patel', '+91-98765-43212', 'day', '2022-01-05');

-- Insert Demo Patient
INSERT INTO patients (user_id, hospital_id, patient_id, first_name, last_name, phone, email, date_of_birth, gender, blood_group) VALUES
(3, 1, 'PAT001', 'John', 'Doe', '+91-98765-43213', 'john.doe@email.com', '1985-06-15', 'male', 'O+');

-- Insert Demo Medicines
INSERT INTO medicines (hospital_id, name, generic_name, category, dosage_form, strength, unit_price, stock_quantity) VALUES
(1, 'Paracetamol', 'Acetaminophen', 'Analgesic', 'Tablet', '500mg', 5.00, 1000),
(1, 'Amoxicillin', 'Amoxicillin', 'Antibiotic', 'Capsule', '250mg', 15.00, 500),
(1, 'Aspirin', 'Acetylsalicylic Acid', 'Anti-inflammatory', 'Tablet', '75mg', 3.00, 800);

-- Insert Demo Lab Tests
INSERT INTO lab_tests (hospital_id, test_name, test_code, category, cost, duration_hours) VALUES
(1, 'Complete Blood Count', 'CBC', 'Hematology', 300.00, 4),
(1, 'Blood Sugar Fasting', 'BSF', 'Biochemistry', 150.00, 2),
(1, 'Lipid Profile', 'LIPID', 'Biochemistry', 400.00, 6),
(1, 'ECG', 'ECG', 'Cardiology', 200.00, 1);

-- Insert Demo Beds
INSERT INTO beds (hospital_id, bed_number, room_number, floor_number, bed_type, cost_per_day, status) VALUES
(1, 'B001', 'R101', '1', 'general', 800.00, 'available'),
(1, 'B002', 'R101', '1', 'general', 800.00, 'available'),
(1, 'P001', 'R201', '2', 'private', 2000.00, 'available'),
(1, 'ICU001', 'ICU1', '3', 'icu', 5000.00, 'available');

-- Insert Demo Equipment
INSERT INTO equipment (hospital_id, name, equipment_code, category, manufacturer, status, cost_per_use) VALUES
(1, 'X-Ray Machine', 'XRAY001', 'Imaging', 'Siemens', 'available', 500.00),
(1, 'ECG Machine', 'ECG001', 'Cardiology', 'Philips', 'available', 200.00),
(1, 'Ultrasound Scanner', 'USG001', 'Imaging', 'GE Healthcare', 'available', 800.00);

-- Create Indexes for Performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role_id);
CREATE INDEX idx_patients_phone ON patients(phone);
CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_appointments_doctor ON appointments(doctor_id);
CREATE INDEX idx_bills_patient ON bills(patient_id);
CREATE INDEX idx_bills_date ON bills(bill_date);
CREATE INDEX idx_activity_logs_user ON activity_logs(user_id);
CREATE INDEX idx_activity_logs_date ON activity_logs(created_at);

-- =============================================
-- STORED PROCEDURES
-- =============================================

DELIMITER //

-- Procedure to generate next patient ID
CREATE PROCEDURE GetNextPatientId(IN hospital_id INT, OUT next_id VARCHAR(50))
BEGIN
    DECLARE current_count INT DEFAULT 0;
    SELECT COUNT(*) INTO current_count FROM patients WHERE patients.hospital_id = hospital_id;
    SET next_id = CONCAT('PAT', LPAD(current_count + 1, 6, '0'));
END //

-- Procedure to check appointment conflicts
CREATE PROCEDURE CheckAppointmentConflict(
    IN p_doctor_id INT,
    IN p_appointment_date DATE,
    IN p_appointment_time TIME,
    IN p_duration_minutes INT,
    OUT conflict_count INT
)
BEGIN
    DECLARE end_time TIME;
    SET end_time = ADDTIME(p_appointment_time, SEC_TO_TIME(p_duration_minutes * 60));
    
    SELECT COUNT(*) INTO conflict_count
    FROM appointments
    WHERE doctor_id = p_doctor_id
    AND appointment_date = p_appointment_date
    AND status NOT IN ('cancelled', 'completed')
    AND (
        (appointment_time BETWEEN p_appointment_time AND end_time)
        OR (ADDTIME(appointment_time, SEC_TO_TIME(duration_minutes * 60)) BETWEEN p_appointment_time AND end_time)
        OR (p_appointment_time BETWEEN appointment_time AND ADDTIME(appointment_time, SEC_TO_TIME(duration_minutes * 60)))
    );
END //

DELIMITER ;

-- Create Views for Common Queries
CREATE VIEW patient_summary AS
SELECT 
    p.id,
    p.patient_id,
    CONCAT(p.first_name, ' ', IFNULL(p.middle_name, ''), ' ', p.last_name) AS full_name,
    p.phone,
    p.email,
    p.gender,
    p.blood_group,
    TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) AS age,
    h.name AS hospital_name
FROM patients p
JOIN hospitals h ON p.hospital_id = h.id;

CREATE VIEW doctor_summary AS
SELECT 
    d.id,
    d.employee_id,
    CONCAT(d.first_name, ' ', IFNULL(d.middle_name, ''), ' ', d.last_name) AS full_name,
    d.specialization,
    d.consultation_fee,
    dept.name AS department_name,
    h.name AS hospital_name,
    u.email,
    u.is_active
FROM doctors d
JOIN hospitals h ON d.hospital_id = h.id
LEFT JOIN departments dept ON d.department_id = dept.id
JOIN users u ON d.user_id = u.id;

-- Final Success Message
SELECT 'Hospital CRM Database Created Successfully!' AS Status,
       'Default Password for all demo users: password' AS Note,
       'Remember to change passwords in production!' AS Warning;