<?php
include('connection.php');
$contactNumber = $_GET['contactNumber'] ?? '';
$sql = "SELECT * FROM users WHERE contact_no = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $contactNumber);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verify Account</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <link href="assets/css/main.css" rel="stylesheet">
</head>

<body>
    <?php
    if ($result->num_rows > 0):
    ?>
        <script>
            Swal.fire({
                title: "Reset Password",
                html: `
            <div class="swal2-input-wrapper">
                <input type="password" id="newPassword" class="swal2-input" placeholder="Enter New Password">
                <span class="toggle-password" data-target="newPassword">Show</span>
            </div>
            <div class="swal2-input-wrapper">
                <input type="password" id="confirmPassword" class="swal2-input" placeholder="Confirm New Password">
                <span class="toggle-password" data-target="confirmPassword">Show</span>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: "Submit",
                cancelButtonText: "Cancel",
                allowOutsideClick: false,
                allowEscapeKey: false,
                backdrop: true,
                willOpen: () => {
                    const overlay = document.querySelector('.swal2-container');
                    if (overlay) {
                        overlay.style.background = 'rgba(0, 0, 0, 0.3)';
                        overlay.style.backdropFilter = 'blur(2px)';
                    }
                    document.querySelectorAll('.toggle-password').forEach(icon => {
                        icon.addEventListener('click', function() {
                            const targetInput = document.getElementById(this.dataset.target);
                            if (targetInput.type === 'password') {
                                targetInput.type = 'text';
                                this.textContent = 'Hide';
                            } else {
                                targetInput.type = 'password';
                                this.textContent = 'Show';
                            }
                        });
                    });
                },
                preConfirm: () => {
                    const newPassword = document.getElementById('newPassword').value.trim();
                    const confirmPassword = document.getElementById('confirmPassword').value.trim();
                    if (!newPassword || !confirmPassword) {
                        Swal.showValidationMessage("Both fields are required.");
                        return false;
                    }
                    if (newPassword !== confirmPassword) {
                        Swal.showValidationMessage("Passwords do not match.");
                        return false;
                    }
                    return {
                        newPassword
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const newPassword = encodeURIComponent(result.value.newPassword);
                    window.location.href = "update_password.php?contactNumber=<?= urlencode($contactNumber) ?>&newPassword=" + newPassword;
                } else {
                    window.location.href = "index.php";
                }
            });
        </script>
    <?php else: ?>
        <script>
            Swal.fire({
                title: "Invalid Contact Number",
                text: "This contact number is not registered!",
                icon: "error",
                confirmButtonText: "OK",
                allowOutsideClick: false,
                allowEscapeKey: false,
                backdrop: true,
                willOpen: () => {
                    const overlay = document.querySelector('.swal2-container');
                    if (overlay) {
                        overlay.style.background = 'rgba(0, 0, 0, 0.3)';
                        overlay.style.backdropFilter = 'blur(2px)';
                    }
                }
            }).then(() => {
                window.location.href = "index.php";
            });
        </script>
    <?php
    endif;
    // Note: $conn is closed automatically at end of request - do not close manually
    ?>
</body>

</html>