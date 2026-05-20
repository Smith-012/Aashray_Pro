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
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
if ($username === '' || $password === '') {
    jsend(false, 'Username and password are required');
}
if (strlen($username) > 20) jsend(false, 'Username is too long');
if (strlen($password) < 4) jsend(false, 'Password must be at least 4 characters');
if (!preg_match('/^[A-Za-z0-9_.-]{2,20}$/', $username)) {
    jsend(false, 'Username can contain letters, numbers, dot, underscore and hyphen only (2-20 chars)');
}
$check = $conn->prepare("SELECT admin_id FROM admins WHERE admin_username = ? LIMIT 1");
$check->bind_param('s', $username);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    $check->close();
    jsend(false, 'Username already exists');
}
$check->close();
$hashed = password_hash($password, PASSWORD_DEFAULT);
$insert = $conn->prepare("INSERT INTO admins (admin_username, admin_password) VALUES (?, ?)");
if (!$insert) jsend(false, 'Prepare failed: ' . $conn->error);
$insert->bind_param('ss', $username, $hashed);
if ($insert->execute()) {
    $insert->close();
    jsend(true, 'New admin created successfully');
} else {
    $insert->close();
    jsend(false, 'Database error: ' . $conn->error);
}
