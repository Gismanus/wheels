fetch('products.json')
    .then(res => res.json())
    .then(products => {
        // Берём первый товар
        const product = products[0];
        
        // Если есть характеристики
        if (product.characteristics) {
            const chars = product.characteristics;
            
            // Основные характеристики
            if (chars['Основные характеристики']) {
                const mainChars = chars['Основные характеристики'];
                let mainHtml = '';
                
                for (const [key, value] of Object.entries(mainChars)) {
                    if (value) { // Показываем только заполненные
                        mainHtml += `<li><strong>${key}:</strong> ${value}</li>`;
                    }
                }
                
                // Вставляем в нужный блок
                const mainContainer = document.querySelector('.specifications__block:first-child .specifications__list');
                if (mainContainer) {
                    mainContainer.innerHTML = `<ul>${mainHtml}</ul>`;
                }
            }
            
            // Дополнительные характеристики
            if (chars['Дополнительные характеристики']) {
                const extraChars = chars['Дополнительные характеристики'];
                let extraHtml = '';
                
                for (const [key, value] of Object.entries(extraChars)) {
                    if (value) {
                        extraHtml += `<li><strong>${key}:</strong> ${value}</li>`;
                    }
                }
                
                const extraContainer = document.querySelector('.specifications__block:nth-child(2) .specifications__list');
                if (extraContainer) {
                    extraContainer.innerHTML = `<ul>${extraHtml}</ul>`;
                }
            }
        }
        
        // Заполняем основную информацию из Product_creation
        if (product.Product_creation) {
            const info = product.Product_creation.Product_information;
            
            // Название товара
            document.querySelector('.product-page__title').textContent = info.Name || '';
            
            // Цена
            document.querySelector('.product-page__price').textContent = info.Price || '';
            
            // Описание (если есть в JSON)
            if (product.description) {
                document.querySelector('.product-page__description p').textContent = product.description;
            }
            
            // Главное изображение
            if (product.main_image) {
                document.querySelector('.product-page__main-image').src = product.main_image;
            }
            
            // Категория и тип
            if (info['Category_and_type']) {
                // Можно добавить в какой-нибудь блок
                const categoryEl = document.querySelector('.product-page__category');
                if (categoryEl) {
                    categoryEl.textContent = info['Category_and_type'];
                }
            }
            
            // Артикул (SKU)
            if (info.SKU) {
                const skuEl = document.querySelector('.product-page__sku');
                if (skuEl) {
                    skuEl.textContent = `Артикул: ${info.SKU}`;
                }
            }
            
            // Размеры и вес (если нужно показать)
            if (product.Product_creation['Dimensions_and_weight']) {
                const dims = product.Product_creation['Dimensions_and_weight'];
                const dimsText = [];
                
                if (dims.Width) dimsText.push(`Ширина: ${dims.Width}`);
                if (dims.Height) dimsText.push(`Высота: ${dims.Height}`);
                if (dims.Length) dimsText.push(`Длина: ${dims.Length}`);
                if (dims.Weight) dimsText.push(`Вес: ${dims.Weight}`);
                if (dims.Volume) dimsText.push(`Объём: ${dims.Volume}`);
                
                if (dimsText.length > 0) {
                    const dimsContainer = document.querySelector('.product-page__dimensions');
                    if (dimsContainer) {
                        dimsContainer.innerHTML = dimsText.map(text => `<div>${text}</div>`).join('');
                    }
                }
            }
        }
    })
    .catch(err => console.error('Ошибка загрузки товара:', err));