<?php
// Log.php
class Log {
    private $conn;
    private $table_name = "logs";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($acao, $produto_id, $userInsert) {
        $query = "INSERT INTO " . $this->table_name . " (acao, produto_id, userInsert) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$acao, $produto_id, $userInsert]);
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
