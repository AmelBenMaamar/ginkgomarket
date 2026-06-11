<?php require_once '../app/Views/layout/header.php'; ?>

<section class="auth">
    <h1>Créer un compte</h1>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="/?url=user/register" method="POST" class="auth-form">
        <label>Email
            <input type="email" name="email" required autofocus>
        </label>
        <label>Mot de passe
            <input type="password" name="password" required minlength="8">
        </label>
        <label>Confirmer le mot de passe
            <input type="password" name="confirm" required minlength="8">
        </label>
        <button type="submit" class="btn">Créer mon compte</button>
    </form>
    <p class="auth-link">Déjà un compte ? <a href="/?url=user/login">Se connecter</a></p>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
