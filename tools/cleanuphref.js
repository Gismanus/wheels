const { JSDOM } = require('jsdom');
const fs = require('fs');

const dom = new JSDOM(fs.readFileSync('../drafts/cart.html', 'utf-8'));
const doc = dom.window.document;

// Очистка всех href и src
doc.querySelectorAll('[href], [src]').forEach(el => {
    if (el.hasAttribute('href')) el.setAttribute('href', '/');
    if (el.hasAttribute('src')) el.setAttribute('src', '');
});

// Удаление атрибутов content
doc.querySelectorAll('[content]').forEach(el => {
    el.removeAttribute('content');
});

// Удаление всех meta с content
doc.querySelectorAll('meta[content]').forEach(meta => {
    meta.removeAttribute('content');
});

fs.writeFileSync('../index-updated.html', dom.serialize());