<?php
require_once '../config/db.php';

class UserController {

    public function login(): void {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $pdo      = getDB();
            $stmt     = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'email' => $user['email'],
                    'role'  => $user['role'],
                ];
                header('Location: /?url=user/account');
                exit;
            }
            $error = 'Email ou mot de passe incorrect.';
        }
        $title = 'Connexion — GinkGoMarket';
        require_once '../app/Views/user/login.php';
    }

    public function register(): void {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm'] ?? '';
            if ($password !== $confirm) {
                $error = 'Les mots de passe ne correspondent pas.';
            } elseif (strlen($password) < 8) {
                $error = 'Le mot de passe doit contenir au moins 8 caractères.';
            } else {
                $pdo  = getDB();
                $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $error = 'Cet email est déjà utilisé.';
                } else {
                    $hash = password_hash($password, PASSWORD_BCRYPT);
                    $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)")
                        ->execute([$email, $hash]);
                    header('Location: /?url=user/login');
                    exit;
                }
            }
        }
        $title = 'Inscription — GinkGoMarket';
        require_once '../app/Views/user/register.php';
    }

    public function account(): void {
        if (empty($_SESSION['user'])) {
            header('Location: /?url=user/login');
            exit;
        }
        $pdo   = getDB();
        $stmt  = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$_SESSION['user']['id']]);
        $orders = $stmt->fetchAll();
        $title = 'Mon compte — GinkGoMarket';
        require_once '../app/Views/user/account.php';
    }

    public function logout(): void {
        unset($_SESSION['user']);
        header('Location: /?url=user/login');
        exit;
    }
}
