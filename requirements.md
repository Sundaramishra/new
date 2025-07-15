# Hospital & Clinic Management CRM - Requirements Document

## Project Overview
**Technology Stack**: HTML, CSS, PHP, MySQL, JavaScript  
**Design Theme**: Cliniva Angular theme inspired  
**Project Type**: Easy-to-use Hospital & Clinic Management CRM  

---

## Section 1: Development Principles
- **Code Phase Restriction**: Development will not begin until ALL requirements are finalized
- **Completeness Guarantee**: No requirement will be missed during implementation

---

## Section 2: Technology Stack & File Structure
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **File Delivery Order**: CSS files, include files, and SQL files will be delivered last

---

## Section 3: Core Project Details
**Project**: Hospital & Clinic Management Easy CRM

---

## Section 4: Design Requirements

### 4.1 Base Design
- Design similar to **Cliniva Angular theme**

### 4.2 Customization Features (Section 5)
- **5(a) Customizable Elements**:
  - Logo customization
  - Favicon customization  
  - Title customization
  - Color scheme customization
  - Day/Night mode UI toggle
- **5(b) Responsive Design**: 100% responsive across all devices

---

## Section 5: System Architecture

### 5.1 Modular Design (Section 6)
- Adding new pages/roles should not affect existing functionality
- **6(a)** New modules should be easily adoptable without touching doctor dashboard

---

## Section 6: Multi-Role Authentication System (Section 7)

### 6.1 User Roles
1. **Admin**
2. **Doctor** 
3. **Patient**
4. **Nurse**
5. **Staff**
6. **Pharmacy**
7. **Lab Tech**
8. **Receptionist**

### 6.2 Role Management
- Role activation controlled by Admin
- All logins use **encrypted passwords** (Section 8)

### 6.3 Doctor Management (Section 7)
- **7(a)** Doctor list visible in admin dashboard
- **7(b)** Complete doctor details:
  - Name, Middle name, Surname
  - Contact information
  - Address
  - Education background
  - Experience details
  - Certificates
  - Awards
  - Vitals
  - Profile image
- **7(c)** Only admin can add/edit/delete doctors
- **7(d)** Department system (optional - admin controlled)
- **7(e)** Department assignments: doctor/nurse/lab/pharmacy
- **7(f)** Doctor contact details visible only to admin

---

## Section 7: Doctor Functionality (Section 9)

### 7.1 Patient Access Control
- Doctors can only view/consult/update their assigned patients
- **9(a)** Appointment conflict checking system

---

## Section 8: Admin Dashboard (Section 10)
- **Graph system** for analytics and reporting

---

## Section 9: Bed Management (Section 11)
**Access Roles**: Admin, Nurse, Doctor, Receptionist

---

## Section 10: Equipment Management (Section 12)
- Admin-controlled activation
- Equipment tracking and assignment

---

## Section 11: Billing System (Section 13)
**Auto-billing features**:
- Consultancy fees
- Test charges  
- Medicine costs
- Equipment usage charges

---

## Section 12: Test Management (Section 14)

### 12.1 Test Department System
- **14(a)** Test module activation by admin
- **14(b)** Detailed records of test conductors
- Separate department assignments for different doctors

---

## Section 13: Patient Management (Section 15)

### 13.1 Patient List Access
- **Admin** and **Receptionist** can view patient lists
- Both roles can add new patients

### 13.2 Patient Health Records (Section 15b)
- **Doctor, Nurse, and assigned Test staff** can update patient vitals and health information

---

## Section 14: Patient Portal (Section 16)
- Patients can view their payment receipts
- Access to personal billing history

---

## Section 15: Pharmacy Management (Section 17)

### 15.1 Pharmacy Details
- **17(a)** Pharmacy information:
  - Pharmacy name
  - License details
  - Staff member details

### 15.2 Inventory Management  
- **17(b)** Stock management:
  - SKU and Non-SKU stock tracking
  - User-friendly filtering system

