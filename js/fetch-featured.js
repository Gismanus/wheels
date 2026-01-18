// fetch-featured.js
$(document).ready(function() {
    const container = document.getElementById('featured-products');
    if (!container) return;
    
    fetch('products.json')
        .then(res => res.json())
        .then(products => {
            // Фильтруем featured товары
            const featured = products.filter(p => p.featured);
            if (!featured.length) return;
            
            // Генерируем HTML
            container.innerHTML = featured.map(product => `
                <div class="product-card" data-id="${product.id}">
                    <img src="${product.image}" 
                         alt="${product.title}" 
                         class="product-card__image"
                         style="background: #fff; object-fit: contain;">
                    <h3>${product.title}</h3>
                    <p>${product.description}</p>
                </div>
            `).join('');
            
            // Можно добавить обработчик клика для перехода на страницу товара
            container.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function() {
                    const id = this.dataset.id;
                    window.location.href = `product.php?id=${id}`;
                });
            });
        })
        .catch(error => console.error('Ошибка загрузки товаров:', error));
});