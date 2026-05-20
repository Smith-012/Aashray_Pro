<?php
include('connection.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function sanitize($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $swalHeader = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>';
    $stmtUser = $conn->prepare("SELECT first_name,user_username, user_password FROM users WHERE user_username = ? LIMIT 1");
    $stmtUser->bind_param("s", $username);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $user = $resultUser->fetch_assoc();
    $stmtUser->close();
    if ($user && password_verify($password, $user['user_password'])) {
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['logged_in_user'] = $user['user_username'];
        $_SESSION['user_type'] = 'user';
        echo $swalHeader;
        echo <<<EOD
        <script>
        Swal.fire({
            title: 'Welcome Back!',
            text: '{$user['first_name']} you\'re logged in successfully.',
            icon: 'success',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            allowEscapeKey: false
        }).then(() => {
            window.location.href = 'userprofile.php';
        });
        </script>
        EOD;
        exit();
    }
    $stmtAdmin = $conn->prepare("SELECT admin_id, admin_username, admin_password FROM admins WHERE admin_username = ? LIMIT 1");
    $stmtAdmin->bind_param("s", $username);
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();
    $admin = $resultAdmin->fetch_assoc();
    $stmtAdmin->close();
    if ($admin) {
        $stored = $admin['admin_password'];
        $login_ok = false;
        if (password_verify($password, $stored)) {
            $login_ok = true;
        } else {
            if ($password === $stored) {
                $login_ok = true;
                $rehash = password_hash($password, PASSWORD_DEFAULT);
                $u = $conn->prepare("UPDATE admins SET admin_password = ? WHERE admin_id = ?");
                if ($u) {
                    $u->bind_param('si', $rehash, $admin['admin_id']);
                    $u->execute();
                    $u->close();
                }
            }
        }
        if ($login_ok) {
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['logged_in_user'] = $admin['admin_username'];
            $_SESSION['user_type'] = 'admin';
            echo $swalHeader;
            echo <<<EOD
            <script>
            Swal.fire({
                title: 'Admin Login',
                text: 'Admin logged in successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = 'adminside/dashboard.php';
            });
            </script>
            EOD;
            exit();
        }
    }
    echo $swalHeader;
    echo <<<EOD
    <script>
    Swal.fire({
        title: 'Login Failed',
        text: 'Invalid username or password!',
        icon: 'error',
        confirmButtonText: 'Try Again',
        allowOutsideClick: false,
        allowEscapeKey: false
    }).then(() => {
        window.history.back();
    });
    </script>
    EOD;
    exit();
}
?>
<footer id="footer" class="footer light-background">
    <div class="container">
        <div class="row gy-3">
            <div class="col-lg-3 col-md-6 d-flex">
                <i class="bi bi-geo-alt icon"></i>
                <div class="address">
                    <h4>Address</h4>
                    <p>Waghawadi Road ,</p>
                    <p>Bhavnagar - 364001</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 d-flex">
                <i class="bi bi-telephone icon"></i>
                <div>
                    <h4>Contact</h4>
                    <p>
                        <strong>Contact :</strong> <span>(123) 456 7890</span><br>
                        <strong>E-mail Id :</strong> <span>helloaashray25@gmail.com</span><br>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 d-flex">
                <i class="bi bi-clock icon"></i>
                <div>
                    <h4>Opening Hours</h4>
                    <p>
                        <strong>Mon - Sat :</strong> <span>10:00AM - 08:00PM</span><br>
                        <strong>Sunday :</strong> <span>Closed</span>
                    </p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <h4>Follow Us</h4>
                <div class="social-links d-flex">
                    <a href="404error.php" class="twitter social-link" title="https://x.com/aashray_25"><i class="bi bi-twitter-x"></i></a>
                    <a href="404error.php" class="facebook social-link" title="https://facebook.com/aashray_25"><i class="bi bi-facebook"></i></a>
                    <a href="404error.php" class="instagram social-link" title="https://instagram.com/_s.p._1"><i class="bi bi-instagram"></i></a>
                    <a href="404error.php" class="linkedin social-link" title="https://linkedin.com/aashray_25"><i class="bi bi-linkedin"></i></a>
                    <a href="404error.php" class="github social-link" title="https://github.com/Smith-0112"><i class="bi bi-github"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="container copyright text-center mt-4">
        <p>© <span>Copyright 2025</span>
            <span>All Rights Reserved by</span>
            <a href="#" id="scroll-top"><strong class="px-1 sitename text-accent">Aashray</strong></a>
        </p>
        <div class="credits">
        </div>
    </div>
