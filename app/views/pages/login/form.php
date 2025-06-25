<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .login-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .divider {
            text-align: center;
            margin: 1rem 0;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        
        <!-- Formulaire de connexion utilisateur -->
        <form action="/user/login" method="post">
            <div class="form-group">
                <label for="login">Nom d'utilisateur</label>
                <input type="text" name="login" id="login" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" class="btn-primary">Se connecter (Utilisateur)</button>
        </form>
        
        <div class="divider">ou</div>
        
        <!-- Formulaire de connexion client -->
        <form action="/client/login" method="post">
            <div class="form-group">
                <label for="client_email">Email client</label>
                <input type="text" name="client_email" id="client_email" required>
            </div>
            <div class="form-group">
                <label for="client_password">Mot de passe</label>
                <input type="password" name="client_password" id="client_password" required>
            </div>
            <button type="submit" class="btn-secondary">Se connecter en tant que client</button>
        </form>
    </div>
</body>
</html>