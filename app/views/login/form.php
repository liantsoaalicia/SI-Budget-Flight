<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="/user/login" method="post">
        <label for="login">Nom d'utilisateur <input type="text" name="login" id="login"></label>
        <label for="password">Mot de passe <input type="password" name="password" id="password"></label>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>