<?php
include('connection.php');
$contactNumber = $_GET['contactNumber'] ?? '';
$newPass = $_GET['newPassword'] ?? '';
$plain_password = password_hash($newPass, PASSWORD_DEFAULT);

// Secure SQL Prepared Statement
$stmt = $conn->prepare("UPDATE users SET user_password = ? WHERE contact_no = ?");
$stmt->bind_param("ss", $plain_password, $contactNumber);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Password Updated</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>

<body>
    <?php if ($stmt->execute() === TRUE): ?>
        <script>
            Swal.fire({
                title: "Success!",
                text: "Your password has been changed successfully.",
                icon: "success",
                confirmButtonText: "OK",
                allowOutsideClick: false,
                allowEscapeKey: false,
                backdrop: true,
                willOpen: () => {
                    const overlay = document.querySelector('.swal2-container');
                    if (overlay) {
                        overlay.style.background = 'rgba(0, 0, 0, 0.5)';
                        overlay.style.backdropFilter = 'blur(3px)';
                    }
                }
            }).then(() => {
                window.location.href = "index.php";
            });
        </script>
    <?php else: ?>
        <script>
            Swal.fire({
                title: "Error!",
                text: "Error updating password: <?= addslashes($conn->error) ?>",
                icon: "error",
                confirmButtonText: "Try Again",
                backdrop: true,
                willOpen: () => {
                    const overlay = document.querySelector('.swal2-container');
                    if (overlay) {
                        overlay.style.background = 'rgba(0, 0, 0, 0.5)';
                        overlay.style.backdropFilter = 'blur(3px)';
                    }
                }
            }).then(() => {
                window.location.href = "index.php";
            });
        </script>
    <?php endif;
    $conn->close(); ?>
</body>

</html>