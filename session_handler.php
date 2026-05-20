<?php
class DatabaseSessionHandler implements SessionHandlerInterface {
    private $db = null;

    private function getConn() {
        if ($this->db && !$this->db->connect_error) {
            return $this->db;
        }
        // Create a private, independent connection - never shared with app code
        $is_local = empty(getenv('DB_HOST'));
        if ($is_local) {
            $host     = '127.0.0.1';
            $user     = 'root';
            $pass     = '';
            $dbname   = 'gp2530_db';
            $port     = 3306;
        } else {
            $host   = getenv('DB_HOST');
            $user   = getenv('DB_USER');
            $pass   = getenv('DB_PASSWORD');
            $dbname = getenv('DB_NAME');
            $port   = (int)(getenv('DB_PORT') ?: 3306);
        }

        $conn = mysqli_init();
        if (!$is_local) {
            $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
            $conn->real_connect($host, $user, $pass, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);
        } else {
            $conn->real_connect($host, $user, $pass, $dbname, $port);
        }

        if ($conn->connect_error) {
            return null;
        }

        $this->db = $conn;
        return $this->db;
    }

    public function open($path, $name): bool {
        return $this->getConn() !== null;
    }

    public function close(): bool {
        // Do NOT close $this->db here - PHP will close it at end of request
        return true;
    }

    public function read($id): string|false {
        $db = $this->getConn();
        if (!$db) return "";
        $stmt = $db->prepare("SELECT data FROM sessions WHERE id = ?");
        if (!$stmt) return "";
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $stmt->close();
            return (string)$row['data'];
        }
        $stmt->close();
        return "";
    }

    public function write($id, $data): bool {
        $db = $this->getConn();
        if (!$db) return false;
        $stmt = $db->prepare("INSERT INTO sessions (id, data, last_accessed) VALUES (?, ?, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE data = ?, last_accessed = CURRENT_TIMESTAMP");
        if (!$stmt) return false;
        $stmt->bind_param("sss", $id, $data, $data);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function destroy($id): bool {
        $db = $this->getConn();
        if (!$db) return false;
        $stmt = $db->prepare("DELETE FROM sessions WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("s", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function gc($max_lifetime): int|false {
        $db = $this->getConn();
        if (!$db) return false;
        $stmt = $db->prepare("DELETE FROM sessions WHERE last_accessed < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL ? SECOND)");
        if (!$stmt) return false;
        $stmt->bind_param("i", $max_lifetime);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }
}
?>
