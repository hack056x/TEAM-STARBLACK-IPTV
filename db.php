<?php
// db.php - Conexión a SQLite (como en tu proyecto funcional)
class Database {
    private $db;
    
    public function __construct() {
        $db_file = __DIR__ . '/api/.db.db';
        $db_dir = dirname($db_file);
        
        // Crear carpeta api si no existe
        if (!is_dir($db_dir)) {
            mkdir($db_dir, 0777, true);
        }
        
        $this->db = new SQLite3($db_file);
        $this->db->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            exp_date TEXT NOT NULL,
            role TEXT DEFAULT 'user',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Crear usuario admin por defecto si no hay usuarios
        $check = $this->db->querySingle("SELECT COUNT(*) as count FROM users");
        if ($check == 0) {
            $hashed = password_hash('1234', PASSWORD_DEFAULT);
            $exp_date = date('Y-m-d', strtotime('+1 year'));
            $this->db->exec("INSERT INTO users (username, password, exp_date, role) 
                            VALUES ('admin', '$hashed', '$exp_date', 'admin')");
        }
    }
    
    public function getConnection() {
        return $this->db;
    }
    
    public function close() {
        $this->db->close();
    }
}

// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Funciones de utilidad
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function checkExpiration($exp_date) {
    $today = date('Y-m-d');
    return $today <= $exp_date;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: index.php');
        exit();
    }
    if (isset($_SESSION['exp_date'])) {
        if (!checkExpiration($_SESSION['exp_date'])) {
            session_destroy();
            header('Location: index.php?error=expired');
            exit();
        }
    }
}

function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: checker.php');
        exit();
    }
}

// Obtener IP del usuario
function getUserIP() {
    $ip = 'undefined';
    if (isset($_SERVER)) {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        elseif (isset($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        $ip = getenv('REMOTE_ADDR');
        if (getenv('HTTP_X_FORWARDED_FOR')) $ip = getenv('HTTP_X_FORWARDED_FOR');
        elseif (getenv('HTTP_CLIENT_IP')) $ip = getenv('HTTP_CLIENT_IP');
    }
    return htmlspecialchars($ip, ENT_QUOTES, 'UTF-8');
}
?>