<?php
session_start();
if (!isset($_SESSION['logged_in_user'])) {
    echo <<<EOD
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Redirecting...</title>
    <link href="assets/img/favicon.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</head>
<body>
    <script>
        Swal.fire({
            title: 'Aashray',
            text: 'Please sign in to Aashray before proceeding !!',
            icon: 'error',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            window.history.back();
        });
    </script>
</body>
</html>
EOD;
    exit;
}
include 'header.php';
?>
<main class="main">
    <br><br><br><br><br>
    <section id="profile" class="profile section">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <?php
                    if (isset($_SESSION['logged_in_user'])) {
                        include 'connection.php';
                        $username = mysqli_real_escape_string($conn, $_SESSION['logged_in_user']);
                        $sql = "SELECT * FROM `users` WHERE `user_username` = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                        } else {
                            echo '<div class="alert alert-danger">User not found</div>';
                            exit;
                        }
                        $stmt->close();
                        $conn->close();
                    } else {
                        echo '<div class="alert alert-danger">User not logged in</div>';
                        exit;
                    }
                    ?>
                    <div class="profile-card">
                        <div class="profile-header">
                            <div class="profile-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="profile-info">
                                <h2><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></h2>
                                <p class="text-muted">@<?php echo htmlspecialchars($row['user_username']); ?></p>
                            </div>
                        </div>
                        <div class="profile-body">
                            <form id="profileForm" action="update_profile.php" method="post" autocomplete="off">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <th scope="row"><label for="first_name">First Name</label></th>
                                            <td><input type="text" class="form-control" name="first_name" id="first_name"
                                                    value="<?php echo htmlspecialchars($row['first_name']); ?>" readonly></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="last_name">Last Name</label></th>
                                            <td><input type="text" class="form-control" name="last_name" id="last_name"
                                                    value="<?php echo htmlspecialchars($row['last_name']); ?>" readonly></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="user_username">Username</label></th>
                                            <td>
                                                <input type="text" class="form-control" id="user_username"
                                                    value="<?php echo htmlspecialchars($row['user_username']); ?>" readonly>
                                                <input type="hidden" name="user_username_hidden"
                                                    value="<?php echo htmlspecialchars($row['user_username']); ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="email_id">Email Address</label></th>
                                            <td><input type="email" class="form-control" name="email_id" id="email_id"
                                                    value="<?php echo htmlspecialchars($row['email_id']); ?>" readonly></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="contact_no">Contact Number</label></th>
                                            <td><input type="tel" class="form-control" name="contact_no" id="contact_no" maxlength="10"
                                                    value="<?php echo htmlspecialchars($row['contact_no']); ?>" readonly></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="dob">Date of Birth</label></th>
                                            <td><input type="date" class="form-control" name="dob" id="dob"
                                                    value="<?php echo htmlspecialchars($row['dob']); ?>" readonly></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="gender">Gender</label></th>
                                            <td>
                                                <select class="form-control" name="gender" id="gender" data-readonly="true">
                                                    <option value="Male" <?php echo ($row['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                                    <option value="Female" <?php echo ($row['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                                    <option value="Other" <?php echo ($row['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="city">City</label></th>
                                            <td><input type="text" class="form-control" name="city" id="city"
                                                    value="<?php echo htmlspecialchars($row['city']); ?>" readonly></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="address">Address</label></th>
                                            <td><textarea class="form-control" name="address" id="address" rows="2"
                                                    readonly><?php echo htmlspecialchars($row['address']); ?></textarea></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="pincode">Pincode</label></th>
                                            <td><input type="text" class="form-control" name="pincode" id="pincode"
                                                    value="<?php echo htmlspecialchars($row['pincode']); ?>" readonly></td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><label for="state">State</label></th>
                                            <td><input type="text" class="form-control" name="state" id="state"
                                                    value="<?php echo htmlspecialchars($row['state']); ?>" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="profile-actions">
                                    <button type="button" class="btn btn-primary" onclick="enableEdit()" id="editBtn">
                                        <i class="bi bi-pencil"></i> Edit Profile
                                    </button>
                                    <button type="submit" class="btn btn-success profile-toggle-hidden" id="saveBtn" name="saveBtn">
                                        <i class="bi bi-check-lg"></i> Save Changes
                                    </button>
                                    <button type="button" class="btn btn-secondary profile-toggle-hidden" onclick="cancelEdit()" id="cancelBtn">
                                        <i class="bi bi-x-lg"></i> Cancel
                                    </button>
                                    <button type="button" class="btn btn-danger" id="deleteAccountBtn" onclick="confirmDelete()">
                                        <i class="bi bi-trash"></i> Delete Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        let originalValues = {};
        let editMode = false;

        function enableEdit() {
            editMode = true;
            document.querySelectorAll('input, select, textarea').forEach(el => {
                if (!el.id || el.type === 'hidden') return;
                if (el.id === 'user_username') return;
                originalValues[el.id] = el.value;
                if (el.tagName !== 'SELECT') {
                    el.readOnly = false;
                }
            });
            document.getElementById('editBtn').style.display = 'none';
            document.getElementById('saveBtn').style.display = 'inline-block';
            document.getElementById('cancelBtn').style.display = 'inline-block';
        }

        function cancelEdit() {
            editMode = false;
            document.querySelectorAll('input, select, textarea').forEach(el => {
                if (!el.id || el.type === 'hidden') return;
                if (el.id === 'user_username') return;
                if (originalValues.hasOwnProperty(el.id)) {
                    el.value = originalValues[el.id];
                }
                if (el.tagName !== 'SELECT') {
                    el.readOnly = true;
                }
            });
            document.getElementById('editBtn').style.display = 'inline-block';
            document.getElementById('saveBtn').style.display = 'none';
            document.getElementById('cancelBtn').style.display = 'none';
            originalValues = {};
        }
        document.addEventListener('DOMContentLoaded', function() {
            const dobInput = document.getElementById('dob');
            const today = new Date();
            const fifteenYearsAgo = new Date(today.getFullYear() - 15, today.getMonth(), today.getDate());
            const maxDate = fifteenYearsAgo.toISOString().split('T')[0];
            dobInput.max = maxDate;
        });
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email_id').value;
            const contact = document.getElementById('contact_no').value;
            const dob = document.getElementById('dob').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const contactRegex = /^[0-9]{10}$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Email',
                    text: 'Please enter a valid email address.'
                });
                return;
            }
            if (!contactRegex.test(contact)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Contact',
                    text: 'Please enter a valid 10-digit contact number.'
                });
                return;
            }
            if (dob) {
                const enteredDate = new Date(dob);
                const today = new Date();
                const minAllowedDOB = new Date(today.getFullYear() - 15, today.getMonth(), today.getDate());
                if (enteredDate > minAllowedDOB) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid DOB',
                        text: 'User must be at least 15 years old.'
                    });
                    return;
                }
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('select[data-readonly="true"]').forEach(function(sel) {
                if (!originalValues.hasOwnProperty(sel.id)) {
                    originalValues[sel.id] = sel.value;
                }
                sel.addEventListener('mousedown', function(e) {
                    if (!editMode) {
                        e.preventDefault();
                        sel.blur();
                    }
                });
                sel.addEventListener('keydown', function(e) {
                    if (!editMode) {
                        e.preventDefault();
                    }
                });
                sel.addEventListener('change', function(e) {
                    if (!editMode) {
                        if (originalValues && originalValues[sel.id] !== undefined) {
                            sel.value = originalValues[sel.id];
                        }
                    }
                });
            });
        });

        function confirmDelete() {
            Swal.fire({
                title: 'Confirm account deletion',
                text: 'Enter your password to permanently delete your account.',
                input: 'password',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocorrect: 'off'
                },
                inputPlaceholder: 'Your password',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Delete account',
                cancelButtonText: 'Cancel',
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Password is required');
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const pwd = result.value;
                    fetch('delete_account.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `password=${encodeURIComponent(pwd)}`
                    }).then(async response => {
                        const data = await response.json();
                        if (response.ok && data.success) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: data.message || 'Account deleted successfully.',
                                icon: 'success',
                                allowOutsideClick: false
                            }).then(() => {
                                window.location.href = 'index.php';
                            });
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'Could not delete account. Try again later.',
                                icon: 'error'
                            });
                        }
                    }).catch(err => {
                        console.error('Delete request failed', err);
                        Swal.fire({
                            title: 'Error',
                            text: 'Network error. Please try again later.',
                            icon: 'error'
                        });
                    });
                } else {}
            });
        }
    </script>
    <?php include 'footer.php'; ?>