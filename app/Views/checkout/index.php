<?php require_once '../app/Views/layout/header.php'; ?>

<section class="checkout">
    <h1>Finaliser ma commande</h1>

    <div class="checkout-grid">

        <div class="checkout-form">
            <h2>Adresse de livraison</h2>
            <form action="/?url=checkout/confirm" method="POST">

                <?php if (!empty($addresses)): ?>
                <div class="saved-addresses">
                    <p><strong>Adresses sauvegardées :</strong></p>
                    <?php foreach ($addresses as $a): ?>
                    <label class="address-option">
                        <input type="radio" name="saved_address" value="<?= $a['id'] ?>">
                        <?= htmlspecialchars($a['rue']) ?>, <?= htmlspecialchars($a['cp']) ?> <?= htmlspecialchars($a['ville']) ?>
                    </label>
                    <?php endforeach; ?>
                    <p class="or-divider">— ou saisir une nouvelle adresse —</p>
                </div>
                <?php endif; ?>

                <label>Rue
                    <input type="text" name="rue" placeholder="12 rue des Oliviers" required>
                </label>
                <label>Code postal
                    <input type="text" name="cp" placeholder="34500" required>
                </label>
                <label>Ville
                    <input type="text" name="ville" placeholder="Béziers" required>
                </label>
                <label>Pays
                    <input type="text" name="pays" value="France">
                </label>

                <button type="submit" class="btn">Confirmer la commande →</button>
            </form>
        </div>

        <div class="checkout-summary">
            <h2>Récapitulatif</h2>
            <table class="cart-table">
                <thead>
                    <tr><th>Produit</th><th>Qté</th><th>Sous-total</th></tr>
                </thead>
                <tbody>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['name']) ?></td>
                    <td><?= $_SESSION['cart'][$p['id']] ?></td>
                    <td><?= number_format($p['price'] * $_SESSION['cart'][$p['id']], 2) ?> €</td>
                </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr><td colspan="2">Sous-total</td><td><?= number_format($total, 2) ?> €</td></tr>
                    <tr><td colspan="2">Frais de port</td><td><?= $total >= 50 ? 'Gratuit' : 'Calculés à l\'étape suivante' ?></td></tr>
                    <tr><td colspan="2"><strong>Total estimé</strong></td><td><strong><?= number_format($total, 2) ?> €</strong></td></tr>
                </tfoot>
            </table>
        </div>

    </div>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
