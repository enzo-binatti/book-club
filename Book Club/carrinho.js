const cart = JSON.parse(localStorage.getItem('cart')) || [];

function renderCart() {
    const cartItemsElement = document.getElementById('cartItems');
    const cartTotalElement = document.getElementById('cartTotal');

    cartItemsElement.innerHTML = '';

    let total = 0;

    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;

        const li = document.createElement('li');
        li.classList.add('cart-item');
        li.innerHTML = `
            <span>${item.name} (R$ ${item.price.toFixed(2)})</span>
            <div class="quantity-controls">
                <button onclick="changeQuantity(${index}, -1)">-</button>
                <span>${item.quantity}</span>
                <button onclick="changeQuantity(${index}, 1)">+</button>
            </div>
        `;
        cartItemsElement.appendChild(li);
    });

    cartTotalElement.textContent = `Total: R$ ${total.toFixed(2)}`;
}

renderCart();