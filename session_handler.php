<?php
class DatabaseSessionHandler implements SessionHandlerInterface {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function open($path, $name): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read($id): string|false {
        $stmt = $this->db->prepare("SELECT data FROM sessions WHERE id = ?");
        if (!$stmt) return "";
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return (string)$row['data'];
        }
        return "";
    }

    public function write($id, $data): bool {
        $stmt = $this->db->prepare("INSERT INTO sessions (id, data, last_accessed) VALUES (?, ?, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE data = ?, last_accessed = CURRENT_TIMESTAMP");
        if (!$stmt) return false;
        $stmt->bind_param("sss", $id, $data, $data);
        return $stmt->execute();
    }

    public function destroy($id): bool {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE id = ?");
        if (!$stmt) return false;
        $stmt->bind_param("s", $id);
        return $stmt->execute();
    }

    public function gc($max_lifetime): int|false {
        $stmt = $this->db->prepare("DELETE FROM sessions WHERE last_accessed < DATE_SUB(CURRENT_TIMESTAMP, INTERVAL ? SECOND)");
        if (!$stmt) return false;
        $stmt->bind_param("i", $max_lifetime);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}
?>
