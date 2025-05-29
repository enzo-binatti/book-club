// Cart Functionality
function addToCart(productId, quantity = 1) {
    fetch(`cart.php?action=add&productId=${productId}&quantity=${quantity}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateCartCount();
                alert('Produto adicionado ao carrinho!');
            } else {
                alert(data.message);
            }
        });
}

function updateCartCount() {
    fetch('cart.php?action=get')
        .then(response => response.json())
        .then(cart => {
            const cartCount = cart.reduce((total, item) => total + item.quantity, 0);
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = cartCount;
            }
        });
}

// Search Functionality
function searchProducts() {
    const searchInput = document.querySelector('.search-bar input');
    const query = searchInput.value;

    fetch(`products.php?action=search&query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(products => {
            const gallery = document.getElementById('productGallery');
            gallery.innerHTML = ''; // Clear existing products

            products.forEach(product => {
                const productElement = createProductElement(product);
                gallery.appendChild(productElement);
            });
        });
}

function createProductElement(product) {
    const div = document.createElement('div');
    div.className = 'product-item';
    div.innerHTML = `
        <span class="product-badge">${product.badge}</span>
        <div class="product-image">
            <a href="produto.php?id=${product.id}">
                <img src="${product.image}" alt="${product.name}">
            </a>
        </div>
        <div class="product-details">
            <h3>${product.name}</h3>
            <p class="product-price">R$ ${product.price.toFixed(2).replace('.', ',')}</p>
            <div class="product-actions">
                <button onclick="addToCart(${product.id})">
                    <i class="fas fa-shopping-cart"></i> Adicionar
                </button>
            </div>
        </div>
    `;
    return div;
}

// Newsletter Subscription
function subscribeNewsletter(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch('newsletter.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            form.reset();
        }
    });
}

// Navbar Scroll Effect
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Initialize cart count on page load
document.addEventListener('DOMContentLoaded', updateCartCount);
