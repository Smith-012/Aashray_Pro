<?php
header('Content-Type: application/json');
include('connection.php');
if ($conn->connect_error) {
    echo json_encode(['username_exists' => false, 'error' => 'Database connection failed.']);
    exit();
}
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $check_sql = "SELECT `user_username` FROM `users` WHERE `user_username` = ?";
    $check_stmt = $conn->prepare($check_sql);
    if ($check_stmt === false) {
        echo json_encode(['username_exists' => false, 'error' => 'Error preparing statement.']);
        $conn->close();
        exit();
    }
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        echo json_encode(['username_exists' => true]);
    } else {
        echo json_encode(['username_exists' => false]);
    }
    $check_stmt->close();
    $conn->close();
} else {
    echo json_encode(['username_exists' => false, 'error' => 'No username provided.']);
    $conn->close();
}
