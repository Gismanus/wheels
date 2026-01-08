const axios = require('axios');
const fs = require('fs');
const path = require('path');

// Конфигурация
const websiteUrl = 'https://armed.ru/product/kostyli_kr925l_armed_c_ups/'; // ЗАМЕНИТЕ на нужный сайт
const outputFolder = './kostyli_kr925l_armed_c_ups';

// Создаем папку для картинок
if (!fs.existsSync(outputFolder)) {
    fs.mkdirSync(outputFolder, { recursive: true });
    console.log(`Создана папка: ${outputFolder}`);
}

// Главная функция
async function downloadAllImages() {
    try {
        console.log(`Скачиваю изображения с: ${websiteUrl}`);

        // 1. Получаем HTML страницы
        const response = await axios.get(websiteUrl);
        const html = response.data;

        // 2. Ищем все картинки в HTML (простой поиск по тегам)
        const imgRegex = /<img[^>]+src="([^">]+)"/g;
        const images = [];
        let match;

        while ((match = imgRegex.exec(html)) !== null) {
            images.push(match[1]);
        }

        console.log(`Найдено картинок: ${images.length}`);

        // 3. Скачиваем каждую картинку
        for (let i = 0; i < images.length; i++) {
            let imgUrl = images[i];

            // Если ссылка относительная (/images/photo.jpg)
            if (imgUrl.startsWith('/')) {
                const urlObj = new URL(websiteUrl);
                imgUrl = urlObj.origin + imgUrl;
            }
            // Если ссылка без протокола (//site.com/image.jpg)
            else if (imgUrl.startsWith('//')) {
                imgUrl = 'https:' + imgUrl;
            }
            // Если ссылка относительная без слэша (images/photo.jpg)
            else if (!imgUrl.startsWith('http')) {
                imgUrl = websiteUrl + '/' + imgUrl;
            }

            // Определяем расширение файла
            let ext = '.jpg';
            if (imgUrl.includes('.png')) ext = '.png';
            else if (imgUrl.includes('.gif')) ext = '.gif';
            else if (imgUrl.includes('.webp')) ext = '.webp';

            const filename = `image_${i}${ext}`;
            const filepath = path.join(outputFolder, filename);

            try {
                // Скачиваем картинку
                const imgResponse = await axios({
                    method: 'GET',
                    url: imgUrl,
                    responseType: 'stream'
                });

                // Сохраняем в файл
                const writer = fs.createWriteStream(filepath);
                imgResponse.data.pipe(writer);

                // Ждем завершения записи
                await new Promise((resolve, reject) => {
                    writer.on('finish', resolve);
                    writer.on('error', reject);
                });

                console.log(`[${i + 1}/${images.length}] ✓ ${filename}`);

            } catch (err) {
                console.log(`[${i + 1}/${images.length}] ✗ Ошибка: ${imgUrl}`);
            }

            // Маленькая задержка, чтобы не нагружать сервер
            await new Promise(resolve => setTimeout(resolve, 100));
        }

        console.log(`\n✅ Готово! Все картинки в папке: ${outputFolder}`);

    } catch (error) {
        console.error('Ошибка:', error.message);
    }
}

// Запускаем
downloadAllImages();