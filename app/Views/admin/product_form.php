<?php require_once '../app/Views/layout/header.php'; ?>

<section class="admin">
    <h1><?= isset($product) ? 'Modifier' : 'Nouveau' ?> produit</h1>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form action="<?= isset($product) ? '/?url=admin/productEdit&id='.$product['id'] : '/?url=admin/productCreate' ?>" method="POST" enctype="multipart/form-data" class="product-form">

        <label>Nom *
            <input type="text" name="name" value="<?= htmlspecialchars($product['name'] ?? '') ?>" required>
        </label>
        <label>Description
            <textarea name="description" rows="4"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
        </label>
        <div class="form-row">
            <label>Prix (€) *
                <input type="number" name="price" step="0.01" min="0" value="<?= $product['price'] ?? '' ?>" required>
            </label>
            <label>Stock
                <input type="number" name="stock" min="0" value="<?= $product['stock'] ?? 0 ?>">
            </label>
            <label>Poids (g)
                <input type="number" name="weight_g" min="0" value="<?= $product['weight_g'] ?? 0 ?>">
            </label>
        </div>
        <label>Catégorie
            <select name="category_id">
                <option value="">— Sans catégorie —</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= (isset($product) && $product['category_id'] == $c['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Photo
            <?php if (!empty($product['image'])): ?>
            <img src="/uploads/<?= htmlspecialchars($product['image']) ?>" style="width:80px;border-radius:6px;margin-bottom:0.5rem;">
            <?php endif; ?>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
        </label>

        <div class="form-actions">
            <a href="/?url=admin/products" class="btn-outline">Annuler</a>
            <button type="submit" class="btn">Enregistrer</button>
        </div>
    </form>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
