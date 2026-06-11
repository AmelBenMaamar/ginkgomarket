<?php require_once '../app/Views/layout/header.php'; ?>

<section class="auth">
    <h1>Connexion</h1>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form action="/?url=user/login" method="POST" class="auth-form">
        <label>Email
            <input type="email" name="email" required autofocus>
        </label>
        <label>Mot de passe
            <input type="password" name="password" required>
        </label>
        <button type="submit" class="btn">Se connecter</button>
    </form>
    <p class="auth-link">Pas encore de compte ? <a href="/?url=user/register">S'inscrire</a></p>
</section>

<?php require_once '../app/Views/layout/footer.php'; ?>
