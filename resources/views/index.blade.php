<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
        <form action="/checkout" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <button type="submit">Pagar</button>
        </form>
        
        <form action="/checkout2" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <button type="submit">Pagar producto fijo</button>
        </form>
    </body>
</html>