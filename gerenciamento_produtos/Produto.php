<?php
// Produto.php
class Produto {
    private $conn;
    private $table_name = "produtos";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($nome, $descricao, $preco, $estoque, $userInsert) {
        if (strlen($nome) < 3 || $preco < 0 || $estoque < 0) {
            return false;
        }
        
        $query = "INSERT INTO " . $this->table_name . " (nome, descricao, preco, estoque, userInsert) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nome, $descricao, $preco, $estoque, $userInsert]);

        $this->logOperation('criação', $this->conn->lastInsertId(), $userInsert);
        return true;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $nome, $descricao, $preco, $estoque, $userInsert) {
        if (strlen($nome) < 3 || $preco < 0 || $estoque < 0) {
            return false;
        }

        $query = "UPDATE " . $this->table_name . " SET nome = ?, descricao = ?, preco = ?, estoque = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nome, $descricao, $preco, $estoque, $id]);

        $this->logOperation('atualização', $id, $userInsert);
        return true;
    }

    public function delete($id, $userInsert) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        $this->logOperation('exclusão', $id, $userInsert);
        return true;
    }

    private function logOperation($acao, $produto_id, $userInsert) {
        $log = new Log($this->conn);
        $log->create($acao, $produto_id, $userInsert);
    }
}
?>
