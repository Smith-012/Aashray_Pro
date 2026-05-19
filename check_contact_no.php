<?php
header('Content-Type: application/json');
include('connection.php');
if ($conn->connect_error) {
    echo json_encode(['contact_no_exists' => false, 'error' => 'Database connection failed.']);
    exit();
}
if (isset($_POST['contact_no'])) {
    $contact_no = $_POST['contact_no'];
    $check_sql = "SELECT `contact_no` FROM `users` WHERE `contact_no` = ?";
    $check_stmt = $conn->prepare($check_sql);
    if ($check_stmt === false) {
        echo json_encode(['contact_no_exists' => false, 'error' => 'Error preparing statement.']);
        $conn->close();
        exit();
    }
    $check_stmt->bind_param("s", $contact_no);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        echo json_encode(['contact_no_exists' => true]);
    } else {
        echo json_encode(['contact_no_exists' => false]);
    }
    $check_stmt->close();
    $conn->close();
} else {
    echo json_encode(['contact_no_exists' => false, 'error' => 'No contact number provided.']);
    $conn->close();
}
