// Переключение между страницами
document.addEventListener('DOMContentLoaded', function () {
    const navLinks = document.querySelectorAll('.nav-link');
    const pages = document.querySelectorAll('.page');

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const pageId = this.getAttribute('data-page');

            // Показываем нужную страницу
            pages.forEach(page => {
                if (page.id === pageId
                ) {
                    page.classList.add('active');
                } else {
                    page.classList.remove('active');
                }
            });

            // Обновляем корзину если открыли её
            if (pageId === 'cart') {
                updateCartDisplay();
            }
        });
    });
});