<?php
session_start();
require_once 'config.php';
require_once 'products.php';

class CartManager {
    private $pdo;
    private $productManager;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->productManager = new ProductManager($pdo);
    }

    public function addToCart($productId, $quantity = 1) {
        $product = $this->productManager->getProductById($productId);
        
        if (!$product) {
            return ['success' => false, 'message' => 'Produto não encontrado'];
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if product already in cart
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $productId) {
                $item['quantity'] += $quantity;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity
            ];
        }

        return ['success' => true, 'message' => 'Produto adicionado ao carrinho'];
    }

    public function removeFromCart($productId) {
        if (isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array_filter($_SESSION['cart'], function($item) use ($productId) {
                return $item['id'] != $productId;
            });
        }
        return ['success' => true, 'message' => 'Produto removido do carrinho'];
    }

    public function getCart() {
        return $_SESSION['cart'] ?? [];
    }

    public function clearCart() {
        $_SESSION['cart'] = [];
        return ['success' => true, 'message' => 'Carrinho limpo'];
    }

    public function calculateTotal() {
        $total = 0;
        foreach ($_SESSION['cart'] ?? [] as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}

// Handle AJAX requests
if (isset($_GET['action'])) {
    $cartManager = new CartManager($pdo);

    switch ($_GET['action']) {
        case 'add':
            $productId = $_GET['productId'] ?? null;
            $quantity = $_GET['quantity'] ?? 1;
            echo json_encode($cartManager->addToCart($productId, $quantity));
            break;
        
        case 'remove':
            $productId = $_GET['productId'] ?? null;
            echo json_encode($cartManager->removeFromCart($productId));
            break;
        
        case 'get':
            echo json_encode($cartManager->getCart());
            break;
        
        case 'total':
            echo json_encode(['total' => $cartManager->calculateTotal()]);
            break;
    }
}
?>
