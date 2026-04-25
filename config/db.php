<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'vpnfree_db');
define('DB_USER', 'root');        // Altere
define('DB_PASS', '');            // Altere
define('SITE_NAME', 'VPN Free AO');
define('SITE_URL', 'http://localhost/vpnfree');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 50 * 1024 * 1024);

define('ADMIN_USER', 'admin');
define('ADMIN_PASS', password_hash('admin123', PASSWORD_DEFAULT)); // Altere

function getDB(): PDO {
    static $pdo = null;
    if (!$pdo) {
        $pdo = new PDO(
            "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
            DB_USER, DB_PASS,
            [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
             PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}
