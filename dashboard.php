<?php
session_start();
require_once 'config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$db = new Database();
$user_role = $_SESSION['role'];
$user_name = $_SESSION['username'];

// Get dashboard statistics based on role
$stats = [];
try {
    if ($user_role === 'admin') {
        // Admin dashboard stats
        $stats['total_patients'] = $db->query("SELECT COUNT(*) as count FROM patients")->fetch()['count'];
        $stats['total_doctors'] = $db->query("SELECT COUNT(*) as count FROM doctors")->fetch()['count'];
        $stats['total_appointments'] = $db->query("SELECT COUNT(*) as count FROM appointments WHERE appointment_date = CURDATE()")->fetch()['count'];
        $stats['total_revenue'] = $db->query("SELECT SUM(total_amount) as revenue FROM bills WHERE DATE(created_at) = CURDATE()")->fetch()['revenue'] ?? 0;
    } elseif ($user_role === 'doctor') {
        // Doctor dashboard stats
        $doctor_id = $db->query("SELECT id FROM doctors WHERE user_id = ?", [$_SESSION['user_id']])->fetch()['id'];
        $stats['my_patients'] = $db->query("SELECT COUNT(DISTINCT patient_id) as count FROM appointments WHERE doctor_id = ?", [$doctor_id])->fetch()['count'];
        $stats['todays_appointments'] = $db->query("SELECT COUNT(*) as count FROM appointments WHERE doctor_id = ? AND appointment_date = CURDATE()", [$doctor_id])->fetch()['count'];
        $stats['pending_appointments'] = $db->query("SELECT COUNT(*) as count FROM appointments WHERE doctor_id = ? AND status = 'scheduled'", [$doctor_id])->fetch()['count'];
    } elseif ($user_role === 'patient') {
        // Patient dashboard stats
        $patient_id = $db->query("SELECT id FROM patients WHERE user_id = ?", [$_SESSION['user_id']])->fetch()['id'];
        $stats['my_appointments'] = $db->query("SELECT COUNT(*) as count FROM appointments WHERE patient_id = ?", [$patient_id])->fetch()['count'];
        $stats['pending_bills'] = $db->query("SELECT COUNT(*) as count FROM bills WHERE patient_id = ? AND payment_status != 'paid'", [$patient_id])->fetch()['count'];
    }
} catch (Exception $e) {
    $stats = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital CRM - Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: #004685;
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #0066cc;
            margin-bottom: 20px;
        }
        
        .sidebar-header h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            font-size: 12px;
            opacity: 0.8;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: #0066cc;
        }
        
        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
        }
        
        .dashboard-header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .dashboard-header h1 {
            color: #333;
            font-size: 24px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            font-size: 32px;
            color: #004685;
            margin-bottom: 10px;
        }
        
        .stat-card p {
            color: #666;
            font-size: 14px;
        }
        
        .quick-actions {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .quick-actions h2 {
            color: #333;
            margin-bottom: 20px;
        }
        
        .action-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .action-btn {
            background: #004685;
            color: white;
            padding: 15px;
            text-decoration: none;
            border-radius: 8px;
            text-align: center;
            transition: background 0.3s;
        }
        
        .action-btn:hover {
            background: #003366;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .dashboard-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Hospital CRM</h2>
                <p><?php echo htmlspecialchars($_SESSION['role_display']); ?></p>
            </div>
            
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active">🏠 Dashboard</a></li>
                
                <?php if ($user_role === 'admin'): ?>
                    <li><a href="patients.php">👥 Patients</a></li>
                    <li><a href="doctors.php">👨‍⚕️ Doctors</a></li>
                    <li><a href="staff.php">👷 Staff</a></li>
                    <li><a href="appointments.php">📅 Appointments</a></li>
                    <li><a href="billing.php">💰 Billing</a></li>
                    <li><a href="reports.php">📊 Reports</a></li>
                    <li><a href="settings.php">⚙️ Settings</a></li>
                <?php elseif ($user_role === 'doctor'): ?>
                    <li><a href="my-patients.php">👥 My Patients</a></li>
                    <li><a href="appointments.php">📅 Appointments</a></li>
                    <li><a href="prescriptions.php">💊 Prescriptions</a></li>
                    <li><a href="schedule.php">🕐 Schedule</a></li>
                <?php elseif ($user_role === 'patient'): ?>
                    <li><a href="my-appointments.php">📅 My Appointments</a></li>
                    <li><a href="my-bills.php">💰 My Bills</a></li>
                    <li><a href="medical-records.php">📋 Medical Records</a></li>
                    <li><a href="book-appointment.php">➕ Book Appointment</a></li>
                <?php elseif ($user_role === 'nurse'): ?>
                    <li><a href="assigned-patients.php">👥 Assigned Patients</a></li>
                    <li><a href="vitals.php">🩺 Patient Vitals</a></li>
                    <li><a href="medications.php">💊 Medications</a></li>
                <?php elseif ($user_role === 'receptionist'): ?>
                    <li><a href="patients.php">👥 Patients</a></li>
                    <li><a href="appointments.php">📅 Appointments</a></li>
                    <li><a href="billing.php">💰 Billing</a></li>
                <?php endif; ?>
                
                <li><a href="profile.php">👤 Profile</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="dashboard-header">
                <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['role_display']); ?></span>
                    <a href="logout.php" class="logout-btn">Logout</a>
                </div>
            </div>
            
            <div class="stats-grid">
                <?php if ($user_role === 'admin'): ?>
                    <div class="stat-card">
                        <h3><?php echo number_format($stats['total_patients'] ?? 0); ?></h3>
                        <p>Total Patients</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo number_format($stats['total_doctors'] ?? 0); ?></h3>
                        <p>Total Doctors</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo number_format($stats['total_appointments'] ?? 0); ?></h3>
                        <p>Today's Appointments</p>
                    </div>
                    <div class="stat-card">
                        <h3>₹<?php echo number_format($stats['total_revenue'] ?? 0, 2); ?></h3>
                        <p>Today's Revenue</p>
                    </div>
                <?php elseif ($user_role === 'doctor'): ?>
                    <div class="stat-card">
                        <h3><?php echo number_format($stats['my_patients'] ?? 0); ?></h3>
                        <p>My Patients</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo number_format($stats['todays_appointments'] ?? 0); ?></h3>
                        <p>Today's Appointments</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo number_format($stats['pending_appointments'] ?? 0); ?></h3>
                        <p>Pending Appointments</p>
                    </div>
                <?php elseif ($user_role === 'patient'): ?>
                    <div class="stat-card">
                        <h3><?php echo number_format($stats['my_appointments'] ?? 0); ?></h3>
                        <p>My Appointments</p>
                    </div>
                    <div class="stat-card">
                        <h3><?php echo number_format($stats['pending_bills'] ?? 0); ?></h3>
                        <p>Pending Bills</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <?php if ($user_role === 'admin'): ?>
                        <a href="add-patient.php" class="action-btn">Add New Patient</a>
                        <a href="add-doctor.php" class="action-btn">Add New Doctor</a>
                        <a href="create-appointment.php" class="action-btn">Schedule Appointment</a>
                        <a href="reports.php" class="action-btn">View Reports</a>
                    <?php elseif ($user_role === 'doctor'): ?>
                        <a href="todays-appointments.php" class="action-btn">Today's Schedule</a>
                        <a href="create-prescription.php" class="action-btn">Create Prescription</a>
                        <a href="patient-search.php" class="action-btn">Search Patients</a>
                    <?php elseif ($user_role === 'patient'): ?>
                        <a href="book-appointment.php" class="action-btn">Book Appointment</a>
                        <a href="my-bills.php" class="action-btn">View Bills</a>
                        <a href="medical-records.php" class="action-btn">Medical Records</a>
                    <?php elseif ($user_role === 'receptionist'): ?>
                        <a href="add-patient.php" class="action-btn">Register Patient</a>
                        <a href="create-appointment.php" class="action-btn">Schedule Appointment</a>
                        <a href="billing.php" class="action-btn">Billing</a>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>