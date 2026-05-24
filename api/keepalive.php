<?php
/**
 * Keep-alive endpoint with connection retries and clearer errors
 */

ini_set('display_errors', 0);
error_reporting(E_ALL);

function load_env_config() {
    return [
        'host'     => getenv('DB_HOST') ?: 'localhost',
        'user'     => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: '',
        'database' => getenv('DB_NAME') ?: 'aashray',
        'port'     => (int)(getenv('DB_PORT') ?: 3306),
        'connect_timeout' => (int)(getenv('DB_CONNECT_TIMEOUT') ?: 5),
    ];
}

function json_response($status, $message, $data = null, $http_code = 200) {
    header('Content-Type: application/json');
    http_response_code($http_code);
    $response = [
        'status'    => $status,
        'message'   => $message,
        'timestamp' => date('Y-m-d H:i:s'),
        'server'    => $_SERVER['SERVER_ADDR'] ?? 'unknown'
    ];
    if ($data !== null) $response['data'] = $data;
    echo json_encode($response);
    exit;
}

try {
    $db_config = load_env_config();

    // Retry connect loop (handles transient DNS/network blips)
    $max_attempts = 3;
    $attempt = 0;
    $conn = null;
    while ($attempt < $max_attempts) {
        $attempt++;
        $conn = @new mysqli(
            $db_config['host'],
            $db_config['user'],
            $db_config['password'],
            $db_config['database'],
            $db_config['port']
        );

        if (!$conn->connect_error) {
            break;
        }

        // small backoff
        sleep(1);
    }

    if ($conn === null || $conn->connect_error) {
        $err = $conn ? $conn->connect_error : 'unknown';
        json_response('error', 'Database connection failed after retries', [
            'attempts' => $attempt,
            'error' => $err
        ], 500);
    }

    // Read-only keep-alive query
    $query = "SELECT 1 as keepalive, NOW() as timestamp, DATABASE() as `database`";
    $result = $conn->query($query);
    if (!$result) {
        $err = $conn->error;
        $conn->close();
        json_response('error', 'Keep-alive query failed', ['error' => $err], 500);
    }

    $row = $result->fetch_assoc();
    $result->free();
    $conn->close();

    $response_data = [
        'database'    => $db_config['database'],
        'server_time' => $row['timestamp'] ?? null,
        'keep_alive'  => ((int)($row['keepalive'] ?? 0)) === 1
    ];

    json_response('success', 'Database keep-alive check passed', $response_data, 200);

} catch (Exception $e) {
    json_response('error', 'An exception occurred', ['exception' => $e->getMessage()], 500);
}
