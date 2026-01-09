const axios = require('axios');
const fs = require('fs');
const path = require('path');

// Получаем URL сайта из аргументов командной строки
const websiteUrl = process.argv[2];

if (!websiteUrl) {
    console.error('❌ Пожалуйста, укажите URL сайта в качестве параметра:');
    console.error('   node script.js https://example.com/page/');
    process.exit(1);
}

// Функция для получения последних символов из URL перед последним "/"
function getLastCharsFromUrl(url) {
    try {
        const urlObj = new URL(url);
        const pathname = urlObj.pathname;
        
        // Удаляем завершающий слеш если есть
        let cleanPath = pathname.replace(/\/$/, '');
        
        // Если есть путь (не корневая страница)
        if (cleanPath && cleanPath !== '/') {
            // Получаем последнюю часть пути
            const parts = cleanPath.split('/');
            let lastPart = parts[parts.length - 1];
            
            // Берем последние 20 символов (или меньше, если строка короче)
            const charsToTake = Math.min(20, lastPart.length);
            let result = lastPart.slice(-charsToTake);
            
            // Убираем небезопасные символы для имени папки
            result = result.replace(/[^a-zA-Z0-9а-яА-Я_-]/g, '_');
            
            // Если после фильтрации пусто, используем "images"
            if (!result) result = 'images';
            
            return result;
        }
        
        return 'images'; // По умолчанию для корневой страницы
    } catch (error) {
        console.error('Ошибка при разборе URL:', error.message);
        return 'images';
    }
}

