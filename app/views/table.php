<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Status Dolibarr</title>
    <style>
        table { border-collapse: collapse; width: 50%; margin: 30px auto; }
        th, td { border: 1px solid #aaa; padding: 8px 12px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">État du Webservice Dolibarr</h2>
    <?php if (isset($error)): ?>
        <p style="color:red;text-align:center;"><?php echo htmlspecialchars($error); ?></p>
    <?php else: ?>
        <table>
            <thead>
                <tr><th>Clé</th><th>Valeur</th></tr>
            </thead>
            <tbody>
                <?php foreach ($data as $key => $value): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($key); ?></td>
                        <td><?php echo htmlspecialchars((string)$value); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
