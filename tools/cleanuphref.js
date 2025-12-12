const { JSDOM } = require('jsdom');
const fs = require('fs');
const dom = new JSDOM(fs.readFileSync('../catalog.html', 'utf-8'));
dom.window.document.querySelectorAll('a[href^="/"]').forEach(a => a.href = "/");
fs.writeFileSync('../index-updated.html', dom.serialize());