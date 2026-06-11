<?php require_once '../app/Views/layout/header.php'; ?>

<article class="product-detail">
    <img src="/ginkgomarket/uploads/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    <div class="product-info">
        <span class="category"><?= htmlspecialchars($product['category_name'] ?? '') ?></span>
        <h1><?= htmlspecialchars($product['name']) ?></h1>
        <p class="price"><?= number_format($product['price'], 2) ?> €</p>
        <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        <p class="stock"><?= $product['stock'] > 0 ? 'En stock (' . $product['stock'] . ' disponibles)' : '<span class="rupture">Rupture de stock</span>' ?></p>
        <?php if ($product['stock'] > 0): ?>
        <form action="/ginkgomarket/public/cart/add" method="POST">
            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
            <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock'] ?>">
            <button type="submit" class="btn">Ajouter au panier</button>
        </form>
        <?php endif; ?>
    </div>
</article>

<?php require_once '../app/Views/layout/footer.php'; ?>
