<?php require_once '../app/Views/layout/header.php'; ?>

<section class="success">
    <div class="success-box">
        <div class="success-icon">✓</div>
        <h1>Paiement confirmé !</h1>
        <p>Votre commande <strong>#<?= $orderId ?></strong> a été payée avec succès.</p>
        <p>Vous recevrez une confirmation par email.</p>
        <a href="/?url=product" class="btn">Continuer mes achats</a>
        <a href="/?url=user/account" class="btn-outline">Voir mes commandes</a>
    </div>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
