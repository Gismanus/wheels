fetch('products.json')
    .then(res => res.json())
    .then(products => {
        // Берём первый товар (позже можно по ID из URL)
        const product = products[0];

        // Заполняем контент
        document.querySelector('.product-page__title').textContent = product.title;
        document.querySelector('.product-page__price').textContent = product.price;
        document.querySelector('.product-page__description p').textContent = product.description;
        document.querySelector('.product-page__main-image').src = product.image;

        // Особенности
        const list = document.querySelector('.product-page__features ul');
        list.innerHTML = product.features.map(f => `<li>${f}</li>`).join('');

        // Миниатюры (если есть в JSON)
        if (product.thumbnails) {
            const thumbsContainer = document.querySelector('.product-page__thumbnails');
            thumbsContainer.innerHTML = product.thumbnails.map(src =>
                `<img src="${src}" alt="Миниатюра">`
            ).join('');
        }
    })
    .catch(err => console.error('Ошибка загрузки товара:', err));