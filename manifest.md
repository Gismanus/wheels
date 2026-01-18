# 🛒 Магазин инвалидных колясок — Project Manifest

## 📁 Структура проекта
/
├── index.php # Главная (компоненты)
├── catalog.php # Каталог (fetch-products.js)
├── product.php # Товар (динамический по ?id=)
├── cart.php # Корзина (cart.js + localStorage)
├── about.php # О нас
├── admin.php # Админка (HTTP auth)
├── submit.php # Обработчик форм → feedback.json
│
├── components/ # PHP-компоненты
│ ├── head.php # Мета, подключение CSS/JS
│ ├── header.php # Шапка (лого, меню, корзина)
│ ├── footer.php # Подвал
│ ├── main.php # Контент главной
│ └── feedback-popup.php # Попап формы обратной связи
│
├── styles/ # Модульные стили
│ ├── base.css # Глобальные стили, сброс, утилиты
│ ├── about-page.css # Страница "О нас"
│ ├── cart-page.css # Страница корзины
│ ├── catalog-page.css # Страница каталога
│ ├── features.css # Блок "Преимущества"
│ ├── feedback.css # Форма обратной связи
│ ├── footer.css # Подвал
│ ├── hero.css # Герой-блок
│ ├── partners.css # Партнёры
│ ├── product-page.css # Страница товара
│ ├── products.css # Блок товаров на главной
│ └── response.css # Адаптив (медиазапросы)
│
├── js/ # JavaScript модули
│ ├── fetch-products.js # Рендер каталога из products.json
│ ├── cart.js # Логика корзины (addToCart, updateCounter)
│ └── [jQuery.js] # (опционально)
│
├── images/ # Изображения (товары, лого, иконки)
├── fonts/ # Montserrat (18 начертаний, fonts.css)
└── products.json # Все товары с полными характеристиками

text

## 🔧 Технологический стек
- **Frontend**: HTML5, CSS3 (Grid/Flex), Vanilla JS + Fetch API
- **Backend**: PHP 8.1 (компоненты, обработка форм)
- **Сервер**: Nginx + PHP-FPM
- **Хранение данных**:
  - JSON (`products.json`, `feedback.json`)
  - localStorage (корзина)
- **Адаптив**: Mobile-first, бургер-меню

## 🚀 Ключевые возможности
1. **Каталог товаров** — сетка карточек, загрузка из `products.json`
2. **Страница товара** — динамическая по `?id=`, характеристики в `<details>`
3. **Корзина** — добавление/удаление, счётчик в хедере
4. **Формы** — обратный звонок, отправка в `feedback.json`
5. **Админка** — просмотр заявок с HTTP-аутентификацией
6. **Адаптив** — мобильное меню, резиновые сетки

## 🔄 Динамические элементы
- **Каталог**: `fetch('products.json')` → рендер карточек
- **Товар**: `product.php?id=1` → выбор из JSON → рендер
- **Корзина**: `localStorage.cart` → обновление счётчика
- **Характеристики товара**: вложенный JSON с группировкой

## 🎨 Стилевая система
- **Цвета**:
  - Акцент: `#A7E7FE`
  - Текст: `#2C3E50`
  - Фон: `#F8FCFE`
- **Шрифт**: Montserrat (18 начертаний, подключён через `fonts.css`)
- **Контейнер**: `.container { width: 1280px; margin: 0 auto; }`
- **Адаптив**: медиазапросы в `response.css` и отдельных модулях

## ⚙️ Конфигурация сервера
- **Nginx**: обработка PHP через FPM, `auth_basic` для `admin.php`
- **Права**: `feedback.json` владелец `www-data:www-data`
- **Пути**: корень сайта → `/wheels/`

## 🧩 Компоненты PHP
```php
<?php
$page_title = 'Заголовок';
include 'components/head.php';
include 'components/header.php';
?>
<main>...</main>
<?php include 'components/footer.php'; ?>