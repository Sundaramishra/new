<?php
require_once 'config/database.php';
startSecureSession();
requireLogin();

$database = new Database();
$db = $database->getConnection();
$siteSettings = getSiteSettings();

// Get dashboard statistics based on user role
function getDashboardStats($db, $role, $user_id) {
    $stats = [];
    
    try {
        if ($role == 'admin') {
            // Admin sees everything
            $stats['total_patients'] = $db->query("SELECT COUNT(*) FROM patients")->fetchColumn();
            $stats['total_appointments'] = $db->query("SELECT COUNT(*) FROM appointments WHERE DATE(appointment_date) = CURDATE()")->fetchColumn();
            $stats['total_doctors'] = $db->query("SELECT COUNT(*) FROM users WHERE role = 'doctor' AND status = 'active'")->fetchColumn();
            $stats['total_staff'] = $db->query("SELECT COUNT(*) FROM users WHERE role IN ('nurse', 'lab_technician', 'receptionist') AND status = 'active'")->fetchColumn();
            $stats['pending_bills'] = $db->query("SELECT COUNT(*) FROM bills WHERE status IN ('pending', 'partial')")->fetchColumn();
            $stats['today_revenue'] = $db->query("SELECT COALESCE(SUM(paid_amount), 0) FROM bills WHERE DATE(created_at) = CURDATE()")->fetchColumn();
            
        } elseif ($role == 'doctor') {
            // Doctor sees their appointments and patients
            $stats['my_appointments_today'] = $db->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id = ? AND DATE(appointment_date) = CURDATE()");
            $stats['my_appointments_today']->execute([$user_id]);
            $stats['my_appointments_today'] = $stats['my_appointments_today']->fetchColumn();
            
            $stats['my_patients'] = $db->prepare("SELECT COUNT(DISTINCT patient_id) FROM appointments WHERE doctor_id = ?");
            $stats['my_patients']->execute([$user_id]);
            $stats['my_patients'] = $stats['my_patients']->fetchColumn();
            
        } elseif ($role == 'receptionist') {
            // Receptionist sees billing and appointments
            $stats['pending_bills'] = $db->query("SELECT COUNT(*) FROM bills WHERE status IN ('pending', 'partial')")->fetchColumn();
            $stats['today_appointments'] = $db->query("SELECT COUNT(*) FROM appointments WHERE DATE(appointment_date) = CURDATE()")->fetchColumn();
            $stats['today_revenue'] = $db->query("SELECT COALESCE(SUM(paid_amount), 0) FROM bills WHERE DATE(created_at) = CURDATE()")->fetchColumn();
            
        } else {
            // Other roles see basic stats
            $stats['total_patients'] = $db->query("SELECT COUNT(*) FROM patients")->fetchColumn();
            $stats['today_appointments'] = $db->query("SELECT COUNT(*) FROM appointments WHERE DATE(appointment_date) = CURDATE()")->fetchColumn();
        }
        
    } catch(PDOException $e) {
        // Return empty stats on error
    }
    
    return $stats;
}

