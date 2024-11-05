let cart = [];
let cartCount = 0;

function addToCart(productName, price) {
    cart.push({ name: productName, price: price });
    cartCount++;
    document.getElementById('cart-count').innerText = cartCount;
    alert(`${productName} has been added to your cart!`);
}

function viewCart() {
    const cartModal = document.getElementById('cart-modal');
    const cartItemsDiv = document.getElementById('cart-items');
    
    cartItemsDiv.innerHTML = ''; // Clear existing items
    cart.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.textContent = `${item.name} - $${item.price}`;
        cartItemsDiv.appendChild(itemElement);
    });
    
    cartModal.style.display = 'block';
}

function closeCart() {
    const cartModal = document.getElementById('cart-modal');
    cartModal.style.display = 'none';
}

function filterProducts() {
    const filter = document.getElementById('category-filter').value;
    const products = document.querySelectorAll('.product');
    
    products.forEach(product => {
        if (filter === 'all' || product.getAttribute('data-category') === filter) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

function searchProducts() {
    const searchValue = document.getElementById('search-bar').value.toLowerCase();
    const products = document.querySelectorAll('.product');
    
    products.forEach(product => {
        const productName = product.querySelector('h3').innerText.toLowerCase();
        if (productName.includes(searchValue)) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

function checkout() {
    alert('Proceeding to checkout!');
    closeCart();
}
