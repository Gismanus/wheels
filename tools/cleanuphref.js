const { JSDOM } = require('jsdom');
const fs = require('fs');

const dom = new JSDOM(fs.readFileSync('../drafts/good-min.html', 'utf-8'));
const doc = dom.window.document;

// Очистка всех href и src
doc.querySelectorAll('[href], [src], [data-lazy]').forEach(el => {
    if (el.hasAttribute('href')) el.setAttribute('href', '/');
    if (el.hasAttribute('data-lazy')) el.setAttribute('data-lazy', '/');
    if (el.hasAttribute('src')) el.setAttribute('src', '');
});

// Удаление атрибутов content

fs.writeFileSync('../index-updated.html', dom.serialize());