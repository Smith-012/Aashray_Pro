<?php
include('../connection.php');
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['logged_in_user']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
include '../connection.php';
function jsend($ok, $msg)
{
    echo json_encode(['success' => $ok, 'message' => $msg]);
    exit;
}
$admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 0;
$admin_username = isset($_POST['admin_username']) ? trim($_POST['admin_username']) : '';
if ($admin_id <= 0) jsend(false, 'Invalid admin id');
$logged = isset($_SESSION['logged_in_user']) ? $_SESSION['logged_in_user'] : '';
if ($admin_username !== '' && $admin_username === $logged) jsend(false, 'Cannot delete the currently logged-in admin');
$cntRes = $conn->query("SELECT COUNT(*) AS cnt FROM admins");
if ($cntRes) {
    $r = $cntRes->fetch_assoc();
    if ($r && intval($r['cnt']) <= 1) jsend(false, 'Cannot delete the last admin account');
}
$stmt = $conn->prepare("DELETE FROM admins WHERE admin_id = ?");
if (!$stmt) jsend(false, 'Prepare failed: ' . $conn->error);
$stmt->bind_param('i', $admin_id);
if ($stmt->execute()) {
    $stmt->close();
    jsend(true, 'Admin deleted successfully');
} else {
    $stmt->close();
    jsend(false, 'Database error: ' . $conn->error);
}
