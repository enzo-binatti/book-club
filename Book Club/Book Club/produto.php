<?php
require_once 'config.php';

class ProductManager {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllProducts() {
        $stmt = $this->pdo->query("SELECT * FROM books");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchProducts($query) {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE name LIKE :query");
        $stmt->execute(['query' => "%$query%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// If this file is directly accessed
if (isset($_GET['action'])) {
    $productManager = new ProductManager($pdo);

    switch ($_GET['action']) {
        case 'search':
            $query = $_GET['query'] ?? '';
            $results = $productManager->searchProducts($query);
            echo json_encode($results);
            break;
        
        case 'list':
            $products = $productManager->getAllProducts();
            echo json_encode($products);
            break;
    }
}
?>
