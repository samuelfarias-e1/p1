<?php
// index.php
require 'database.php';
require 'Produto.php';
require 'Log.php';

$db = (new Database())->dbConnection();

header("Content-Type: application/json");
$requestMethod = $_SERVER["REQUEST_METHOD"];
$path = explode('/', trim($_SERVER['PATH_INFO'], '/'));

switch ($path[0]) {
    case 'produtos':
        $produto = new Produto($db);
        switch ($requestMethod) {
            case 'GET':
                if (isset($path[1])) {
                    $result = $produto->readOne($path[1]);
                    echo json_encode($result);
                } else {
                    $result = $produto->read();
                    echo json_encode($result);
                }
                break;
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                $success = $produto->create($data['nome'], $data['descricao'], $data['preco'], $data['estoque'], $data['userInsert']);
                echo json_encode(['success' => $success]);
                break;
            case 'PUT':
                $data = json_decode(file_get_contents("php://input"), true);
                $success = $produto->update($path[1], $data['nome'], $data['descricao'], $data['preco'], $data['estoque'], $data['userInsert']);
                echo json_encode(['success' => $success]);
                break;
            case 'DELETE':
                $data = json_decode(file_get_contents("php://input"), true);
                $success = $produto->delete($path[1], $data['userInsert']);
                echo json_encode(['success' => $success]);
                break;
        }
        break;
    
    case 'logs':
        $log = new Log($db);
        if ($requestMethod === 'GET') {
            $result = $log->read();
            echo json_encode($result);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['message' => 'Endpoint não encontrado.']);
}
?>
