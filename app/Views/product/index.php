<?php require_once '../app/Views/layout/header.php'; ?>

<section class="catalogue">
    <h1>Boutique</h1>
    <div class="grid">
        <?php foreach ($products as $p): ?>
        <div class="card">
            <img src="/ginkgomarket/uploads/<?= htmlspecialchars($p['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($p['name']) ?>">
            <span class="category"><?= htmlspecialchars($p['category_name'] ?? '') ?></span>
            <h3><?= htmlspecialchars($p['name']) ?></h3>
            <p class="price"><?= number_format($p['price'], 2) ?> €</p>
            <p class="stock"><?= $p['stock'] > 0 ? 'En stock' : '<span class="rupture">Rupture</span>' ?></p>
            <a href="/?url=product/show/<?= $p['id'] ?>" class="btn">Voir le produit</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
