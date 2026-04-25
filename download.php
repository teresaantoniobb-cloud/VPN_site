<?php
require_once 'includes/functions.php';
$id = (int)($_GET['id'] ?? 0);
if (!$id) { header('Location: index.php'); exit; }
$file = getFileById($id);
if (!$file) { http_response_code(404); die('Ficheiro não encontrado.'); }
$path = UPLOAD_DIR . $file['filename'];
if (!file_exists($path)) { http_response_code(404); die('Ficheiro não encontrado no servidor.'); }
logDownload($id);
$mime = mime_content_type($path) ?: 'application/octet-stream';
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
header('Content-Length: ' . filesize($path));
header('Cache-Control: no-cache');
readfile($path);
exit;
