<?php
require_once 'config.php';

try {
    $stmt = $pdo->query("
        SELECT b.*, a.name as author_name 
        FROM books b 
        JOIN authors a ON b.author_id = a.id
    ");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($products);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar produtos: ' . $e->getMessage()]);
}
?>
