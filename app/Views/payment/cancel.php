<?php require_once '../app/Views/layout/header.php'; ?>

<section class="success">
    <div class="success-box">
        <div class="success-icon" style="background:var(--error)">✕</div>
        <h1>Paiement annulé</h1>
        <p>Votre commande <strong>#<?= $orderId ?></strong> n'a pas été finalisée.</p>
        <a href="/?url=cart" class="btn">Retour au panier</a>
    </div>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
