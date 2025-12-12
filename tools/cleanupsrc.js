const { JSDOM } = require('jsdom');
const fs = require('fs');

// Читаем HTML
const html = fs.readFileSync('../index-updated.html', 'utf-8');
const dom = new JSDOM(html);
const document = dom.window.document;

// Выбираем только <img> теги с атрибутом src
const images = document.querySelectorAll('img[src]');

console.log(`Найдено изображений: ${images.length}`);

// Очищаем все src у изображений
images.forEach((img, index) => {
    const oldSrc = img.getAttribute('src');
    
    // Сохраняем оригинальный src в data-атрибут (опционально)
    img.setAttribute('data-original-src', oldSrc);
    
    // Очищаем src
    img.setAttribute('src', '');
    
    // Добавляем alt текст для пустых изображений (опционально)
    if (!img.hasAttribute('alt')) {
        img.setAttribute('alt', 'Изображение удалено');
    }
    
    console.log(`${index + 1}. "${oldSrc}" → ""`);
});

// Сохраняем результат
const outputFile = '../index-no-images.html';
fs.writeFileSync(outputFile, dom.serialize());

console.log(`\n✅ Готово! Изменено изображений: ${images.length}`);
console.log(`📁 Файл сохранён: ${outputFile}`);