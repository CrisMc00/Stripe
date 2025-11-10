<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <form action="/pagar1" method="POST">
            @csrf
            <button type="submit">Pagar producto de stripe</button>
        </form>
        <br>
        <form action="/pagar2" method="POST">
            @csrf
            <button type="submit">Pagar producto que no esta stripe</button>
        </form>
    </body>
</html>