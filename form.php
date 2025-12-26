<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="/js/jQuery.js"></script>


</head>

<body>
    <form method="post">
        <label for="fname">First Name</label>
        <input type="text" id="name" name="name"><br>
        <label for="lname">Last Name</label>
        <input type="text" id="surname" name="surname">
        <input type="submit" value="Submit">
    </form>
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(event) {
                event.preventDefault();
                let data;
                $.post("/form2.php", $(this).serialize(), function(data) {
                    console.log(data);
                    $('.res').html(data.Name);
                    $('.res').html(data.Name);
                }, 'json');

            });
        });
    </script>
    <div class="res"></div>
</body>

</html>