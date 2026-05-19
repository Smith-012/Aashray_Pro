<?php
header('Content-Type: application/json');
include('connection.php');
if ($conn->connect_error) {
    echo json_encode(['email_exists' => false, 'error' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}
if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
    if (empty($email)) {
        echo json_encode(['email_exists' => false, 'error' => 'Email value is empty.']);
        $conn->close();
        exit();
    }
    $check_sql = "SELECT email_id FROM users WHERE email_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    if (!$check_stmt) {
        echo json_encode(['email_exists' => false, 'error' => 'Error preparing SQL statement: ' . $conn->error]);
        $conn->close();
        exit();
    }
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_stmt->store_result();
    echo json_encode(['email_exists' => $check_stmt->num_rows > 0]);
    $check_stmt->close();
    $conn->close();
} else {
    echo json_encode(['email_exists' => false, 'error' => 'No email provided in POST request.']);
}
