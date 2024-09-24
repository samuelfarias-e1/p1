<?php
// database.php
class Database {
    private $db_name = 'gerenciamento_produtos.db';
    public $conn;

    public function dbConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("sqlite:" . $this->db_name);
            $this->createTables();
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }

    private function createTables() {
        $this->conn->exec("CREATE TABLE IF NOT EXISTS produtos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            descricao TEXT,
            preco REAL NOT NULL CHECK(preco >= 0),
            estoque INTEGER NOT NULL CHECK(estoque >= 0),
            userInsert TEXT NOT NULL,
            data_hora DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $this->conn->exec("CREATE TABLE IF NOT EXISTS logs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            acao TEXT NOT NULL,
            data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
            produto_id INTEGER,
            userInsert TEXT NOT NULL,
            FOREIGN KEY(produto_id) REFERENCES produtos(id)
        )");
    }
}
?>
