<?php require_once '../app/Views/layout/header.php'; ?>

<section class="admin">
    <h1>Tableau de bord</h1>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?= $stats['products'] ?></span>
            <span class="stat-label">Produits</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['orders'] ?></span>
            <span class="stat-label">Commandes</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $stats['users'] ?></span>
            <span class="stat-label">Clients</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= number_format($stats['revenue'], 2) ?> €</span>
            <span class="stat-label">Chiffre d'affaires</span>
        </div>
    </div>

    <div class="admin-nav">
        <a href="/?url=admin/products" class="btn">Gérer les produits</a>
        <a href="/?url=admin/productCreate" class="btn">+ Nouveau produit</a>
        <a href="/?url=admin/orders" class="btn">Gérer les commandes</a>
    </div>

    <h2>Dernières commandes</h2>
    <table class="cart-table">
        <thead>
            <tr><th>#</th><th>Client</th><th>Total</th><th>Statut</th><th>Date</th></tr>
        </thead>
        <tbody>
        <?php foreach ($recentOrders as $o): ?>
            <tr>
                <td><a href="/?url=admin/orderDetail&id=<?= $o['id'] ?>">#<?= $o['id'] ?></a></td>
                <td><?= htmlspecialchars($o['email'] ?? '—') ?></td>
                <td><?= number_format($o['total'], 2) ?> €</td>
                <td><span class="status-<?= $o['status'] ?>"><?= $o['status'] ?></span></td>
                <td><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
