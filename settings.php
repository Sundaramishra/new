<?php
require_once 'config/database.php';
startSecureSession();
requireRole(['admin']);

$database = new Database();
$db = $database->getConnection();
$siteSettings = getSiteSettings();

$success = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $siteName = sanitize($_POST['site_name']);
        $primaryColor = sanitize($_POST['primary_color']);
        $secondaryColor = sanitize($_POST['secondary_color']);
        $accentColor = sanitize($_POST['accent_color']);
        $themeMode = sanitize($_POST['theme_mode']);
        $language = sanitize($_POST['language']);
        $currency = sanitize($_POST['currency']);
        
        // Email settings
        $emailSmtpHost = sanitize($_POST['email_smtp_host']);
        $emailSmtpPort = intval($_POST['email_smtp_port']);
        $emailSmtpUser = sanitize($_POST['email_smtp_user']);
        $emailSmtpPass = $_POST['email_smtp_pass']; // Don't sanitize password
        
        // SMS settings
        $smsApiKey = sanitize($_POST['sms_api_key']);
        $smsApiUrl = sanitize($_POST['sms_api_url']);
        
        // Module settings
        $enableSalaryModule = isset($_POST['enable_salary_module']) ? 1 : 0;
        $enableSmsModule = isset($_POST['enable_sms_module']) ? 1 : 0;
        $enableEmailModule = isset($_POST['enable_email_module']) ? 1 : 0;
        
        // Handle logo upload
        $logoPath = $siteSettings['site_logo'];
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] == 0) {
            $uploadDir = 'assets/img/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION);
            $logoPath = $uploadDir . 'logo.' . $fileExtension;
            
            if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $logoPath)) {
                // Logo uploaded successfully
            } else {
                $error = 'Failed to upload logo';
            }
        }
        
        // Handle favicon upload
        $iconPath = $siteSettings['site_icon'];
        if (isset($_FILES['site_icon']) && $_FILES['site_icon']['error'] == 0) {
            $uploadDir = 'assets/img/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = pathinfo($_FILES['site_icon']['name'], PATHINFO_EXTENSION);
            $iconPath = $uploadDir . 'favicon.' . $fileExtension;
            
            if (move_uploaded_file($_FILES['site_icon']['tmp_name'], $iconPath)) {
                // Icon uploaded successfully
            } else {
                $error = 'Failed to upload favicon';
            }
        }
        
        if (empty($error)) {
            $query = "UPDATE site_settings SET 
                        site_name = :site_name,
                        site_logo = :site_logo,
                        site_icon = :site_icon,
                        primary_color = :primary_color,
                        secondary_color = :secondary_color,
                        accent_color = :accent_color,
                        theme_mode = :theme_mode,
                        language = :language,
                        currency = :currency,
                        email_smtp_host = :email_smtp_host,
                        email_smtp_port = :email_smtp_port,
                        email_smtp_user = :email_smtp_user,
                        email_smtp_pass = :email_smtp_pass,
                        sms_api_key = :sms_api_key,
                        sms_api_url = :sms_api_url,
                        enable_salary_module = :enable_salary_module,
                        enable_sms_module = :enable_sms_module,
                        enable_email_module = :enable_email_module
                      WHERE id = 1";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':site_name', $siteName);
            $stmt->bindParam(':site_logo', $logoPath);
            $stmt->bindParam(':site_icon', $iconPath);
            $stmt->bindParam(':primary_color', $primaryColor);
            $stmt->bindParam(':secondary_color', $secondaryColor);
            $stmt->bindParam(':accent_color', $accentColor);
            $stmt->bindParam(':theme_mode', $themeMode);
            $stmt->bindParam(':language', $language);
            $stmt->bindParam(':currency', $currency);
            $stmt->bindParam(':email_smtp_host', $emailSmtpHost);
            $stmt->bindParam(':email_smtp_port', $emailSmtpPort);
            $stmt->bindParam(':email_smtp_user', $emailSmtpUser);
            $stmt->bindParam(':email_smtp_pass', $emailSmtpPass);
            $stmt->bindParam(':sms_api_key', $smsApiKey);
            $stmt->bindParam(':sms_api_url', $smsApiUrl);
            $stmt->bindParam(':enable_salary_module', $enableSalaryModule);
            $stmt->bindParam(':enable_sms_module', $enableSmsModule);
            $stmt->bindParam(':enable_email_module', $enableEmailModule);
            
            $stmt->execute();
            
            logActivity($_SESSION['user_id'], 'Updated site settings');
            $success = 'Settings updated successfully!';
            
            // Refresh settings
            $siteSettings = getSiteSettings();
        }
        
    } catch(PDOException $e) {
        $error = 'Failed to update settings: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $siteSettings['language']; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Customization - <?php echo htmlspecialchars($siteSettings['site_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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
        }
        
        .settings-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .settings-card h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 20px;
            border-bottom: 2px solid #f1f3f4;
            padding-bottom: 10px;
        }
        
        .color-preview {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid #ddd;
            cursor: pointer;
            margin-left: 10px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .preview-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .module-toggle {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .module-toggle:hover {
            border-color: var(--primary-color);
        }
        
        .module-toggle.active {
            border-color: var(--primary-color);
            background: rgba(13, 110, 253, 0.05);
        }
        
        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        
        .file-input-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        
        .file-input-label {
            background: var(--primary-color);
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-input-label:hover {
            background: var(--accent-color);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <h4><?php echo htmlspecialchars($siteSettings['site_name']); ?></h4>
            <small class="text-light">Admin Panel</small>
        </div>
        
        <div class="sidebar-menu">
            <a href="dashboard.php">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="users.php">
                <i class="fas fa-users"></i>
                <span>User Management</span>
            </a>
            <a href="settings.php" class="active">
                <i class="fas fa-cog"></i>
                <span>Site Customization</span>
            </a>
            <a href="patients.php">
                <i class="fas fa-user-injured"></i>
                <span>Patients</span>
            </a>
            <a href="appointments.php">
                <i class="fas fa-calendar-check"></i>
                <span>Appointments</span>
            </a>
            <a href="billing.php">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Billing</span>
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-cog me-2"></i>Site Customization</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </nav>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <!-- General Settings -->
                    <div class="settings-card">
                        <h5><i class="fas fa-globe me-2"></i>General Settings</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Site Name</label>
                                <input type="text" class="form-control" name="site_name" 
                                       value="<?php echo htmlspecialchars($siteSettings['site_name']); ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Language</label>
                                <select class="form-control" name="language">
                                    <option value="en" <?php echo $siteSettings['language'] == 'en' ? 'selected' : ''; ?>>English</option>
                                    <option value="hi" <?php echo $siteSettings['language'] == 'hi' ? 'selected' : ''; ?>>Hindi</option>
                                    <option value="es" <?php echo $siteSettings['language'] == 'es' ? 'selected' : ''; ?>>Spanish</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Currency</label>
                                <select class="form-control" name="currency">
                                    <option value="INR" <?php echo $siteSettings['currency'] == 'INR' ? 'selected' : ''; ?>>INR (₹)</option>
                                    <option value="USD" <?php echo $siteSettings['currency'] == 'USD' ? 'selected' : ''; ?>>USD ($)</option>
                                    <option value="EUR" <?php echo $siteSettings['currency'] == 'EUR' ? 'selected' : ''; ?>>EUR (€)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Theme Mode</label>
                                <select class="form-control" name="theme_mode">
                                    <option value="light" <?php echo $siteSettings['theme_mode'] == 'light' ? 'selected' : ''; ?>>Light</option>
                                    <option value="dark" <?php echo $siteSettings['theme_mode'] == 'dark' ? 'selected' : ''; ?>>Dark</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Branding -->
                    <div class="settings-card">
                        <h5><i class="fas fa-palette me-2"></i>Branding & Theme</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Site Logo</label>
                                <div class="file-input-wrapper">
                                    <input type="file" id="site_logo" name="site_logo" accept="image/*">
                                    <label for="site_logo" class="file-input-label">
                                        <i class="fas fa-upload me-2"></i>Choose Logo
                                    </label>
                                </div>
                                <?php if (!empty($siteSettings['site_logo'])): ?>
                                    <div class="mt-2">
                                        <img src="<?php echo htmlspecialchars($siteSettings['site_logo']); ?>" 
                                             alt="Current Logo" style="max-height: 50px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Site Icon/Favicon</label>
                                <div class="file-input-wrapper">
                                    <input type="file" id="site_icon" name="site_icon" accept="image/*">
                                    <label for="site_icon" class="file-input-label">
                                        <i class="fas fa-upload me-2"></i>Choose Icon
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Primary Color</label>
                                <div class="d-flex align-items-center">
                                    <input type="color" class="form-control" name="primary_color" 
                                           value="<?php echo htmlspecialchars($siteSettings['primary_color']); ?>" 
                                           style="width: 60px; height: 40px;">
                                    <input type="text" class="form-control ms-2" 
                                           value="<?php echo htmlspecialchars($siteSettings['primary_color']); ?>" 
                                           readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Secondary Color</label>
                                <div class="d-flex align-items-center">
                                    <input type="color" class="form-control" name="secondary_color" 
                                           value="<?php echo htmlspecialchars($siteSettings['secondary_color']); ?>" 
                                           style="width: 60px; height: 40px;">
                                    <input type="text" class="form-control ms-2" 
                                           value="<?php echo htmlspecialchars($siteSettings['secondary_color']); ?>" 
                                           readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Accent Color</label>
                                <div class="d-flex align-items-center">
                                    <input type="color" class="form-control" name="accent_color" 
                                           value="<?php echo htmlspecialchars($siteSettings['accent_color']); ?>" 
                                           style="width: 60px; height: 40px;">
                                    <input type="text" class="form-control ms-2" 
                                           value="<?php echo htmlspecialchars($siteSettings['accent_color']); ?>" 
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Module Settings -->
                    <div class="settings-card">
                        <h5><i class="fas fa-puzzle-piece me-2"></i>Module Settings</h5>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="module-toggle <?php echo $siteSettings['enable_salary_module'] ? 'active' : ''; ?>">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="enable_salary_module" 
                                               <?php echo $siteSettings['enable_salary_module'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">
                                            <strong>Salary Management</strong><br>
                                            <small class="text-muted">Staff salary tracking & payroll</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="module-toggle <?php echo $siteSettings['enable_email_module'] ? 'active' : ''; ?>">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="enable_email_module" 
                                               <?php echo $siteSettings['enable_email_module'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">
                                            <strong>Email Notifications</strong><br>
                                            <small class="text-muted">Send email alerts & reminders</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="module-toggle <?php echo $siteSettings['enable_sms_module'] ? 'active' : ''; ?>">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="enable_sms_module" 
                                               <?php echo $siteSettings['enable_sms_module'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">
                                            <strong>SMS Notifications</strong><br>
                                            <small class="text-muted">Send SMS alerts & reminders</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Email Configuration -->
                    <div class="settings-card">
                        <h5><i class="fas fa-envelope me-2"></i>Email Configuration</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">SMTP Host</label>
                            <input type="text" class="form-control" name="email_smtp_host" 
                                   value="<?php echo htmlspecialchars($siteSettings['email_smtp_host']); ?>" 
                                   placeholder="smtp.gmail.com">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">SMTP Port</label>
                            <input type="number" class="form-control" name="email_smtp_port" 
                                   value="<?php echo htmlspecialchars($siteSettings['email_smtp_port']); ?>" 
                                   placeholder="587">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">SMTP Username</label>
                            <input type="email" class="form-control" name="email_smtp_user" 
                                   value="<?php echo htmlspecialchars($siteSettings['email_smtp_user']); ?>" 
                                   placeholder="your-email@gmail.com">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">SMTP Password</label>
                            <input type="password" class="form-control" name="email_smtp_pass" 
                                   value="<?php echo htmlspecialchars($siteSettings['email_smtp_pass']); ?>" 
                                   placeholder="App Password">
                        </div>
                    </div>
                    
                    <!-- SMS Configuration -->
                    <div class="settings-card">
                        <h5><i class="fas fa-sms me-2"></i>SMS Configuration</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">SMS API Key</label>
                            <input type="text" class="form-control" name="sms_api_key" 
                                   value="<?php echo htmlspecialchars($siteSettings['sms_api_key']); ?>" 
                                   placeholder="Your SMS API Key">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">SMS API URL</label>
                            <input type="url" class="form-control" name="sms_api_url" 
                                   value="<?php echo htmlspecialchars($siteSettings['sms_api_url']); ?>" 
                                   placeholder="https://api.textlocal.in/send/">
                        </div>
                        
                        <div class="alert alert-info">
                            <small><i class="fas fa-info-circle me-1"></i>
                            Configure your SMS gateway API details here. Popular options include Textlocal, Twilio, or MSG91.</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save me-2"></i>Save All Settings
                </button>
            </div>
        </form>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update color input text fields when color picker changes
        document.querySelectorAll('input[type="color"]').forEach(function(colorInput) {
            colorInput.addEventListener('change', function() {
                const textInput = this.parentNode.querySelector('input[type="text"]');
                if (textInput) {
                    textInput.value = this.value;
                }
            });
        });
        
        // Toggle module cards
        document.querySelectorAll('.module-toggle input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const moduleCard = this.closest('.module-toggle');
                if (this.checked) {
                    moduleCard.classList.add('active');
                } else {
                    moduleCard.classList.remove('active');
                }
            });
        });
        
        // Show file names when selected
        document.querySelectorAll('input[type="file"]').forEach(function(fileInput) {
            fileInput.addEventListener('change', function() {
                const label = this.parentNode.querySelector('label');
                if (this.files.length > 0) {
                    label.innerHTML = '<i class="fas fa-check me-2"></i>' + this.files[0].name;
                }
            });
        });
    </script>
</body>
</html>