<?php
include('connection.php');

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$isLoggedIn = isset($_SESSION['logged_in_user']);
// Dynamic Error Reporting (Only show errors on localhost)
$is_local = empty(getenv('DB_HOST'));

if ($is_local) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
date_default_timezone_set('Asia/Kolkata');
$conn->query("SET time_zone = '" . date('P') . "'");
$current_time = date("Y-m-d H:i:s");
$conn->query("
    UPDATE properties p
    SET booking = 'Available'
    WHERE booking = 'Not Available'
      AND EXISTS (
          SELECT 1
          FROM invoice i
          WHERE i.property_no = p.property_no
            AND i.rent_end_date IS NOT NULL
            AND i.rent_end_date <= '$current_time'
      )
");
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Aashray</title>
  <link href="assets/img/favicon.png" rel="icon">

  <!-- AOS Complete CDN Files (v2.3.4) -->

  <!-- CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">

  <!-- Standard JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

  <!-- Minified JS (Recommended) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.js"></script>

  <!-- ESM Module Version -->
  <script type="module" src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.esm.js"></script>

  <!-- CommonJS Version -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.cjs.js"></script>

  <!-- JS Map File -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js.map"></script>


  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body class="index-page">
  <?php if (isset($_GET['logout_success']) && $_GET['logout_success'] == 'true'): ?>
    <div class="alert alert-success text-center mt-3" role="alert">
      You have been logged out successfully.
    </div>
  <?php endif; ?>
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
      <h1 class="sitename header-branding">
        <a href="index.php" class="brand-link">
          <img src="assets/img/favicon.png" alt="Logo" class="header-logo">
        </a>
        <a href="#" id="scroll-top" class="brand-link">
          <img src="assets/img/aashray.png" alt="Aashray" class="header-logo">
        </a>
      </h1>
      <div class="d-flex align-items-center header-actions">
        <nav id="navmenu" class="navmenu">
          <?php $current = basename($_SERVER['PHP_SELF']); ?>
          <i class="mobile-nav-toggle d-xl-none bi bi-list" onclick="toggleMobileMenu()"></i>
          <ul id="mobileMenu">
            <li><a href="index.php" class="<?= $current == 'index.php' ? 'active' : '' ?>">Home</a></li>
            <li><a href="about.php" class="<?= $current == 'about.php' ? 'active' : '' ?>">About</a></li>
            <li><a href="properties.php" class="<?= $current == 'properties.php' ? 'active' : '' ?>">Properties</a></li>
            <li><a href="services.php" class="<?= $current == 'services.php' ? 'active' : '' ?>">Services</a></li>
            <li><a href="feedback.php" class="<?= $current == 'feedback.php' ? 'active' : '' ?>">Feedback</a></li>
            <li><a href="contact.php" class="<?= $current == 'contact.php' ? 'active' : '' ?>">Contact Us</a></li>
            <li>
              <div class="profile-btn-wrapper">
                <a href="userprofile.php"
                  class="classic-profile-btn <?= $current == 'userprofile.php' ? 'active' : '' ?>"></a>
              </div>
            </li>
            <li>
              <?php if ($isLoggedIn): ?>
                <button type="button" class="btn auth-pill-btn" data-bs-toggle="modal" data-bs-target="#confirmLogoutModal">
                  Logout
                </button>
              <?php else: ?>
                <button type="button" class="btn auth-pill-btn" data-bs-toggle="modal" data-bs-target="#loginModal">
                  Login
                </button>
              <?php endif; ?>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </header>
  <div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmLogoutModalLabel">Confirm Logout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Are you sure you want to log out?</div>
        <div class="modal-footer">
          <a href="logout.php" class="btn btn-primary" id="confirmLogoutButton">Yes</a>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <script>
    function toggleMobileMenu() {
      const menu = document.getElementById('mobileMenu');
      menu.classList.toggle('show');
    }
    document.getElementById('confirmLogoutButton')?.addEventListener('click', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Logging Out',
        text: 'You are being logged out...',
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
          setTimeout(() => {
            window.location.href = 'logout.php';
          }, 1000);
        }
      });
    });
  </script>