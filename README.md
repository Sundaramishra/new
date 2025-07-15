# 🏥 Hospital & Clinic Management CRM

A comprehensive Hospital and Clinic Management System built with PHP, MySQL, HTML, CSS, and JavaScript.

## 🚀 Quick Setup

### 1. Database Setup
```sql
-- Import the database
mysql -u root -p < database/hospital_crm.sql
```

### 2. Update Demo Passwords
```bash
# Run this once to properly hash demo passwords
php update_passwords.php
```

### 3. Configuration
- Update database credentials in `config/database.php` if needed
- Default: `localhost`, `root`, no password, database: `hospital_crm`

### 4. Run Project
```bash
# Using PHP built-in server
php -S localhost:8000

# Or copy to htdocs (XAMPP/WAMP)
# Access: http://localhost/hospital-crm
```

## 🔐 Demo Login Credentials

| Role | Email | Password |
|------|--------|----------|
| **Admin** | admin@hospital.com | password |
| **Doctor** | dr.sharma@hospital.com | password |
| **Patient** | john.doe@email.com | password |
| **Nurse** | priya.nurse@hospital.com | password |
| **Receptionist** | reception@hospital.com | password |

## 📋 Features Implemented

✅ **Multi-role Authentication System**  
✅ **Role-based Dashboard**  
✅ **Database with 25+ Tables**  
✅ **Demo Data Included**  
✅ **Responsive Design**  
✅ **Security Features**  
✅ **Patient Management** (Add, Search, View)  
✅ **Appointment Booking System** (Conflict checking, Time slots)  
✅ **Appointments Management** (View, Filter, Status updates)  
✅ **Role-based Access Control**  

## 🏗️ Project Structure

```
hospital-crm/
├── config/
│   └── database.php        # Database configuration
├── database/
│   └── hospital_crm.sql    # Complete database with demo data
├── index.php               # Login page
├── dashboard.php           # Main dashboard
├── logout.php              # Logout script
├── patients.php            # Patient management
├── book-appointment.php    # Appointment booking
├── appointments.php        # Appointments management
├── update_passwords.php    # Password update script
├── style.css               # Original styling
├── requirements.md         # Full requirements document
└── README.md               # This file
```

## 🎯 Next Steps

Based on your requirements, the following pages need to be developed:

**Admin Pages:**
- `patients.php` - Patient management
- `doctors.php` - Doctor management  
- `billing.php` - Billing system
- `reports.php` - Analytics & reports

**Doctor Pages:**
- `my-patients.php` - Assigned patients
- `appointments.php` - Appointment management
- `prescriptions.php` - Prescription system

**Patient Pages:**
- `book-appointment.php` - Appointment booking
- `my-bills.php` - Bill viewing
- `medical-records.php` - Medical history

## 🔧 Technical Details

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Authentication**: PHP Sessions
- **Security**: PDO Prepared Statements, Password Hashing

## 📝 Requirements Covered

All 41 requirements from your specification are documented in `requirements.md` and the database schema supports:

- Multi-role system (8 roles)
- Patient management (Inpatient/Outpatient)
- Appointment scheduling with conflict checking
- Billing automation
- Pharmacy & Lab management
- Equipment & Bed management
- Audit trails & activity logs

---

**🚀 Project Status**: Core foundation + Patient & Appointment management complete.  
**⏰ Ready for**: Additional features like billing, prescriptions, lab management, etc.

## 🎯 Current Features Working:
1. **Authentication System** - Multi-role login/logout
2. **Dashboard** - Role-specific views with statistics
3. **Patient Management** - Add, search, view patients  
4. **Appointment Booking** - Full booking system with conflict checking
5. **Appointment Management** - View and manage appointments by role

**Demo URL**: `http://localhost:8000` (after setup)