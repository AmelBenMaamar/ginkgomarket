<?php require_once '../app/Views/layout/header.php'; ?>

<section class="admin">
    <h1>Commande #<?= $order['id'] ?></h1>

    <div class="order-detail-grid">
        <div class="order-info">
            <h2>Informations</h2>
            <p><strong>Client :</strong> <?= htmlspecialchars($order['email'] ?? '—') ?></p>
            <p><strong>Statut :</strong> <span class="status-<?= $order['status'] ?>"><?= $order['status'] ?></span></p>
            <p><strong>Date :</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
            <p><strong>Frais de port :</strong> <?= number_format($order['shipping_cost'], 2) ?> €</p>
            <p><strong>Total :</strong> <?= number_format($order['total'], 2) ?> €</p>
        </div>
        <div class="order-address">
            <h2>Adresse de livraison</h2>
            <p><?= htmlspecialchars($order['rue'] ?? '—') ?></p>
            <p><?= htmlspecialchars($order['cp'] ?? '') ?> <?= htmlspecialchars($order['ville'] ?? '') ?></p>
            <p><?= htmlspecialchars($order['pays'] ?? '') ?></p>
        </div>
    </div>

    <h2>Produits commandés</h2>
    <table class="cart-table">
        <thead>
            <tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th><th>Sous-total</th></tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name'] ?? '—') ?></td>
                <td><?= $item['quantity'] ?></td>
                <td><?= number_format($item['price_at_purchase'], 2) ?> €</td>
                <td><?= number_format($item['price_at_purchase'] * $item['quantity'], 2) ?> €</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="/?url=admin/orders" class="btn-outline" style="margin-top:1.5rem;display:inline-block">← Retour aux commandes</a>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
