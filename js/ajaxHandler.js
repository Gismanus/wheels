document.querySelectorAll('a[href^="/"]').forEach(link => {
    link.addEventListener('click', async (e) => {
        e.preventDefault();
        const url = e.target.getAttribute('href');
        
        const response = await fetch(url);
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const content = doc.querySelector('.content').innerHTML;
        
        document.querySelector('.content').innerHTML = content;
        history.pushState(null, '', url); // Обновляем URL
    });
});