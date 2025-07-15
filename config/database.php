<?php
// Database Configuration
class Database {
    private $host = 'localhost';
    private $db_name = 'hospital_crm';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                  $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Security Functions
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateUniqueId($prefix = '') {
    return $prefix . date('Ymd') . rand(1000, 9999);
}

// Session Management
function startSecureSession() {
    if (session_status() == PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
        session_start();
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['role']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function requireRole($allowedRoles) {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
    
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        header("Location: dashboard.php?error=access_denied");
        exit();
    }
}

// Get Site Settings
function getSiteSettings() {
    $database = new Database();
    $db = $database->getConnection();
    
    try {
        $query = "SELECT * FROM site_settings WHERE id = 1";
        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [
            'site_name' => 'Hospital CRM',
            'primary_color' => '#007bff',
            'secondary_color' => '#6c757d',
            'accent_color' => '#28a745',
            'theme_mode' => 'light',
            'language' => 'en',
            'currency' => 'INR'
        ];
    }
}

// Logging Function
function logActivity($user_id, $action, $table_name = null, $record_id = null) {
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "INSERT INTO activity_logs (user_id, action, table_name, record_id, ip_address, user_agent) 
                  VALUES (:user_id, :action, :table_name, :record_id, :ip_address, :user_agent)";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':table_name', $table_name);
        $stmt->bindParam(':record_id', $record_id);
        $stmt->bindParam(':ip_address', $_SERVER['REMOTE_ADDR']);
        $stmt->bindParam(':user_agent', $_SERVER['HTTP_USER_AGENT']);
        
        $stmt->execute();
    } catch(PDOException $e) {
        // Silent fail for logging
    }
}
?>