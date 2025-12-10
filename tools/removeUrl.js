const fs = require('fs');
const path = require('path');

const inputFile = process.argv[2];
if (!inputFile) {
    console.log('Укажите файл: node script.js файл.css');
    process.exit(1);
}

const cssContent = fs.readFileSync(inputFile, 'utf8');
let removedUrls = [];

const newContent = cssContent.replace(/url\((["']?)(.*?)\1\)/g, (match, quote, url) => {
    removedUrls.push(url);
    return 'url("")';
});

const baseName = path.basename(inputFile, '.css');
fs.writeFileSync(`${baseName}.css`, newContent);
fs.writeFileSync('test.txt', removedUrls.join('\n'));