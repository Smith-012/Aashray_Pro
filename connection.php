<?php
// Smart Database Connection
$is_local = empty(getenv('DB_HOST'));

if ($is_local) {
    // Local XAMPP Settings
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gp2530_db";
} else {
    // Live Cloud MySQL Settings (Set these in Vercel environment variables!)
    $servername = getenv('DB_HOST') ?: "";
    $username = getenv('DB_USER') ?: "";
    $password = getenv('DB_PASSWORD') ?: "";
    $dbname = getenv('DB_NAME') ?: "";
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ==================== SECURITY SUITE: AUTOMATIC CSRF PROTECTION ====================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Generate Session CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 2. Validate all incoming POST state modifications
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_uri = $_SERVER['REQUEST_URI'];
    // Exclude stateless check endpoints
    $is_check_api = (strpos($request_uri, 'check_username.php') !== false || 
                     strpos($request_uri, 'check_email.php') !== false || 
                     strpos($request_uri, 'check_contact_no.php') !== false);
                     
    if (!$is_check_api) {
        $submitted_token = $_POST['csrf_token'] ?? '';
        if (empty($submitted_token) || !hash_equals($_SESSION['csrf_token'], $submitted_token)) {
            http_response_code(403);
            die("Security Error: CSRF token validation failed! Invalid or expired token. Please refresh the page.");
        }
    }
}

// 3. Output Buffer Handler: Auto-inject tokens into HTML forms and auto-patch Javascript Fetch requests
if (!function_exists('csrf_global_buffer_handler')) {
    function csrf_global_buffer_handler($html) {
        if (empty($_SESSION['csrf_token'])) {
            return $html;
        }
        
        $token = $_SESSION['csrf_token'];
        
        // Inject hidden input in all HTML <form> tags
        $hidden_input = '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES) . '">';
        $html = preg_replace('/(<form[^>]*>)/i', '$1' . $hidden_input, $html);
        
        // Inject a global JavaScript patcher right before </body> to automatically append CSRF tokens to all JS fetch() calls
        $js_patch = '
        <script>
        (function() {
            const csrfToken = ' . json_encode($token) . ';
            
            // Intercept all window.fetch calls
            if (window.fetch) {
                const originalFetch = window.fetch;
                window.fetch = function(input, init) {
                    init = init || {};
                    if (init.method && init.method.toUpperCase() === "POST") {
                        init.headers = init.headers || {};
                        if (init.body instanceof URLSearchParams) {
                            init.body.append("csrf_token", csrfToken);
                        } else if (typeof init.body === "string") {
                            if (init.body.includes("=")) {
                                init.body += "&csrf_token=" + encodeURIComponent(csrfToken);
                            } else if (init.body === "") {
                                init.body = "csrf_token=" + encodeURIComponent(csrfToken);
                            }
                        } else if (init.body instanceof FormData) {
                            init.body.append("csrf_token", csrfToken);
                        }
                    }
                    return originalFetch(input, init);
                };
            }
        })();
        </script>';
        
        // Insert JS patch right before closing body tag
        if (stripos($html, '</body>') !== false) {
            $html = str_ireplace('</body>', $js_patch . '</body>', $html);
        } else {
            $html .= $js_patch;
        }
        
        return $html;
    }

    // Enable output buffering
    ob_start('csrf_global_buffer_handler');
}
?>
