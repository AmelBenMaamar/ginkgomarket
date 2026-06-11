<?php require_once '../app/Views/layout/header.php'; ?>

<section class="hero">
    <h1>Bienvenue sur GinkGoMarket</h1>
    <p>Produits naturels, vivants et durables.</p>
    <a href="/?url=product" class="btn">Voir la boutique</a>
</section>

<section class="featured">
    <h2>Nouveautés</h2>
    <div class="grid">
        <?php foreach ($products as $p): ?>
        <div class="card">
            <img src="/uploads/<?= htmlspecialchars($p['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            <h3><?= htmlspecialchars($p['name']) ?></h3>
            <p><?= number_format($p['price'], 2) ?> €</p>
            <a href="/?url=product/show/<?= $p['id'] ?>" class="btn">Voir</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