// Get chart data for admin
function getChartData($db) {
    $chartData = [];
    
    try {
        // Monthly revenue data
        $monthlyRevenue = $db->query("
            SELECT MONTH(created_at) as month, COALESCE(SUM(paid_amount), 0) as revenue 
            FROM bills 
            WHERE YEAR(created_at) = YEAR(CURDATE()) 
            GROUP BY MONTH(created_at) 
            ORDER BY month
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        $chartData['monthly_revenue'] = array_fill(0, 12, 0);
        foreach ($monthlyRevenue as $data) {
            $chartData['monthly_revenue'][$data['month'] - 1] = (float)$data['revenue'];
        }
        
        // Patient demographics
        $demographics = $db->query("
            SELECT gender, COUNT(*) as count 
            FROM patients 
            GROUP BY gender
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        $chartData['demographics'] = $demographics;
        
        // Recent appointments
        $recentAppointments = $db->query("
            SELECT a.appointment_date, COUNT(*) as count 
            FROM appointments a 
            WHERE a.appointment_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
            GROUP BY a.appointment_date 
            ORDER BY a.appointment_date
        ")->fetchAll(PDO::FETCH_ASSOC);
        
        $chartData['recent_appointments'] = $recentAppointments;
        
    } catch(PDOException $e) {
        $chartData = [];
    }
    
    return $chartData;
}

$stats = getDashboardStats($db, $_SESSION['role'], $_SESSION['user_id']);
$chartData = ($_SESSION['role'] == 'admin') ? getChartData($db) : [];
?>
<!DOCTYPE html>
<html lang="<?php echo $siteSettings['language']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo htmlspecialchars($siteSettings['site_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: <?php echo $siteSettings['primary_color']; ?>;
            --secondary-color: <?php echo $siteSettings['secondary_color']; ?>;
            --accent-color: <?php echo $siteSettings['accent_color']; ?>;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            min-height: 100vh;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: 70px;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h4 {
            color: white;
            margin: 0;
            font-weight: 700;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
        }
        
        .sidebar-menu i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 70px;
        }
        
        .top-navbar {
            background: white;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: between;
            align-items: center;
        }
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .stats-card .icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .stats-card h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            color: #333;
        }
        
        .stats-card p {
            color: #666;
            margin: 5px 0 0 0;
        }
        
        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .chart-card h5 {
            color: #333;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .profile-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .profile-dropdown .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            color: #333;
            font-size: 1.2rem;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .sidebar.show {
                width: 250px;
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><?php echo htmlspecialchars($siteSettings['site_name']); ?></h4>
            <small class="text-light"><?php echo ucfirst($_SESSION['role']); ?> Panel</small>
        </div>
        
        <div class="sidebar-menu">
            <a href="dashboard.php" class="active">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="users.php">
                    <i class="fas fa-users"></i>
                    <span>User Management</span>
                </a>
                <a href="settings.php">
                    <i class="fas fa-cog"></i>
                    <span>Site Customization</span>
                </a>
            <?php endif; ?>
            
            <?php if (in_array($_SESSION['role'], ['admin', 'doctor', 'nurse'])): ?>
                <a href="patients.php">
                    <i class="fas fa-user-injured"></i>
                    <span>Patients</span>
                </a>
            <?php endif; ?>
            
            <?php if (in_array($_SESSION['role'], ['admin', 'doctor', 'receptionist'])): ?>
                <a href="appointments.php">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
            <?php endif; ?>
            
            <?php if (in_array($_SESSION['role'], ['admin', 'receptionist'])): ?>
                <a href="billing.php">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Billing</span>
                </a>
            <?php endif; ?>
            
            <?php if (in_array($_SESSION['role'], ['admin', 'lab_technician'])): ?>
                <a href="lab-reports.php">
                    <i class="fas fa-vial"></i>
                    <span>Lab Reports</span>
                </a>
            <?php endif; ?>
            
            <?php if ($_SESSION['role'] == 'admin' && $siteSettings['enable_salary_module']): ?>
                <a href="salary.php">
                    <i class="fas fa-money-check-alt"></i>
                    <span>Salary Management</span>
                </a>
            <?php endif; ?>
            
            <a href="profile.php">
                <i class="fas fa-user-circle"></i>
                <span>My Profile</span>
            </a>
            
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Top Navigation -->
        <nav class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <h5 class="mb-0 ms-3">Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h5>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-bell position-relative">
                        <span class="notification-badge">3</span>
                    </i>
                </div>
                
                <div class="profile-dropdown dropdown">
                    <button class="btn btn-link dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle"></i>
                        <?php echo htmlspecialchars($_SESSION['full_name']); ?>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Dashboard Content -->
        <div class="container-fluid">
            <!-- Statistics Cards -->
            <div class="row">
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="fas fa-user-injured"></i>
                            </div>
                            <h3><?php echo number_format($stats['total_patients'] ?? 0); ?></h3>
                            <p>Total Patients</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3><?php echo number_format($stats['total_appointments'] ?? 0); ?></h3>
                            <p>Today's Appointments</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <h3><?php echo number_format($stats['total_doctors'] ?? 0); ?></h3>
                            <p>Active Doctors</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="fas fa-rupee-sign"></i>
                            </div>
                            <h3><?php echo $siteSettings['currency']; ?> <?php echo number_format($stats['today_revenue'] ?? 0, 2); ?></h3>
                            <p>Today's Revenue</p>
                        </div>
                    </div>
                <?php elseif ($_SESSION['role'] == 'doctor'): ?>
                    <div class="col-lg-6 col-md-6">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <h3><?php echo number_format($stats['my_appointments_today'] ?? 0); ?></h3>
                            <p>My Appointments Today</p>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 col-md-6">
                        <div class="stats-card">
                            <div class="icon">
                                <i class="fas fa-user-injured"></i>
                            </div>
                            <h3><?php echo number_format($stats['my_patients'] ?? 0); ?></h3>
                            <p>My Total Patients</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Charts Section (Admin Only) -->
            <?php if ($_SESSION['role'] == 'admin' && !empty($chartData)): ?>
                <div class="row">
                    <div class="col-lg-8">
                        <div class="chart-card">
                            <h5><i class="fas fa-chart-line me-2"></i>Monthly Revenue Trend</h5>
                            <canvas id="revenueChart" height="300"></canvas>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="chart-card">
                            <h5><i class="fas fa-chart-pie me-2"></i>Patient Demographics</h5>
                            <canvas id="demographicsChart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="chart-card">
                        <h5><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                        <div class="row">
                            <?php if (in_array($_SESSION['role'], ['admin', 'receptionist'])): ?>
                                <div class="col-md-3 mb-3">
                                    <a href="appointments.php?action=new" class="btn btn-primary w-100">
                                        <i class="fas fa-plus me-2"></i>New Appointment
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="patients.php?action=new" class="btn btn-success w-100">
                                        <i class="fas fa-user-plus me-2"></i>Add Patient
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (in_array($_SESSION['role'], ['admin', 'doctor'])): ?>
                                <div class="col-md-3 mb-3">
                                    <a href="lab-reports.php?action=new" class="btn btn-info w-100">
                                        <i class="fas fa-vial me-2"></i>New Lab Report
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($_SESSION['role'] == 'admin'): ?>
                                <div class="col-md-3 mb-3">
                                    <a href="users.php?action=new" class="btn btn-warning w-100">
                                        <i class="fas fa-user-plus me-2"></i>Add User
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
        
        // Initialize charts for admin
        <?php if ($_SESSION['role'] == 'admin' && !empty($chartData)): ?>
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Revenue',
                        data: <?php echo json_encode($chartData['monthly_revenue']); ?>,
                        borderColor: '<?php echo $siteSettings['primary_color']; ?>',
                        backgroundColor: '<?php echo $siteSettings['primary_color']; ?>20',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Demographics Chart
            const demographicsCtx = document.getElementById('demographicsChart').getContext('2d');
            new Chart(demographicsCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode(array_column($chartData['demographics'], 'gender')); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_column($chartData['demographics'], 'count')); ?>,
                        backgroundColor: [
                            '<?php echo $siteSettings['primary_color']; ?>',
                            '<?php echo $siteSettings['accent_color']; ?>',
                            '<?php echo $siteSettings['secondary_color']; ?>'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>