// Функция для получения расширения файла из URL
function getFileExtension(imgUrl) {
    // Удаляем query параметры
    const cleanUrl = imgUrl.split('?')[0].split('#')[0];
    
    // Получаем расширение из URL
    const extMatch = cleanUrl.match(/\.(jpg|jpeg|png|gif|webp|bmp|svg|ico|tiff?|jfif|avif)(?:$|\?|#)/i);
    
    if (extMatch && extMatch[1]) {
        return extMatch[1].toLowerCase();
    }
    
    // Если расширение не найдено, пытаемся определить по content-type
    return 'unknown';
}

// Главная функция
async function downloadAllImages() {
    try {
        console.log(`📥 Скачиваю изображения с: ${websiteUrl}`);

        // 1. Получаем HTML страницы
        const response = await axios.get(websiteUrl);
        const html = response.data;

        // 2. Создаем основную папку из последних символов URL
        const folderName = getLastCharsFromUrl(websiteUrl);
        const outputFolder = `./${folderName}`;

        if (!fs.existsSync(outputFolder)) {
            fs.mkdirSync(outputFolder, { recursive: true });
            console.log(`📁 Создана основная папка: ${outputFolder}`);
        }

        // 3. Ищем все картинки в HTML
        const imgRegex = /<img[^>]+src="([^">]+)"/gi;
        const images = [];
        let match;

        while ((match = imgRegex.exec(html)) !== null) {
            images.push(match[1]);
        }

        console.log(`🔍 Найдено картинок: ${images.length}`);

        // 4. Создаем подпапки для разных форматов
        const formatFolders = {};
        const formatCounts = {};

        // 5. Скачиваем каждую картинку
        for (let i = 0; i < images.length; i++) {
            let imgUrl = images[i];

            // Обрабатываем разные форматы URL
            if (imgUrl.startsWith('/')) {
                const urlObj = new URL(websiteUrl);
                imgUrl = urlObj.origin + imgUrl;
            } else if (imgUrl.startsWith('//')) {
                imgUrl = 'https:' + imgUrl;
            } else if (!imgUrl.startsWith('http')) {
                const urlObj = new URL(websiteUrl);
                const baseUrl = urlObj.origin + urlObj.pathname.substring(0, urlObj.pathname.lastIndexOf('/') + 1);
                imgUrl = new URL(imgUrl, baseUrl).href;
            }

            // Получаем расширение файла
            const extension = getFileExtension(imgUrl);
            
            // Создаем папку для этого формата, если еще не создана
            if (!formatFolders[extension]) {
                const formatFolder = path.join(outputFolder, extension);
                if (!fs.existsSync(formatFolder)) {
                    fs.mkdirSync(formatFolder, { recursive: true });
                    console.log(`📂 Создана папка для формата: ${extension}`);
                }
                formatFolders[extension] = formatFolder;
                formatCounts[extension] = 0;
            }

            // Формируем имя файла
            formatCounts[extension]++;
            const filename = `image_${formatCounts[extension]}.${extension}`;
            const filepath = path.join(formatFolders[extension], filename);

            try {
                // Скачиваем картинку
                const imgResponse = await axios({
                    method: 'GET',
                    url: imgUrl,
                    responseType: 'stream',
                    headers: {
                        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                    }
                });

                // Проверяем content-type для уточнения расширения
                const contentType = imgResponse.headers['content-type'];
                if (contentType && extension === 'unknown') {
                    let actualExtension = 'unknown';
                    if (contentType.includes('jpeg') || contentType.includes('jpg')) {
                        actualExtension = 'jpg';
                    } else if (contentType.includes('png')) {
                        actualExtension = 'png';
                    } else if (contentType.includes('gif')) {
                        actualExtension = 'gif';
                    } else if (contentType.includes('webp')) {
                        actualExtension = 'webp';
                    }
                    
                    // Обновляем путь файла, если определили расширение
                    if (actualExtension !== 'unknown') {
                        const newFilename = `image_${formatCounts[extension]}.${actualExtension}`;
                        const newFilepath = path.join(outputFolder, actualExtension, newFilename);
                        
                        // Создаем папку для нового формата если нужно
                        if (!formatFolders[actualExtension]) {
                            const formatFolder = path.join(outputFolder, actualExtension);
                            if (!fs.existsSync(formatFolder)) {
                                fs.mkdirSync(formatFolder, { recursive: true });
                            }
                            formatFolders[actualExtension] = formatFolder;
                            formatCounts[actualExtension] = 0;
                        }
                        
                        formatCounts[actualExtension]++;
                        formatCounts[extension]--;
                        
                        // Обновляем переменные
                        const actualFilename = `image_${formatCounts[actualExtension]}.${actualExtension}`;
                        const actualFilepath = path.join(formatFolders[actualExtension], actualFilename);
                        
                        const writer = fs.createWriteStream(actualFilepath);
                        imgResponse.data.pipe(writer);
                        
                        await new Promise((resolve, reject) => {
                            writer.on('finish', resolve);
                            writer.on('error', reject);
                        });
                        
                        console.log(`[${i + 1}/${images.length}] ✓ ${actualExtension}/${actualFilename}`);
                        continue;
                    }
                }

                // Сохраняем в файл
                const writer = fs.createWriteStream(filepath);
                imgResponse.data.pipe(writer);

                // Ждем завершения записи
                await new Promise((resolve, reject) => {
                    writer.on('finish', resolve);
                    writer.on('error', reject);
                });

                console.log(`[${i + 1}/${images.length}] ✓ ${extension}/${filename}`);

            } catch (err) {
                console.log(`[${i + 1}/${images.length}] ✗ Ошибка: ${imgUrl.substring(0, 50)}...`);
            }

            // Задержка чтобы не нагружать сервер
            await new Promise(resolve => setTimeout(resolve, 100));
        }

        // 6. Выводим статистику
        console.log(`\n📊 Статистика скачивания:`);
        console.log(`📁 Основная папка: ${outputFolder}`);
        for (const [format, count] of Object.entries(formatCounts)) {
            if (count > 0) {
                console.log(`   ${format.toUpperCase()}: ${count} файлов`);
            }
        }
        console.log(`\n✅ Готово! Все картинки отсортированы по форматам.`);

    } catch (error) {
        console.error('❌ Ошибка:', error.message);
        if (error.code === 'ENOTFOUND') {
            console.error('   Проверьте подключение к интернету и правильность URL.');
        }
    }
}

// Запускаем
downloadAllImages();