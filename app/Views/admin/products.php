<?php require_once '../app/Views/layout/header.php'; ?>

<section class="admin">
    <div class="admin-header">
        <h1>Produits</h1>
        <a href="/?url=admin/productCreate" class="btn">+ Nouveau produit</a>
    </div>

    <table class="cart-table">
        <thead>
            <tr><th>Image</th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Stock</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td>
                    <?php if ($p['image']): ?>
                    <img src="/uploads/<?= htmlspecialchars($p['image']) ?>" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                    <?php else: ?>—<?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['category_name'] ?? '—') ?></td>
                <td><?= number_format($p['price'], 2) ?> €</td>
                <td><?= $p['stock'] ?></td>
                <td class="actions">
                    <a href="/?url=admin/productEdit&id=<?= $p['id'] ?>" class="btn-small">Modifier</a>
                    <form action="/?url=admin/productDelete" method="POST" style="display:inline">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" class="btn-danger" onclick="return confirm('Supprimer ce produit ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
