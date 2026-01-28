const express = require('express');
const axios = require('axios');
const cheerio = require('cheerio');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

// Middleware для логирования
app.use((req, res, next) => {
    console.log(`[${new Date().toISOString()}] ${req.method} ${req.url}`);
    next();
});

app.post('/parse-html', async (req, res) => {
    console.log('Получен запрос:', req.body);
    
    try {
        const { url } = req.body;
        
        if (!url) {
            console.log('Ошибка: URL не указан');
            return res.status(400).json({ 
                success: false,
                error: 'URL не указан' 
            });
        }
        
        // Проверяем валидность URL
        try {
            new URL(url);
        } catch (e) {
            console.log('Ошибка: Неверный формат URL', url);
            return res.status(400).json({ 
                success: false,
                error: 'Неверный формат URL' 
            });
        }
        
        console.log('Загружаем страницу:', url);
        
        // Загружаем страницу
        const response = await axios.get(url, {
            headers: {
                'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language': 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7'
            },
            timeout: 15000,
            maxRedirects: 5
        });
        
        console.log('Статус ответа:', response.status);
        console.log('Длина контента:', response.data.length);
        
        const $ = cheerio.load(response.data);
        
        // Ищем блок с характеристиками
        const specBlock = $('.specifications__info');
        console.log('Найдено блоков .specifications__info:', specBlock.length);
        
        if (!specBlock.length) {
            // Попробуем найти альтернативные блоки
            const alternativeBlocks = $('[class*="specification"], [class*="characteristic"], [class*="param"]');
            console.log('Альтернативные блоки:', alternativeBlocks.length);
            
            return res.status(404).json({ 
                success: false,
                error: 'Блок .specifications__info не найден на странице',
                debug: {
                    title: $('title').text().trim(),
                    url: url,
                    foundBlocks: specBlock.length,
                    alternativeBlocks: alternativeBlocks.length
                }
            });
        }
        
        const result = {
            'Основные характеристики': {},
            'Дополнительные характеристики': {}
        };
        
        // Парсим блоки характеристик
        specBlock.find('.specifications__block').each((i, block) => {
            const $block = $(block);
            const title = $block.find('h3').text().trim();
            
            console.log('Найден блок:', title);
            
            if (!result[title]) {
                console.log('Пропускаем неизвестный блок:', title);
                return;
            }
            
            $block.find('li').each((j, li) => {
                const $li = $(li);
                const key = $li.find('.product-inner-info__list-title span').text().trim();
                const value = $li.find('.product-inner-info__list-description').text().trim();
                
                if (key) {
                    result[title][key] = value;
                    console.log('Добавлена характеристика:', key, '=', value);
                }
            });
        });
        
        console.log('Результат парсинга:', {
            main: Object.keys(result['Основные характеристики']).length,
            extra: Object.keys(result['Дополнительные характеристики']).length
        });
        
        res.json({
            success: true,
            data: result,
            stats: {
                main: Object.keys(result['Основные характеристики']).length,
                extra: Object.keys(result['Дополнительные характеристики']).length
            },
            debug: {
                url: url,
                totalBlocks: specBlock.find('.specifications__block').length
            }
        });
        
    } catch (error) {
        console.error('Ошибка парсинга:');
        console.error('Сообщение:', error.message);
        console.error('Код:', error.code);
        console.error('Статус:', error.response?.status);
        
        let errorMessage = error.message;
        let errorDetails = {};
        
        if (error.code === 'ENOTFOUND') {
            errorMessage = 'Не удалось найти указанный сайт';
        } else if (error.code === 'ECONNREFUSED') {
            errorMessage = 'Соединение отклонено';
        } else if (error.code === 'ETIMEDOUT') {
            errorMessage = 'Таймаут соединения';
        } else if (error.response) {
            errorMessage = `Ошибка HTTP ${error.response.status}`;
            errorDetails.status = error.response.status;
        }
        
        res.status(500).json({ 
            success: false,
            error: errorMessage,
            details: errorDetails,
            stack: process.env.NODE_ENV === 'development' ? error.stack : undefined
        });
    }
});

// Обработчик для проверки работоспособности сервера
app.get('/health', (req, res) => {
    res.json({ 
        status: 'ok',
        service: 'HTML Parser',
        timestamp: new Date().toISOString()
    });
});

// Обработка несуществующих маршрутов
app.use((req, res) => {
    res.status(404).json({ 
        success: false,
        error: 'Маршрут не найден' 
    });
});

// Обработка ошибок
app.use((err, req, res, next) => {
    console.error('Необработанная ошибка:', err);
    res.status(500).json({ 
        success: false,
        error: 'Внутренняя ошибка сервера',
        message: err.message 
    });
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`========================================`);
    console.log(`🚀 Парсер запущен на порту ${PORT}`);
    console.log(`📡 Проверка сервера: http://localhost:${PORT}/health`);
    console.log(`📤 Эндпоинт парсинга: POST http://localhost:${PORT}/parse-html`);
    console.log(`========================================`);
});