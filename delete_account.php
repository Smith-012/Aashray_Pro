<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['logged_in_user'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}
include 'connection.php';
$username = $_SESSION['logged_in_user'];
$postedPassword = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['password'])) {
        $postedPassword = $_POST['password'];
    } else {
        $raw = file_get_contents('php://input');
        parse_str($raw, $out);
        if (isset($out['password'])) $postedPassword = $out['password'];
    }
}
if (empty($postedPassword)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password is required for verification']);
    $conn->close();
    exit;
}
$stmt_check = $conn->prepare("SELECT user_password FROM `users` WHERE `user_username` = ? LIMIT 1");
if ($stmt_check === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: prepare failed']);
    $conn->close();
    exit;
}
$stmt_check->bind_param('s', $username);
if (!$stmt_check->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: execute failed']);
    $stmt_check->close();
    $conn->close();
    exit;
}
$stmt_check->store_result();
if ($stmt_check->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'User not found']);
    $stmt_check->close();
    $conn->close();
    exit;
}
$stmt_check->bind_result($dbPassword);
$stmt_check->fetch();
$stmt_check->close();
$passwordMatches = false;
if (password_verify($postedPassword, $dbPassword)) {
    $passwordMatches = true;
} elseif ($postedPassword === $dbPassword) {
    $passwordMatches = true;
}
if (!$passwordMatches) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Password incorrect']);
    $conn->close();
    exit;
}
$copy_sql = "SELECT user_username, first_name, last_name, user_password, dob, gender, address, city, contact_no, email_id, reg_date, state, pincode FROM `users` WHERE `user_username` = ? LIMIT 1";
$copy_stmt = $conn->prepare($copy_sql);
if ($copy_stmt === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: prepare failed (copy select)']);
    $conn->close();
    exit;
}
$copy_stmt->bind_param('s', $username);
if (!$copy_stmt->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: execute failed (copy select)']);
    $copy_stmt->close();
    $conn->close();
    exit;
}
$copy_stmt->store_result();
if ($copy_stmt->num_rows === 1) {
    $copy_stmt->bind_result($c_user_username, $c_first_name, $c_last_name, $c_user_password, $c_dob, $c_gender, $c_address, $c_city, $c_contact_no, $c_email_id, $c_reg_date, $c_state, $c_pincode);
    $copy_stmt->fetch();
    $copy_stmt->close();
    $insert_sql = "INSERT INTO `past_users` (user_username, first_name, last_name, user_password, dob, gender, address, city, contact_no, email_id, reg_date, state, pincode, deleted_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $ins = $conn->prepare($insert_sql);
    if ($ins === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: prepare failed (insert past_users)']);
        $conn->close();
        exit;
    }
    $ins->bind_param('sssssssssssss', $c_user_username, $c_first_name, $c_last_name, $c_user_password, $c_dob, $c_gender, $c_address, $c_city, $c_contact_no, $c_email_id, $c_reg_date, $c_state, $c_pincode);
    if (!$ins->execute()) {
        $err = $ins->error;
        $ins->close();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to copy to past_users: ' . $err]);
        $conn->close();
        exit;
    }
    $ins->close();
    $stmt = $conn->prepare("DELETE FROM `users` WHERE `user_username` = ?");
    if ($stmt === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: prepare failed (delete)']);
        $conn->close();
        exit;
    }
    $stmt->bind_param('s', $username);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Account deleted successfully.']);
        exit;
    } else {
        $err = $stmt->error;
        $stmt->close();
        $conn->close();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Delete failed: ' . $err]);
        exit;
    }
} else {
    $copy_stmt->close();
    $conn->close();
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'User not found for copy']);
    exit;
}
