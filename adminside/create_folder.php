<?php
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../connection.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid method']);
    exit;
}
$raw = isset($_POST['folderName']) ? trim($_POST['folderName']) : '';
if ($raw === '') {
    echo json_encode(['success' => false, 'error' => 'Folder name required']);
    exit;
}
$san = preg_replace('/[^A-Za-z0-9 _-]/', '', $raw);
$san = trim($san);
if ($san === '') {
    echo json_encode(['success' => false, 'error' => 'Invalid folder name']);
    exit;
}
if (strlen($san) > 100) {
    $san = substr($san, 0, 100);
}
if (strpos($san, '/') !== false || strpos($san, "\\") !== false) {
    echo json_encode(['success' => false, 'error' => 'Invalid folder name']);
    exit;
}
$base_dir = realpath(__DIR__ . '/../assets/img/Z Property Images');
if ($base_dir === false) {
    $base_dir = __DIR__ . '/../assets/img/Z Property Images';
}
$new_dir = $base_dir . DIRECTORY_SEPARATOR . $san;
$real_parent = realpath(dirname($new_dir));
if ($real_parent === false) $real_parent = dirname($new_dir);
if (strpos($real_parent, $base_dir) !== 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid path']);
    exit;
}
if (!is_dir($new_dir)) {
    if (!mkdir($new_dir, 0755, true)) {
        echo json_encode(['success' => false, 'error' => 'Failed to create folder on server']);
        exit;
    }
}
echo json_encode(['success' => true, 'folder' => $san]);
exit;
