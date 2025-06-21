<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Gestion Budgétaire' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <?= $additionalStyles ?? '' ?>
</head>
<body>
    <?php include __DIR__ . '/partials/header.php'; ?>
    
    <div class="app-container">
        <?php include __DIR__ . '/partials/sidebar.php'; ?>
        
        <main class="main-content">
            <?php
                $viewPath = __DIR__ . "/pages/{$view}.php";
                if (isset($view) && file_exists($viewPath)) {
                    include $viewPath;
                } 
            ?>
        </main>
    </div>
    
    <script src="/assets/js/main.js"></script>
    <?= $additionalScripts ?? '' ?>
</body>
</html>