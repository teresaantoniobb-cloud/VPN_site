<?php
// admin/delete.php
session_start();
require_once '../includes/functions.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0);
if ($id) {
    $db = getDB();
    $r  = $db->prepare("SELECT filename FROM vpn_files WHERE id=?"); $r->execute([$id]); $r=$r->fetch();
    if ($r) { @unlink(UPLOAD_DIR.$r['filename']); $db->prepare("DELETE FROM vpn_files WHERE id=?")->execute([$id]); }
}
header('Location: files.php'); exit;
