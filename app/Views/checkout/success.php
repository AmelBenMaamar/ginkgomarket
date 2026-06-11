<?php require_once '../app/Views/layout/header.php'; ?>

<section class="success">
    <div class="success-box">
        <div class="success-icon">✓</div>
        <h1>Commande confirmée !</h1>
        <p>Merci pour votre commande <strong>#<?= $orderId ?></strong>.</p>
        <p>Vous recevrez bientôt une confirmation.</p>
        <a href="/?url=product" class="btn">Continuer mes achats</a>
        <a href="/?url=user/account" class="btn-outline">Voir mes commandes</a>
    </div>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
