<?php
include('connection.php');
session_start();
if (isset($_POST['saveBtn'])) {
    $firstname = trim($_POST['first_name']);
    $lastname = trim($_POST['last_name']);
    $user_username = trim($_POST['user_username_hidden']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $contactno = trim($_POST['contact_no']);
    $emailid = trim($_POST['email_id']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);
    $state = trim($_POST['state']);
    $sql = "UPDATE `users` SET 
                `first_name` = ?, 
                `last_name` = ?, 
                `dob` = ?, 
                `gender` = ?, 
                `contact_no` = ?, 
                `email_id` = ?, 
                `address` = ?, 
                `city` = ?, 
                `pincode` = ?, 
                `state` = ? 
            WHERE `user_username` = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param(
            "sssssssssss",
            $firstname,
            $lastname,
            $dob,
            $gender,
            $contactno,
            $emailid,
            $address,
            $city,
            $pincode,
            $state,
            $user_username
        );
        if ($stmt->execute()) {
            echo <<<EOD
            <!DOCTYPE html>
            <html>
            <head>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
                <title>Updating...</title>
                <link href="assets/img/favicon.png" rel="icon">
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Profile Updated',
                        text: 'Your changes have been saved successfully!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'userprofile.php';
                    });
                </script>
            </body>
            </html>
            EOD;
        } else {
            $error = htmlspecialchars($stmt->error);
            echo <<<EOD
            <!DOCTYPE html>
            <html>
            <head>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
                <title>Error...</title>
                <link href="assets/img/favicon.png" rel="icon">
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Error updating record: {$error}',
                        confirmButtonText: 'Go Back'
                    }).then(() => {
                        window.history.back();
                    });
                </script>
            </body>
            </html>
            EOD;
        }
        $stmt->close();
    } else {
        echo <<<EOD
        <!DOCTYPE html>
        <html>
        <head>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
            <title>Error...</title>
            <link href="assets/img/favicon.png" rel="icon">
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Database Error',
                    text: 'Failed to prepare SQL statement.',
                    confirmButtonText: 'Go Back'
                }).then(() => {
                    window.history.back();
                });
            </script>
        </body>
        </html>
        EOD;
    }
    $conn->close();
} else {
    echo <<<EOD
    <!DOCTYPE html>
    <html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
        <title>Invalid</title>
        <link href="assets/img/favicon.png" rel="icon">
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Access',
                text: 'Please submit the form properly.',
                confirmButtonText: 'Go Back'
            }).then(() => {
                window.history.back();
            });
        </script>
    </body>
    </html>
    EOD;
}
