function generateNumbers(count) {
    for(let i = 0; i < count; i++) {
        console.log(Math.floor(Math.random() * 900000000) + 100000000);
    }
}

// Пример использования
generateNumbers(5); // Выведет 5 девятизначных чисел