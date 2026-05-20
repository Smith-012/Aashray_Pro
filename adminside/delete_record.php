<?php
include('../connection.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['logged_in_user']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    echo "Unauthorized access! Admin privileges required.";
    exit;
}
include '../connection.php';
if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $query = "DELETE FROM `users` WHERE `user_id` = $id";
    if (mysqli_query($conn, $query)) {
        echo "Record deleted successfully !!";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else if (isset($_POST['property_sr_no']) && !empty($_POST['property_sr_no'])) {
    $id = mysqli_real_escape_string($conn, $_POST['property_sr_no']);
    $select = mysqli_prepare($conn, "SELECT property_photos FROM properties WHERE property_sr_no = ? LIMIT 1");
    if ($select) {
        mysqli_stmt_bind_param($select, 'i', $id);
        mysqli_stmt_execute($select);
        mysqli_stmt_bind_result($select, $property_photos);
        if (mysqli_stmt_fetch($select)) {
            if (!empty($property_photos)) {
                $photos = json_decode($property_photos, true);
                if (is_array($photos)) {
                    $root = realpath(__DIR__ . '/../');
                    foreach ($photos as $p) {
                        if (!is_string($p)) continue;
                        $p = ltrim($p, '/\\');
                        if (stripos($p, 'assets/') !== 0 && stripos($p, 'assets\\') !== 0) continue;
                        $full = $root . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $p);
                        if (strpos(realpath(dirname($full)) ?: '', $root) !== 0) continue;
                        if (file_exists($full) && is_file($full)) {
                            @unlink($full);
                        }
                    }
                }
            }
        }
        mysqli_stmt_close($select);
    }
    $query = "DELETE FROM `properties` WHERE `property_sr_no` = $id";
    if (mysqli_query($conn, $query)) {
        echo "Record deleted successfully !!";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else if (isset($_POST['feedback_id']) && !empty($_POST['feedback_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['feedback_id']);
    $query = "DELETE FROM `feedback` WHERE `feedback_id` = $id";
    if (mysqli_query($conn, $query)) {
        echo "Record deleted successfully !!";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else if (isset($_POST['contact_id']) && !empty($_POST['contact_id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['contact_id']);
    $query = "DELETE FROM `contact_us` WHERE `contact_id` = $id";
    if (mysqli_query($conn, $query)) {
        echo "Record deleted successfully !!";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request";
}
mysqli_close($conn);
