<?php
require_once 'config.php';
require_once 'products.php';
require_once 'cart.php';

$productManager = new ProductManager($pdo);
$cartManager = new CartManager($pdo);
$products = $productManager->getAllProducts();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Club</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <!-- Navbar (same as original HTML) -->
    <nav class="navbar">
        <!-- Navbar content from original HTML -->
    </nav>

    <!-- Hero Section (same as original HTML) -->
    <section class="hero">
        <!-- Hero content from original HTML -->
    </section>

    <!-- Featured Products Section -->
    <section class="featured" id="products">
        <div class="container">
            <div class="section-title">
                <h2>Nossos Best-sellers</h2>
                <p>Os livros mais amados pelos nossos leitores</p>
            </div>
            <div class="product-gallery" id="productGallery">
                <?php foreach ($products as $product): ?>
                <div class="product-item" data-name="<?= htmlspecialchars($product['name']) ?>" data-price="<?= $product['price'] ?>">
                    <span class="product-badge"><?= htmlspecialchars($product['badge']) ?></span>
                    <div class="product-image">
                        <a href="produto.php?id=<?= $product['id'] ?>">
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        </a>
                    </div>
                    <div class="product-details">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="product-price">R$ <?= number_format($product['price'], 2, ',', '.') ?></p>
                        <div class="product-actions">
                            <button onclick="addToCart(<?= $product['id'] ?>)">
                                <i class="fas fa-shopping-cart"></i> Adicionar
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter">
        <div class="container">
            <form id="newsletterForm" onsubmit="subscribeNewsletter(event)">
                <input type="email" name="email" placeholder="Seu melhor e-mail" required>
                <button type="submit">Inscrever</button>
            </form>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>
