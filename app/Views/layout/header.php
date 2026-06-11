<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'GinkGoMarket' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header>
    <nav>
        <a href="/" class="logo">🌿 GinkGoMarket</a>
        <ul>
            <li><a href="/?url=product">Boutique</a></li>
            <li>
                <a href="/?url=cart" class="cart-link">
                    Panier
                    <?php
                    $cartCount = array_sum($_SESSION['cart'] ?? []);
                    if ($cartCount > 0): ?>
                    <span class="cart-badge"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php if (!empty($_SESSION['user'])): ?>
                <li><a href="/?url=user/account">Mon compte</a></li>
            <?php else: ?>
                <li><a href="/?url=user/login">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