---

## Section 16: Medicine & Equipment Billing (Section 18)

### 16.1 Auto-billing Integration
- Doctor-prescribed medicines automatically added to bill
- Equipment usage automatically billed

### 16.2 Price Management
- **18(a)** Medicine prices manually set by admin/receptionist
- **18(b)** Admitted patient vitals and medicine logging
- **18(c)** Outpatient management system

---

## Section 17: Patient Type Management (Section 19)
**Inpatient ↔ Outpatient conversion system**

---

## Section 18: Nurse Management (Section 20)

### 18.1 Nurse Profile
- Profile similar to doctor but without certificates/awards
- **20(a)** Nurses can only view their assigned patients

---

## Section 19: Staff Management (Section 21)
- Staff profile similar to nurse profile
- Limited access rights

---

## Section 20: Intern System (Sections 22-24)

### 20.1 Intern Categories (Section 22)
- Doctor interns
- Nurse interns  
- Lab interns
- Pharmacy interns

### 20.2 Intern Management (Section 23)
- Admin-controlled intern system activation

### 20.3 Intern Access Control (Section 24)
- Access rights based on role assignment
- Senior contact information visible only to senior staff

---

## Section 21: Lab Technician (Section 25)

### 21.1 Lab Tech Responsibilities
- Test result uploads
- **No access** to patient contact information
- **25(a)** Test charges automatically added to billing

---

## Section 22: Pharmacy Staff (Section 26)
- Detailed staff information management

---

## Section 23: Financial Management

### 23.1 Salary Management (Section 27)
- Comprehensive salary management system

### 23.2 Salary Access Rights (Section 28)
- Department staff can view salary information based on permissions

---

## Section 24: Attendance System (Section 29)
- Admin-controlled attendance system activation

---

## Section 25: Receptionist Management (Section 30)
- Profile similar to nurse with appropriate access rights

---

## Section 26: Patient Visit Management (Section 31)
- Visit reason documentation
- Attendant detail recording
- **Contact information cannot be edited after initial entry**

---

## Section 27: Data Security (Section 33)
- **Record deletion warning system**
- Data remains in database even after "deletion" for audit purposes

---

## Section 28: Communication System (Section 34)

### 28.1 Email/SMS Features
- **Appointment reminders**
- **Bill notifications**  
- **Emergency alerts**
- **Customizable templates**

### 28.2 API Integration
- **API slots for Twilio/Firebase** (optional integration)

---

## Section 29: Insurance Management (Section 35)
- **Insurance claim system**
- Admin approval required for claims
- Other roles can view claim status

---

## Section 30: Ambulance Management (Section 36)
- Complete ambulance fleet management system

---

## Section 31: Feedback System (Section 37)
- Patient and staff feedback management

---

## Section 32: Extended Services (Section 38)
**Admin-controlled service activation**:
- Doctor home visits
- Video consultations  
- Home lab testing services

---

## Section 33: Staff Scheduling (Section 39)
- **Shift management system**

---

## Section 34: Multi-Location Support (Section 40)
- **Multi-hospital/clinic system**
- Admin-controlled activation

---

## Section 35: Audit & Compliance (Section 41)
**Final audit system**:
- **Activity logs**
- **Action trails**
- Complete audit trail for compliance

---

## Implementation Notes

### Priority Levels
1. **Core System**: Authentication, basic CRUD operations
2. **Essential Features**: Patient management, doctor assignment, billing
3. **Advanced Features**: Communication system, multi-location support
4. **Premium Features**: Telemedicine, ambulance management, advanced analytics

### Security Requirements
- All passwords encrypted
- Role-based access control
- Data retention for audit compliance
- Secure patient information handling

### Performance Requirements  
- 100% responsive design
- Fast loading times
- Scalable architecture for multiple hospitals

---

**Document Created**: Current Date  
**Last Updated**: Current Date  
**Version**: 1.0