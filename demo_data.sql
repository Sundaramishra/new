-- Demo Data for Hospital CRM
-- This file adds sample data for testing and demonstration

USE `hospital_crm`;

-- Demo Users (password for all: demo123)
INSERT INTO `users` (`username`, `email`, `password_hash`, `role`, `first_name`, `last_name`, `phone`, `address`, `status`) VALUES
('doctor1', 'doctor1@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor', 'Dr. Rajesh', 'Sharma', '+91-9876543210', '123 Medical Colony, Delhi', 'active'),
('doctor2', 'doctor2@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor', 'Dr. Priya', 'Patel', '+91-9876543211', '456 Health Street, Mumbai', 'active'),
('nurse1', 'nurse1@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'nurse', 'Sister Mary', 'Joseph', '+91-9876543212', '789 Care Avenue, Bangalore', 'active'),
('nurse2', 'nurse2@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'nurse', 'Nurse Anita', 'Singh', '+91-9876543213', '321 Wellness Road, Chennai', 'active'),
('receptionist1', 'reception@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'receptionist', 'Sunita', 'Gupta', '+91-9876543214', '654 Reception Plaza, Pune', 'active'),
('lab_tech1', 'lab@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lab_technician', 'Vikram', 'Kumar', '+91-9876543215', '987 Lab Complex, Hyderabad', 'active'),
('staff1', 'staff1@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'staff', 'Ramesh', 'Yadav', '+91-9876543216', '147 Staff Quarter, Kolkata', 'active');

-- Doctor Details
INSERT INTO `doctor_details` (`user_id`, `specialization_id`, `license_number`, `qualification`, `experience_years`, `consultation_fee`, `working_hours`) VALUES
(2, 1, 'MED2024001', 'MBBS, MD (General Medicine)', 10, 500.00, 'Mon-Fri: 9:00 AM - 5:00 PM, Sat: 9:00 AM - 1:00 PM'),
(3, 2, 'MED2024002', 'MBBS, MD (Cardiology)', 15, 800.00, 'Mon-Sat: 10:00 AM - 6:00 PM');

-- Sample Patients
INSERT INTO `patients` (`patient_id`, `first_name`, `last_name`, `email`, `phone`, `date_of_birth`, `gender`, `blood_group`, `address`, `emergency_contact`, `height`, `weight`, `blood_pressure`, `heart_rate`, `oxygen_level`, `allergies`, `medical_history`) VALUES
('P20240001', 'Amit', 'Kumar', 'amit.kumar@email.com', '+91-9876543220', '1985-03-15', 'male', 'B+', '123 Patient Street, Delhi', '+91-9876543221', 175.00, 70.50, '120/80', 72, 98.50, 'None', 'No significant medical history'),
('P20240002', 'Priya', 'Sharma', 'priya.sharma@email.com', '+91-9876543222', '1990-07-22', 'female', 'A+', '456 Health Colony, Mumbai', '+91-9876543223', 162.00, 58.00, '115/75', 68, 99.00, 'Penicillin allergy', 'Asthma since childhood'),
('P20240003', 'Rohit', 'Patel', 'rohit.patel@email.com', '+91-9876543224', '1975-11-08', 'male', 'O+', '789 Wellness Road, Bangalore', '+91-9876543225', 180.00, 85.00, '130/85', 75, 97.50, 'None', 'Diabetes Type 2'),
('P20240004', 'Sneha', 'Singh', 'sneha.singh@email.com', '+91-9876543226', '1988-01-30', 'female', 'AB-', '321 Care Avenue, Chennai', '+91-9876543227', 158.00, 52.00, '110/70', 65, 98.80, 'Shellfish allergy', 'Migraine'),
('P20240005', 'Vikash', 'Gupta', 'vikash.gupta@email.com', '+91-9876543228', '1995-09-12', 'male', 'A-', '654 Medical Plaza, Pune', '+91-9876543229', 170.00, 68.00, '125/82', 70, 98.20, 'None', 'No significant medical history'),
('P20240006', 'Kavita', 'Yadav', 'kavita.yadav@email.com', '+91-9876543230', '1982-05-18', 'female', 'B-', '987 Health Complex, Hyderabad', '+91-9876543231', 165.00, 60.00, '118/78', 69, 99.20, 'Dust allergy', 'Thyroid disorder'),
('P20240007', 'Rajesh', 'Mehta', 'rajesh.mehta@email.com', '+91-9876543232', '1978-12-03', 'male', 'O-', '147 Patient Quarter, Kolkata', '+91-9876543233', 178.00, 75.00, '135/90', 78, 97.80, 'None', 'Hypertension'),
('P20240008', 'Anita', 'Joshi', 'anita.joshi@email.com', '+91-9876543234', '1992-04-25', 'female', 'A+', '258 Care Street, Jaipur', '+91-9876543235', 160.00, 55.00, '112/72', 67, 98.90, 'Latex allergy', 'No significant medical history');

-- Sample Appointments
INSERT INTO `appointments` (`appointment_id`, `patient_id`, `doctor_id`, `appointment_date`, `appointment_time`, `status`, `reason`, `notes`, `created_by`) VALUES
('APT20240001', 1, 2, '2024-01-15', '10:00:00', 'confirmed', 'Regular checkup', 'Patient seems healthy', 5),
('APT20240002', 2, 3, '2024-01-15', '11:00:00', 'confirmed', 'Chest pain consultation', 'ECG recommended', 5),
('APT20240003', 3, 2, '2024-01-16', '09:30:00', 'scheduled', 'Diabetes follow-up', 'Blood sugar monitoring required', 5),
('APT20240004', 4, 2, '2024-01-16', '14:00:00', 'scheduled', 'Migraine treatment', 'MRI scan suggested', 5),
('APT20240005', 5, 3, '2024-01-17', '10:30:00', 'scheduled', 'Heart health checkup', 'Preventive cardiology', 5),
('APT20240006', 6, 2, '2024-01-17', '15:00:00', 'scheduled', 'Thyroid consultation', 'TSH levels monitoring', 5),
('APT20240007', 7, 3, '2024-01-18', '11:30:00', 'scheduled', 'Hypertension management', 'BP monitoring', 5),
('APT20240008', 8, 2, '2024-01-18', '16:00:00', 'scheduled', 'General consultation', 'Annual health checkup', 5);

-- Sample Bills
INSERT INTO `bills` (`bill_number`, `patient_id`, `appointment_id`, `total_amount`, `paid_amount`, `status`, `payment_method`, `created_by`) VALUES
('BILL20240001', 1, 1, 500.00, 500.00, 'paid', 'cash', 5),
('BILL20240002', 2, 2, 800.00, 400.00, 'partial', 'card', 5),
('BILL20240003', 3, 3, 500.00, 0.00, 'pending', NULL, 5),
('BILL20240004', 4, 4, 500.00, 500.00, 'paid', 'upi', 5),
('BILL20240005', 5, 5, 800.00, 0.00, 'pending', NULL, 5);

-- Sample Bill Items
INSERT INTO `bill_items` (`bill_id`, `description`, `quantity`, `unit_price`) VALUES
(1, 'Consultation Fee - General Medicine', 1, 500.00),
(2, 'Consultation Fee - Cardiology', 1, 800.00),
(3, 'Consultation Fee - General Medicine', 1, 500.00),
(4, 'Consultation Fee - General Medicine', 1, 500.00),
(5, 'Consultation Fee - Cardiology', 1, 800.00);

-- Sample Lab Reports
INSERT INTO `lab_reports` (`report_id`, `patient_id`, `test_name`, `test_type`, `test_date`, `results`, `normal_range`, `status`, `technician_id`) VALUES
('LAB20240001', 1, 'Complete Blood Count', 'Blood Test', '2024-01-15', 'WBC: 7500/μL, RBC: 4.5M/μL, Platelets: 250K/μL', 'WBC: 4000-11000/μL, RBC: 4.2-5.4M/μL, Platelets: 150-450K/μL', 'completed', 6),
('LAB20240002', 2, 'Chest X-Ray', 'Radiology', '2024-01-15', 'Clear lung fields, normal heart size', 'Normal chest anatomy', 'completed', 6),
('LAB20240003', 3, 'Blood Glucose', 'Blood Test', '2024-01-16', 'Fasting: 140 mg/dL, PP: 180 mg/dL', 'Fasting: 70-100 mg/dL, PP: <140 mg/dL', 'completed', 6),
('LAB20240004', 4, 'MRI Brain', 'Radiology', '2024-01-16', 'No abnormal findings', 'Normal brain anatomy', 'pending', 6);

-- Sample Salary Records
INSERT INTO `salaries` (`user_id`, `month`, `basic_salary`, `allowances`, `deductions`, `status`, `created_by`) VALUES
(2, '2024-01-01', 80000.00, 15000.00, 5000.00, 'paid', 1),
(3, '2024-01-01', 120000.00, 20000.00, 8000.00, 'paid', 1),
(4, '2024-01-01', 45000.00, 8000.00, 3000.00, 'paid', 1),
(5, '2024-01-01', 35000.00, 5000.00, 2000.00, 'paid', 1),
(6, '2024-01-01', 40000.00, 6000.00, 2500.00, 'paid', 1),
(7, '2024-01-01', 25000.00, 3000.00, 1500.00, 'paid', 1);

-- Sample Notifications
INSERT INTO `notifications` (`user_id`, `title`, `message`, `type`) VALUES
(2, 'New Appointment', 'You have a new appointment scheduled for tomorrow at 10:00 AM', 'info'),
(3, 'Lab Report Ready', 'Lab report for patient Priya Sharma is ready for review', 'success'),
(5, 'Payment Received', 'Payment of ₹500 received for Bill #BILL20240001', 'success'),
(1, 'System Update', 'System maintenance scheduled for tonight at 11:00 PM', 'warning');

-- Sample Communication Logs
INSERT INTO `communication_logs` (`type`, `recipient`, `subject`, `message`, `status`, `sent_at`, `created_by`) VALUES
('email', 'amit.kumar@email.com', 'Appointment Confirmation', 'Your appointment has been confirmed for tomorrow at 10:00 AM', 'sent', '2024-01-14 18:30:00', 5),
('sms', '+91-9876543220', NULL, 'Reminder: Appointment tomorrow at 10:00 AM with Dr. Rajesh Sharma', 'sent', '2024-01-14 19:00:00', 5),
('email', 'priya.sharma@email.com', 'Lab Report Ready', 'Your lab report is ready. Please visit to collect.', 'sent', '2024-01-15 16:45:00', 6);

COMMIT;