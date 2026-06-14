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

        $pdo    = getDB();
        $userId = $_SESSION['user']['id'];
        $savedId = (int)($_POST['saved_address'] ?? 0);
        $addressId = null;

        if ($savedId > 0) {
            $stmt = $pdo->prepare("SELECT * FROM addresses WHERE id = ? AND user_id = ?");
            $stmt->execute([$savedId, $userId]);
            $addr = $stmt->fetch();
            if ($addr) {
                $addressId = $savedId;
            }
        }

        if ($addressId === null) {
            $rue   = trim($_POST['rue']   ?? '');
            $ville = trim($_POST['ville'] ?? '');
            $cp    = trim($_POST['cp']    ?? '');
            $pays  = trim($_POST['pays']  ?? 'France');
            $pdo->prepare("INSERT INTO addresses (user_id, rue, ville, cp, pays) VALUES (?,?,?,?,?)")
                ->execute([$userId, $rue, $ville, $cp, $pays]);
            $addressId = $pdo->lastInsertId();
        }

        $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
        $products = $pdo->query("SELECT * FROM products WHERE id IN ($ids)")->fetchAll();

        $total       = 0;
        $totalWeight = 0;
        foreach ($products as $p) {
            $qty          = $_SESSION['cart'][$p['id']];
            $total       += $p['price'] * $qty;
            $totalWeight += $p['weight_g'] * $qty;
        }

        if ($total >= 50) {
            $shipping = 0;
        } elseif ($totalWeight < 500) {
            $shipping = 4.90;
        } elseif ($totalWeight < 2000) {
            $shipping = 6.90;
        } else {
            $shipping = 9.90;
        }

        $pdo->prepare("INSERT INTO orders (user_id, status, total, shipping_cost, address_id) VALUES (?,?,?,?,?)")
            ->execute([$userId, 'pending', $total + $shipping, $shipping, $addressId]);
        $orderId = $pdo->lastInsertId();

        foreach ($products as $p) {
            $qty = $_SESSION['cart'][$p['id']];
            $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?,?,?,?)")
                ->execute([$orderId, $p['id'], $qty, $p['price']]);
            $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?")
                ->execute([$qty, $p['id']]);
        }

        unset($_SESSION['cart']);
        $_SESSION['pending_order_id'] = $orderId;
        header('Location: /?url=payment/create');
        exit;
    }
}
