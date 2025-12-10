// extract-classes.js
// Скрипт для отображения иерархии DOM с ID и классами элементов

const fs = require('fs');
const { JSDOM } = require('jsdom');

// Рекурсивная функция для обхода DOM-дерева
function traverseElement(element, depth, lines) {
	// Формируем отступ в зависимости от глубины
	const indent = '\t'.repeat(depth);
	
	// Получаем имя тега
	const tagName = element.tagName.toLowerCase();
	
	// Получаем ID элемента
	const elementId = element.id;
	
	// Получаем и нормализуем строку классов
	const classString = element.className;
	const trimmedClasses = (typeof classString === 'string') 
		? classString.trim() 
		: '';

	// Начинаем формировать строку с тегом и отступом
	let line = `${indent}${tagName}`;
	
	// Добавляем ID с пробелом перед #, если он есть
	if (elementId) {
		line += ` #${elementId}`;
	}
	
	// Добавляем классы с пробелом перед ., если они есть
	if (trimmedClasses) {
		line += ` .${trimmedClasses}`;
	}
	
	// Добавляем строку в массив результатов (ВСЕГДА, даже если нет атрибутов)
	lines.push(line);
	
	// Рекурсивно обходим всех детей элемента
	const children = element.children;
	for (let i = 0; i < children.length; i++) {
		traverseElement(children[i], depth + 1, lines);
	}
}

// Основная функция
function extractDOMStructure() {
	try {
		// Чтение и парсинг HTML
		const htmlContent = fs.readFileSync('../index.html', 'utf-8');
		const dom = new JSDOM(htmlContent);
		const document = dom.window.document;
		
		// Массив для сбора строк результата
		const resultLines = [];
		
		// Начинаем обход с корневого элемента html
		const rootElement = document.documentElement;
		traverseElement(rootElement, 0, resultLines);
		
		// Формируем статистику
		const elementsWithId = resultLines.filter(line => line.includes('#')).length;
		const elementsWithClasses = resultLines.filter(line => line.includes('.')).length;
		const elementsPlain = resultLines.length - elementsWithId - elementsWithClasses;
		
		// Формируем содержимое для файла
		const header = `Полная структура DOM из index.html:\n`;
		const stats = `Всего элементов: ${resultLines.length}\n` +
					  `С ID: ${elementsWithId}\n` +
					  `С классами: ${elementsWithClasses}\n` +
					  `Без атрибутов: ${elementsPlain}\n`;
		const separator = '='.repeat(60) + '\n';
		
		const fileContent = header + stats + separator + resultLines.join('\n');
		
		// Записываем в файл
		const outputFileName = 'dom-complete.txt';
		fs.writeFileSync(outputFileName, fileContent, 'utf-8');
		
		// Выводим отчет в консоль
		console.log(header + stats + separator);
		console.log('Структура DOM (первые 30 элементов):\n');
		
		// Выводим только начало для предварительного просмотра
		const previewLines = resultLines.slice(0, 30);
		previewLines.forEach(line => console.log(line));
		
		if (resultLines.length > 30) {
			console.log(`\n... и ещё ${resultLines.length - 30} элементов`);
		}
		
		console.log(`\nПолная структура сохранена в файл: ${outputFileName}`);
		
		// Дополнительная информация
		console.log('\nФормат строк:');
		console.log('\tdiv #header .container - тег с ID и классами');
		console.log('\tp .text - тег только с классами');
		console.log('\tul #menu - тег только с ID');
		console.log('\tli - тег без ID и классов');
		
	} catch (error) {
		console.error('Произошла ошибка:');
		if (error.code === 'ENOENT') {
			console.error('\t- Файл index.html не найден в текущей папке.');
		} else {
			console.error('\t- Проверьте валидность HTML или права доступа.');
		}
		console.error(`\tТехническая информация: ${error.message}`);
		process.exit(1);
	}
}

// Запуск
extractDOMStructure();