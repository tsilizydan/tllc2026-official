<?php
/**
 * TSILIZY LLC — Maintenance Page
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance | <?= SITE_NAME ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .text-gold-gradient {
            background: linear-gradient(135deg, #C9A227 0%, #FDE68A 50%, #C9A227 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans min-h-screen flex items-center justify-center">
    <div class="text-center max-w-lg px-4">
        <div class="w-20 h-20 bg-gradient-to-br from-yellow-500 to-yellow-700 rounded-2xl flex items-center justify-center mx-auto mb-8">
            <i class="fas fa-tools text-3xl text-gray-950"></i>
        </div>
        <h1 class="text-4xl font-serif font-bold text-gold-gradient mb-4">Maintenance en cours</h1>
        <p class="text-gray-400 text-lg mb-8">
            Notre site est actuellement en maintenance. Nous reviendrons bientôt avec des améliorations.
        </p>
        <p class="text-gray-500 text-sm">
            <i class="fas fa-envelope mr-2"></i> <?= SITE_EMAIL ?>
        </p>
    </div>
</body>
</html>
