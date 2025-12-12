document.querySelector('a.header-basket').addEventListener('click', function(e) {
    console.log('lol');
    e.preventDefault();
    
    fetch('/ajax_handler.php')
    .then(response => response.json())  // скобки!
    .then(data => {
        document.querySelector('.content-box').innerHTML = data.content;
    })
});