<?php
include('../connection.php');
if (session_status() === PHP_SESSION_NONE) session_start();
ob_start();
header('Content-Type: application/json; charset=utf-8');
if (!isset($_SESSION['logged_in_user']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
include '../connection.php';
function jsend($ok, $msg)
{
    while (ob_get_level()) ob_end_clean();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => $ok, 'message' => $msg]);
    exit;
}
$admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 0;
$password = isset($_POST['password']) ? $_POST['password'] : '';
if ($admin_id <= 0 || $password === '') jsend(false, 'Invalid request');
if (strlen($password) < 4) jsend(false, 'Password must be at least 4 characters');
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE admins SET admin_password = ? WHERE admin_id = ?");
if (!$stmt) jsend(false, 'Prepare failed: ' . $conn->error);
$stmt->bind_param('si', $hashed, $admin_id);
if ($stmt->execute()) {
    $stmt->close();
    jsend(true, 'Password updated successfully');
} else {
    $stmt->close();
    jsend(false, 'Database error: ' . $conn->error);
}
