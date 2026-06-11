<?php require_once '../app/Views/layout/header.php'; ?>

<section class="cart">
    <h1>Mon panier</h1>

    <?php if (empty($cart)): ?>
        <p class="empty-cart">Votre panier est vide. <a href="/?url=product">Continuer mes achats</a></p>
    <?php else: ?>

    <table class="cart-table">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix unitaire</th>
                <th>Quantité</th>
                <th>Sous-total</th>
                <th>Supprimer</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td>
                    <img src="/uploads/<?= htmlspecialchars($p['image'] ?? 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                    <?= htmlspecialchars($p['name']) ?>
                </td>
                <td><?= number_format($p['price'], 2) ?> €</td>
                <td>
                    <form action="/?url=cart/update" method="POST">
                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                        <input type="number" name="quantity" value="<?= $cart[$p['id']] ?>" min="1" max="<?= $p['stock'] ?>">
                        <button type="submit" class="btn-small">Mettre à jour</button>
                    </form>
                </td>
                <td><?= number_format($p['price'] * $cart[$p['id']], 2) ?> €</td>
                <td>
                    <form action="/?url=cart/remove" method="POST">
                        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                        <button type="submit" class="btn-danger">✕</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td colspan="2"><strong><?= number_format($total, 2) ?> €</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="cart-actions">
        <form action="/?url=cart/clear" method="POST">
            <button type="submit" class="btn-danger">Vider le panier</button>
        </form>
        <a href="/?url=checkout" class="btn">Passer la commande →</a>
    </div>

    <?php endif; ?>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
