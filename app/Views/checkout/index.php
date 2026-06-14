<?php require_once '../app/Views/layout/header.php'; ?>
<section class="checkout">
    <h1>Finaliser ma commande</h1>
    <div class="checkout-grid">
        <div class="checkout-form">
            <h2>Adresse de livraison</h2>

            <?php if (!empty($addresses)): ?>
            <div class="saved-addresses">
                <p><strong>Adresses sauvegardées :</strong></p>

                <?php foreach ($addresses as $a): ?>
                <div class="address-row">
                    <label class="address-option">
                        <input type="radio" name="saved_address" value="<?= $a['id'] ?>"
                            form="checkout-form"
                            class="addr-radio"
                            data-rue="<?= htmlspecialchars($a['rue']) ?>"
                            data-cp="<?= htmlspecialchars($a['cp']) ?>"
                            data-ville="<?= htmlspecialchars($a['ville']) ?>"
                            data-pays="<?= htmlspecialchars($a['pays']) ?>">
                        <span><?= htmlspecialchars($a['rue']) ?>, <?= htmlspecialchars($a['cp']) ?> <?= htmlspecialchars($a['ville']) ?></span>
                    </label>
                    <button type="submit" form="delete-<?= $a['id'] ?>" class="btn-danger btn-xs">✕</button>
                    <form id="delete-<?= $a['id'] ?>" action="/?url=address/delete" method="POST">
                        <input type="hidden" name="id" value="<?= $a['id'] ?>">
                    </form>
                </div>
                <?php endforeach; ?>

                <p class="or-divider">— ou saisir une nouvelle adresse —</p>
            </div>
            <?php endif; ?>

            <form id="checkout-form" action="/?url=checkout/confirm" method="POST">
                <div class="new-address-fields">
                    <label>Rue <input type="text" name="rue" id="field-rue" placeholder="12 rue des Oliviers"></label>
                    <label>Code postal <input type="text" name="cp" id="field-cp" placeholder="34500"></label>
                    <label>Ville <input type="text" name="ville" id="field-ville" placeholder="Béziers"></label>
                    <label>Pays <input type="text" name="pays" id="field-pays" value="France"></label>
                </div>
                <button type="submit" class="btn" style="margin-top:1rem">Confirmer la commande →</button>
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
                    <tr><td colspan="2">Frais de port</td><td><?= $total >= 50 ? 'Gratuit' : "Calculés à l'étape suivante" ?></td></tr>
                    <tr><td colspan="2"><strong>Total estimé</strong></td><td><strong><?= number_format($total, 2) ?> €</strong></td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.addr-radio').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('field-rue').value   = this.dataset.rue;
        document.getElementById('field-cp').value    = this.dataset.cp;
        document.getElementById('field-ville').value = this.dataset.ville;
        document.getElementById('field-pays').value  = this.dataset.pays;
    });
});
</script>

<?php require_once '../app/Views/layout/footer.php'; ?>
