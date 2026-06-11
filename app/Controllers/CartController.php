<?php
require_once '../config/db.php';

class CartController {

    public function index(): void {
        $cart = $_SESSION['cart'] ?? [];
        $products = [];
        $total = 0;

        if (!empty($cart)) {
            $pdo = getDB();
            $ids = implode(',', array_map('intval', array_keys($cart)));
            $products = $pdo->query("SELECT * FROM products WHERE id IN ($ids)")->fetchAll();
            foreach ($products as $p) {
                $total += $p['price'] * $cart[$p['id']];
            }
        }

        $title = 'Panier — GinkGoMarket';
        require_once '../app/Views/cart/index.php';
    }

    public function add(): void {
        $id  = (int)($_POST['product_id'] ?? 0);
        $qty = (int)($_POST['quantity']   ?? 1);

        if ($id > 0 && $qty > 0) {
            $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
        }

        header('Location: /cart');
        exit;
    }

    public function remove(): void {
        $id = (int)($_POST['product_id'] ?? 0);
        unset($_SESSION['cart'][$id]);
        header('Location: /cart');
        exit;
    }

    public function update(): void {
        $id  = (int)($_POST['product_id'] ?? 0);
        $qty = (int)($_POST['quantity']   ?? 1);

        if ($id > 0 && $qty > 0) {
            $_SESSION['cart'][$id] = $qty;
        } elseif ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        }

        header('Location: /cart');
        exit;
    }

    public function clear(): void {
        unset($_SESSION['cart']);
        header('Location: /cart');
        exit;
    }
}
