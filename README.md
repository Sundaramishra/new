# Hospital/Clinic CRM System

A comprehensive Hospital and Clinic Management System built with PHP, MySQL, HTML, CSS, and JavaScript. This system provides complete management solutions for hospitals, clinics, and medical practices with multi-role access control and WordPress-like customization features.

## ✨ Features

### 🔐 Multi-Role Authentication System
- **Admin**: Full system access and management
- **Doctor**: Patient records, appointments, lab reports
- **Nurse**: Patient care and assistance
- **Lab Technician**: Lab reports and test management
- **Receptionist**: Billing, appointments, and front desk operations
- **Staff**: Basic access with customizable permissions

### 🎨 WordPress-like Customization
- **Theme Customization**: Change colors, logos, and site branding without coding
- **Module Management**: Enable/disable features like salary management, SMS, email
- **Multi-language Support**: English, Hindi, Spanish (expandable)
- **Responsive Design**: Works on desktop, tablet, and mobile devices

### 👥 Patient Management
- Complete patient profiles with medical history
- Vital signs tracking (BP, heart rate, oxygen levels, height, weight)
- Patient photo upload and management
- Medical allergies and condition tracking
- Emergency contact information

### 📅 Appointment System
- Easy appointment scheduling and management
- Doctor availability tracking
- Appointment status management (scheduled, confirmed, completed, cancelled)
- Email and SMS notifications for appointments
- Calendar view with appointment overview

### 💰 Billing & Receipt Management
- Professional invoice generation
- Multiple payment method support (Cash, Card, UPI, Cheque, Online)
- Payment tracking and receipt generation
- Due amount calculations
- Bill item management with quantities and prices

### 🧪 Lab Reports Management
- Digital lab report storage and management
- Test result tracking with normal ranges
- Blood reports and radiology integration
- Lab technician assignment and review system
- Patient report access and history

### 💵 Salary Management (Optional Module)
- Staff salary calculation and tracking
- Allowances and deductions management
- Monthly payroll generation
- Salary slip generation and distribution
- Payment status tracking

### 📧 Communication System
- **Email Integration**: SMTP configuration for automated emails
- **SMS Integration**: API-based SMS notifications
- Appointment reminders and confirmations
- Bill payment notifications
- Custom email and SMS templates

### 📊 Professional Dashboard
- Role-based dashboard with relevant statistics
- Interactive charts and graphs using Chart.js
- Revenue tracking and analysis
- Patient demographics visualization
- Real-time system monitoring

### 🔧 Advanced Features
- **Security**: Password hashing, session management, SQL injection protection
- **Activity Logging**: Complete audit trail of user actions
- **Data Export**: Export reports and data in various formats
- **Backup System**: Database backup and restore functionality
- **Professional UI**: Clean, modern interface similar to Cliniva

## 🚀 Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for PHPMailer dependencies)

### Step 1: Download and Extract
1. Download the ZIP file
2. Extract to your web server directory (htdocs/www/public_html)

### Step 2: Database Setup
1. Create a new MySQL database named `hospital_crm`
2. Import the database structure:
   ```sql
   mysql -u username -p hospital_crm < database.sql
   ```
3. Import demo data (optional):
   ```sql
   mysql -u username -p hospital_crm < demo_data.sql
   ```

### Step 3: Configuration
1. Edit `config/database.php` and update your database credentials:
   ```php
   private $host = 'localhost';
   private $db_name = 'hospital_crm';
   private $username = 'your_username';
   private $password = 'your_password';
   ```

### Step 4: File Permissions
Set proper permissions for upload directories:
```bash
chmod 755 assets/img/
chmod 755 uploads/
```

### Step 5: PHPMailer Setup (Optional)
For email functionality, install PHPMailer:
```bash
composer require phpmailer/phpmailer
```

## 🎯 Default Login Credentials

### Administrator
- **Username**: `admin`
- **Password**: `admin123`

### Demo Accounts (if demo data imported)
- **Doctor**: `doctor1` / `demo123`
- **Nurse**: `nurse1` / `demo123`
- **Receptionist**: `receptionist1` / `demo123`
- **Lab Technician**: `lab_tech1` / `demo123`

## 🛠️ Configuration

### Site Customization
1. Login as Admin
2. Go to **Settings > Site Customization**
3. Customize:
   - Site name and branding
   - Colors and theme
   - Logo and favicon
   - Language and currency
   - Enable/disable modules

### Email Configuration
1. Go to **Settings > Email Configuration**
2. Enter SMTP details:
   - SMTP Host (e.g., smtp.gmail.com)
   - SMTP Port (587 for TLS)
   - Username and App Password
3. Test email functionality

### SMS Configuration
1. Go to **Settings > SMS Configuration**
2. Enter SMS provider API details
3. Popular providers: Textlocal, Twilio, MSG91
4. Test SMS functionality

## 📱 Module Management

### Enabling/Disabling Modules
- **Salary Management**: Staff payroll and salary tracking
- **Email Notifications**: Automated email alerts
- **SMS Notifications**: Text message alerts

### Role-Based Access
Each role has specific permissions:
- **Admin**: All modules and settings
- **Doctor**: Patients, appointments, lab reports
- **Receptionist**: Billing, appointments, patient registration
- **Nurse**: Patient care, vital signs, assistance
- **Lab Technician**: Lab reports, test results
- **Staff**: Basic access (customizable)

## 🔒 Security Features

- **Password Hashing**: BCrypt encryption for all passwords
- **Session Security**: Secure session management with timeouts
- **SQL Injection Protection**: Prepared statements throughout
- **XSS Protection**: Input sanitization and output encoding
- **CSRF Protection**: Form token validation
- **Activity Logging**: Complete audit trail

## 📈 Dashboard Features

### Admin Dashboard
- Total patients, appointments, doctors, staff
- Revenue tracking and analytics
- Monthly revenue charts
- Patient demographics
- System activity monitoring

### Role-Specific Dashboards
- **Doctor**: Personal appointments and patients
- **Receptionist**: Billing and appointment overview
- **Lab Technician**: Pending and completed reports
- **Nurse**: Patient care assignments

## 🎨 Customization Options

### Theme Colors
- Primary color for main interface elements
- Secondary color for supporting elements
- Accent color for highlights and calls-to-action

### Branding
- Upload custom logo and favicon
- Set site name and description
- Choose between light and dark themes

### Languages
- English (default)
- Hindi
- Spanish
- Easy to add more languages

## 📞 Support

### Common Issues
1. **Database Connection Error**: Check database credentials in config/database.php
2. **Email Not Working**: Verify SMTP settings and firewall rules
3. **File Upload Issues**: Check folder permissions (755 for directories)
4. **Login Problems**: Ensure database is properly imported

### System Requirements
- **PHP**: 7.4+ with PDO extension
- **MySQL**: 5.7+ or MariaDB 10.2+
- **Disk Space**: Minimum 100MB
- **Memory**: 256MB PHP memory limit recommended

## 🚀 Deployment

### Production Deployment
1. Use HTTPS for secure communication
2. Set secure session cookies
3. Regular database backups
4. Monitor system logs
5. Keep software updated

### Performance Optimization
- Enable PHP OPcache
- Use database indexing
- Optimize images and assets
- Consider CDN for static files

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Contact

For support and queries, please contact the development team.

---

**Note**: This system is designed for medical practice management. Ensure compliance with local healthcare regulations and data protection laws (HIPAA, GDPR, etc.) when deploying in production environments.