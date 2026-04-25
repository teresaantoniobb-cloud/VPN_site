<?php
require_once __DIR__ . '/../config/db.php';

function getApps(): array {
    return getDB()->query("SELECT * FROM apps ORDER BY sort_order, name")->fetchAll();
}

function getAppBySlug(string $slug): ?array {
    $s = getDB()->prepare("SELECT * FROM apps WHERE slug=?");
    $s->execute([$slug]);
    return $s->fetch() ?: null;
}

function getFilesByApp(int $appId): array {
    $s = getDB()->prepare("SELECT * FROM vpn_files WHERE app_id=? ORDER BY sort_order, created_at DESC LIMIT 5");
    $s->execute([$appId]);
    return $s->fetchAll();
}

function countFilesByApp(int $appId): int {
    $s = getDB()->prepare("SELECT COUNT(*) FROM vpn_files WHERE app_id=?");
    $s->execute([$appId]);
    return (int)$s->fetchColumn();
}

function getFileById(int $id): ?array {
    $s = getDB()->prepare("SELECT f.*,a.name app_name,a.slug app_slug,a.icon app_icon,a.color app_color FROM vpn_files f JOIN apps a ON a.id=f.app_id WHERE f.id=?");
    $s->execute([$id]);
    return $s->fetch() ?: null;
}

function logDownload(int $id): void {
    getDB()->prepare("UPDATE vpn_files SET downloads=downloads+1 WHERE id=?")->execute([$id]);
}

function formatBytes(int $b): string {
    if ($b>=1048576) return round($b/1048576,1).' MB';
    if ($b>=1024)    return round($b/1024,0).' KB';
    return $b.' B';
}

function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function isAdmin(): bool {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

function requireAdmin(): void {
    if (!isAdmin()) { header('Location: '.SITE_URL.'/admin/login.php'); exit; }
}
