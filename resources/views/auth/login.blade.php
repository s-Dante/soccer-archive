<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Soccer Archive</title>
</head>

<body>
    <x-header/>
    <form action="{{ route('home') }}" method="POST">
        <input type="text" name="name" id="name" placeholder="Jon Doe">
        <input type="email" name="email" id="email" placeholder="ejemplo@algo.com">
        <button type="submit">Entrar</button>
    </form>
</body>

</html>