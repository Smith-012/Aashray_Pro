<?php
include('../connection.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['logged_in_user']) && isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
if (!$isLoggedIn) {
    echo <<<EOD
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Redirecting...</title>
    <link href="assets/img/favicon.png" rel="icon">
</head>
<body class="admin-body">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script>
        Swal.fire({
            title: 'Aashray',
            text: 'Please sign in as an Admin before proceeding !!',
            icon: 'error',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            window.location.href = '../index.php';
        });
    </script>
</body>
</html>
EOD;
    exit;
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Aashray</title>
    <link href="../assets/img/favicon.png" rel="icon">
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link href="../assets/css/main.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
</head>

<body>
    <?php if (isset($_GET['logout_success']) && $_GET['logout_success'] === 'true'): ?>
        <div class="alert alert-success text-center mt-3" role="alert">
            You have been logged out successfully.
        </div>
    <?php endif; ?>
    <div class="sidebar" id="sidebar">
        <h1 class="sitename">
            <a href="dashboard.php">
                <img src="../assets/img/favicon.png" alt="Logo">
                <img src="../assets/img/aashray.png" alt="Aashray">
            </a>
        </h1>
        <nav class="nav flex-column">
            <a class="nav-link <?php echo ($currentPage == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
            <a class="nav-link <?php echo ($currentPage == 'admin.php') ? 'active' : ''; ?>" href="admin.php"><i class="bi bi-person-badge"></i> Admins</a>
            <a class="nav-link <?php echo ($currentPage == 'users.php') ? 'active' : ''; ?>" href="users.php"><i class="bi bi-people"></i> Users</a>
            <a class="nav-link <?php echo ($currentPage == 'past_users.php') ? 'active' : ''; ?>" href="past_users.php"><i class="bi bi-clock-history"></i> Former Users</a>
            <a class="nav-link <?php echo ($currentPage == 'contacts.php') ? 'active' : ''; ?>" href="contacts.php"><i class="bi bi-envelope"></i> Contacts</a>
            <a class="nav-link <?php echo ($currentPage == 'feedbacks.php') ? 'active' : ''; ?>" href="feedbacks.php"><i class="bi bi-chat-dots"></i> Feedbacks</a>
            <a class="nav-link <?php echo ($currentPage == 'bank-details.php') ? 'active' : ''; ?>" href="bank-details.php"><i class="bi bi-bank"></i> Bank Details</a>
            <a class="nav-link <?php echo ($currentPage == 'tenants.php') ? 'active' : ''; ?>" href="tenants.php"><i class="bi bi-person-lines-fill"></i> Tenants</a>
            <a class="nav-link <?php echo ($currentPage == 'payments.php') ? 'active' : ''; ?>" href="payments.php"><i class="bi bi-cash-stack"></i> Payments</a>
            <a class="nav-link <?php echo ($currentPage == 'bookings.php') ? 'active' : ''; ?>" href="bookings.php"><i class="bi bi-calendar-check"></i> Bookings</a>
            <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#confirmLogoutModal">
                <i class="bi bi-box-arrow-in-right"></i> Logout
            </a>
        </nav>
    </div>
    <div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to log out?</div>
                <div class="modal-footer">
                    <a href="../logout.php" class="btn btn-primary">Yes</a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <div class="overlay" id="overlay"></div>
    <div class="main-content">
        <button class="btn btn-dark d-md-none mb-3" id="toggleSidebar" aria-controls="sidebar" aria-expanded="false">
            <i class="bi bi-list"></i> Menu
        </button>