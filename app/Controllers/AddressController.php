<?php
require_once '../config/db.php';

class AddressController {

    public function delete(): void {
        if (empty($_SESSION['user'])) {
            header('Location: /?url=user/login');
            exit;
        }
        $id     = (int)($_POST['id'] ?? 0);
        $userId = $_SESSION['user']['id'];
        $pdo    = getDB();
        // Sécurité : vérifier que l'adresse appartient à l'utilisateur
        $pdo->prepare("DELETE FROM addresses WHERE id = ? AND user_id = ?")
            ->execute([$id, $userId]);
        header('Location: /?url=checkout');
        exit;
    }
}