</footer>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login to Aashray</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="loginForm" autocomplete="off">
                        <div class="mb-3">
                            <label for="loginUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="loginUsername" name="username" placeholder="Enter Username" required>
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="Enter Password" required>
                                <button class="btn btn-outline-secondary" type="button" id="togglePasswordBtn">Show</button>
                            </div>
                        </div>
                        <div class="mb-3 text-end">
                            <a href="#" id="forgotPasswordLink">Forgot Password?</a>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success" name="login">Sign in</button>
                        </div>
                    </form>
                </div>
                <hr>
                <div class="mt-4 text-center">
                    <h4>New User ?</h4>
                    <p>Join Us today and get updated with all new properties...</p>
                    <button type="button" class="btn btn-info" onclick="window.location.href='register.php'">Join Us</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('togglePasswordBtn').addEventListener('click', function() {
        const passwordInput = document.getElementById('loginPassword');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            this.textContent = 'Show';
        }
    });
    document.getElementById('forgotPasswordLink').addEventListener('click', function(e) {
        e.preventDefault();
        var loginModalEl = document.getElementById('loginModal');
        var loginModal = bootstrap.Modal.getInstance(loginModalEl);
        if (loginModal) {
            loginModal.hide();
        }
        Swal.fire({
            title: 'Forgot Password',
            html: '<input id="swal-username" class="swal2-input" placeholder="Username" maxlength="30" autocomplete="off">' +
                '<input id="swal-contact" class="swal2-input" placeholder="10-digit contact number" inputmode="numeric" maxlength="10" pattern="\\d{10}" oninput="this.value=this.value.replace(/\\D/g,\'\')" autocomplete="off">',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Verify',
            cancelButtonText: 'Cancel',
            allowOutsideClick: false,
            allowEscapeKey: false,
            preConfirm: () => {
                const username = document.getElementById('swal-username').value.trim();
                const contact = document.getElementById('swal-contact').value.trim();
                if (!username) {
                    Swal.showValidationMessage('Username is required');
                    return false;
                }
                if (!/^\d{10}$/.test(contact)) {
                    Swal.showValidationMessage('Enter a valid 10-digit contact number');
                    return false;
                }
                return {
                    username: username,
                    contact: contact
                };
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const u = encodeURIComponent(result.value.username);
                const c = encodeURIComponent(result.value.contact);
                window.location.href = 'verify.php?username=' + u + '&contactNumber=' + c;
            }
        });
    });
    (function() {
        try {
            var params = new URLSearchParams(window.location.search);
            if (params.get('status') === 'logout_success') {
                var s = document.createElement('script');
                s.src = 'assets/js/sweetalert.js';
                s.onload = function() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Logged out',
                            text: 'You have been logged out successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            if (window.history.replaceState) {
                                const cleanUrl = window.location.origin + window.location.pathname;
                                window.history.replaceState({}, document.title, cleanUrl);
                            }
                        });
                    }
                };
                document.head.appendChild(s);
            }
        } catch (e) {}
    })();
</script>
<a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<div id="preloader"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
<script src="assets/js/main.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.min.js"></script>
<script>
    AOS.init({
        duration: 1000,
        easing: "ease-in-out",
        once: true,
        mirror: false
    });
</script>

</html>