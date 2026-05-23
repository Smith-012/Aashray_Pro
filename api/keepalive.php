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
        'port'     => (int)(getenv('DB_PORT') ?: 3306)
    ];
    
    // If .env file exists, parse it (for local testing)
    if (file_exists($env_file)) {
        $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, ' "\'');
                
                if ($key === 'DB_HOST') $config['host'] = $value;
                if ($key === 'DB_USER') $config['user'] = $value;
                if ($key === 'DB_PASSWORD') $config['password'] = $value;
                if ($key === 'DB_NAME') $config['database'] = $value;
                if ($key === 'DB_PORT') $config['port'] = (int)$value;
            }
        }
    }
    
    return $config;
}

// JSON Response Helper
function json_response($status, $message, $data = null, $http_code = 200) {
    header('Content-Type: application/json');
    http_response_code($http_code);
    
    $response = [
        'status' => $status,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s'),
        'server' => gethostname()
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit;
}

// CORS headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load configuration
$db_config = load_env_config();

try {
    // Create mysqli connection
    $conn = new mysqli(
        $db_config['host'],
        $db_config['user'],
        $db_config['password'],
        $db_config['database'],
        $db_config['port']
    );
    
    // Check connection
    if ($conn->connect_error) {
        json_response('error', 'Database connection failed', null, 500);
    }
    
    // Set charset
    $conn->set_charset('utf8mb4');
    
    // Execute keep-alive query
    $query = "SELECT 1 as keepalive, NOW() as timestamp, DATABASE() as database";
    $result = $conn->query($query);
    
    if (!$result) {
        json_response('error', 'Keep-alive query failed', ['error' => $conn->error], 500);
    }
    
    $data = $result->fetch_assoc();
    $result->free();
    
    $response_data = [
        'database' => $db_config['database'],
        'server_time' => $data['timestamp'],
        'keep_alive' => $data['keepalive'] === 1
    ];
    
    $conn->close();
    
    json_response('success', 'Database keep-alive check passed', $response_data, 200);
    
} catch (Exception $e) {
    json_response('error', 'An error occurred', ['exception' => $e->getMessage()], 500);
}
?>
