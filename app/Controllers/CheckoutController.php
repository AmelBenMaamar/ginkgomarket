<?php
require_once '../config/db.php';

class CheckoutController {

    public function index(): void {
        if (empty($_SESSION['user'])) {
            header('Location: /?url=user/login');
            exit;
        }
        if (empty($_SESSION['cart'])) {
            header('Location: /?url=cart');
            exit;
        }

        $pdo = getDB();
        $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
        $products = $pdo->query("SELECT * FROM products WHERE id IN ($ids)")->fetchAll();

        $total = 0;
        foreach ($products as $p) {
            $total += $p['price'] * $_SESSION['cart'][$p['id']];
        }

        // Adresses sauvegardées
        $stmt = $pdo->prepare("SELECT * FROM addresses WHERE user_id = ?");
        $stmt->execute([$_SESSION['user']['id']]);
        $addresses = $stmt->fetchAll();

        $title = 'Commande — GinkGoMarket';
        require_once '../app/Views/checkout/index.php';
    }

    public function confirm(): void {
        if (empty($_SESSION['user']) || empty($_SESSION['cart'])) {
            header('Location: /?url=home');
            exit;
        }

        $pdo = getDB();

        // Adresse
        $rue    = trim($_POST['rue']    ?? '');
        $ville  = trim($_POST['ville']  ?? '');
        $cp     = trim($_POST['cp']     ?? '');
        $pays   = trim($_POST['pays']   ?? 'France');
        $userId = $_SESSION['user']['id'];

        // Sauvegarder l'adresse
        $pdo->prepare("INSERT INTO addresses (user_id, rue, ville, cp, pays) VALUES (?,?,?,?,?)")
            ->execute([$userId, $rue, $ville, $cp, $pays]);
        $addressId = $pdo->lastInsertId();

        // Calcul total + frais de port
        $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
        $products = $pdo->query("SELECT * FROM products WHERE id IN ($ids)")->fetchAll();

        $total        = 0;
        $totalWeight  = 0;
        foreach ($products as $p) {
            $qty          = $_SESSION['cart'][$p['id']];
            $total       += $p['price'] * $qty;
            $totalWeight += $p['weight_g'] * $qty;
        }

        // Frais de port : gratuit > 50€, sinon 4.90€ < 500g, 6.90€ < 2kg, 9.90€ au-delà
        if ($total >= 50) {
            $shipping = 0;
        } elseif ($totalWeight < 500) {
            $shipping = 4.90;
        } elseif ($totalWeight < 2000) {
            $shipping = 6.90;
        } else {
            $shipping = 9.90;
        }

        // Créer la commande
        $pdo->prepare("INSERT INTO orders (user_id, status, total, shipping_cost, address_id) VALUES (?,?,?,?,?)")
            ->execute([$userId, 'pending', $total + $shipping, $shipping, $addressId]);
        $orderId = $pdo->lastInsertId();

        // Insérer les lignes de commande + décrémenter stock
        foreach ($products as $p) {
            $qty = $_SESSION['cart'][$p['id']];
            $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?,?,?,?)")
                ->execute([$orderId, $p['id'], $qty, $p['price']]);
            $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?")
                ->execute([$qty, $p['id']]);
        }

        // Vider le panier
        unset($_SESSION['cart']);

        header('Location: /?url=checkout/success&order=' . $orderId);
        exit;
    }

    public function success(): void {
        $orderId = (int)($_GET['order'] ?? 0);
        $title   = 'Commande confirmée — GinkGoMarket';
        require_once '../app/Views/checkout/success.php';
    }
}
