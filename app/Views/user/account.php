<?php require_once '../app/Views/layout/header.php'; ?>

<section class="account">
    <h1>Mon compte</h1>
    <p>Connecté en tant que <strong><?= htmlspecialchars($_SESSION['user']['email']) ?></strong></p>
    <a href="/?url=user/logout" class="btn-danger">Se déconnecter</a>

    <h2>Mes commandes</h2>
    <?php if (empty($orders)): ?>
        <p>Aucune commande pour le moment.</p>
    <?php else: ?>
    <table class="cart-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Date</th>
                <th>Total</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $o): ?>
            <tr>
                <td><?= $o['id'] ?></td>
                <td><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
                <td><?= number_format($o['total'], 2) ?> €</td>
                <td><span class="status-<?= $o['status'] ?>"><?= $o['status'] ?></span></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
