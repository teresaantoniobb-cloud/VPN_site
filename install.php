<?php
require_once 'config/db.php';
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";charset=utf8mb4", DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `".DB_NAME."` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `".DB_NAME."`");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `apps` (
        `id`          INT AUTO_INCREMENT PRIMARY KEY,
        `slug`        VARCHAR(60) NOT NULL UNIQUE,
        `name`        VARCHAR(100) NOT NULL,
        `icon`        VARCHAR(10) DEFAULT '📱',
        `color`       VARCHAR(20) DEFAULT '#00f0ff',
        `description` TEXT,
        `sort_order`  INT DEFAULT 0
    ) ENGINE=InnoDB");

    $pdo->exec("CREATE TABLE IF NOT EXISTS `vpn_files` (
        `id`            INT AUTO_INCREMENT PRIMARY KEY,
        `app_id`        INT NOT NULL,
        `title`         VARCHAR(200) NOT NULL,
        `description`   TEXT,
        `filename`      VARCHAR(255) NOT NULL,
        `original_name` VARCHAR(255) NOT NULL,
        `file_size`     BIGINT DEFAULT 0,
        `password`      VARCHAR(255) DEFAULT '',
        `server`        VARCHAR(100) DEFAULT '',
        `downloads`     INT DEFAULT 0,
        `sort_order`    INT DEFAULT 0,
        `created_at`    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`app_id`) REFERENCES `apps`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB");

    $pdo->exec("INSERT IGNORE INTO `apps` (`slug`,`name`,`icon`,`color`,`description`,`sort_order`) VALUES
        ('http-injector','HTTP Injector','💉','#00f0ff','Configurações para HTTP Injector. Importa o ficheiro .ehi directamente na app.',1),
        ('bd-net','BD Net','🌐','#22c55e','Configurações para BD Net. Ficheiros prontos para importar na aplicação.',2),
        ('apna-tunnel','APNA Tunnel Lite','⚡','#f97316','Configurações para APNA Tunnel Lite. Rápido e fácil de configurar.',3),
        ('maya-tun','Maya Tun Pro','🌀','#a855f7','Configurações para Maya Tun Pro. Alta velocidade e estabilidade.',4)
    ");

    echo "<div style='font-family:sans-serif;max-width:500px;margin:60px auto;padding:32px;background:#0b1424;border:1px solid #1e3a5f;border-radius:16px;color:#e8edf5'>
    <h2 style='color:#00f0ff'>✅ Base de dados criada!</h2>
    <p style='color:#5a6a80;margin:12px 0'>4 aplicações VPN pré-configuradas.</p>
    <p style='color:#ef4444;font-size:.85rem'>⚠ Elimina este ficheiro (install.php) agora!</p>
    <a href='index.php' style='display:inline-block;margin-top:16px;padding:10px 20px;background:#00f0ff;color:#000;border-radius:8px;text-decoration:none;font-weight:700'>Ir ao site →</a>
    </div>";
} catch (PDOException $e) {
    echo "<div style='font-family:sans-serif;color:#ef4444;padding:32px'>❌ Erro: ".htmlspecialchars($e->getMessage())."</div>";
}
