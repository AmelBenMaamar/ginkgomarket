<?php require_once '../app/Views/layout/header.php'; ?>

<section class="admin">
    <h1>Commandes</h1>
    <table class="cart-table">
        <thead>
            <tr><th>#</th><th>Client</th><th>Total</th><th>Port</th><th>Statut</th><th>Date</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $o): ?>
            <tr>
                <td>#<?= $o['id'] ?></td>
                <td><?= htmlspecialchars($o['email'] ?? '—') ?></td>
                <td><?= number_format($o['total'], 2) ?> €</td>
                <td><?= number_format($o['shipping_cost'], 2) ?> €</td>
                <td>
                    <form action="/?url=admin/orderStatus" method="POST" style="display:flex;gap:0.4rem;align-items:center">
                        <input type="hidden" name="id" value="<?= $o['id'] ?>">
                        <select name="status">
                            <?php foreach (['pending','paid','shipped','delivered','cancelled'] as $s): ?>
                            <option value="<?= $s ?>" <?= $o['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn-small">OK</button>
                    </form>
                </td>
                <td><?= date('d/m/Y', strtotime($o['created_at'])) ?></td>
                <td><a href="/?url=admin/orderDetail&id=<?= $o['id'] ?>" class="btn-small">Détail</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
