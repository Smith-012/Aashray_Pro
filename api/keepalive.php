<?php
/**
 * Vercel API Keep-Alive Endpoint
 * Use this for external cron services or Vercel functions
 * URL: https://yourdomain.com/api/keepalive.php
 * 
 * Call from: GitHub Actions, cron-job.org, EasyCron, etc.
 */

// Enable error reporting
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Load .env configuration
function load_env_config() {
    $env_file = __DIR__ . '/../.env';
    
    if (!file_exists($env_file)) {
        // Check parent directory
        $env_file = dirname(dirname(__DIR__)) . '/.env';
    }
    
    $config = [
        'host'     => getenv('DB_HOST') ?: 'localhost',
        'user'     => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'database' => getenv('DB_NAME') ?: 'aashray',
        'port'     => getenv('DB_PORT') ?: 3306
    ];
    
    return $config;
}

// Send JSON response
function json_response($status, $message, $data = null, $http_code = 200) {
    header('Content-Type: application/json');
    http_response_code($http_code);
    
    $response = [
        'status'    => $status,
        'message'   => $message,
        'timestamp' => date('Y-m-d H:i:s'),
        'server'    => $_SERVER['SERVER_ADDR'] ?? 'unknown'
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

// Main keep-alive logic
try {
    $db_config = load_env_config();
    
    // Create connection
    $conn = new mysqli(
        $db_config['host'],
        $db_config['user'],
        $db_config['password'],
        $db_config['database'],
        $db_config['port']
    );
    
    // Check connection
    if ($conn->connect_error) {
        json_response('error', 'Database connection failed', [
            'error' => $conn->connect_error
        ], 500);
    }
    
    // Execute keep-alive query (read-only)
    $query = "SELECT 1 as keepalive, NOW() as timestamp, DATABASE() as `database`";
    $result = $conn->query($query);
    
    if (!$result) {
        json_response('error', 'Keep-alive query failed', [
            'error' => $conn->error
        ], 500);
    }
    
    $data = $result->fetch_assoc();
    $result->free();
    
    $response_data = [
        'database'    => $db_config['database'],
        'server_time' => $data['timestamp'],
        'keep_alive'  => (int)$data['keepalive'] === 1
    ];
    
    $conn->close();
    
    json_response('success', 'Database keep-alive check passed', $response_data, 200);
    
} catch (Exception $e) {
    json_response('error', 'An error occurred', [
        'exception' => $e->getMessage()
    ], 500);
}
?>
