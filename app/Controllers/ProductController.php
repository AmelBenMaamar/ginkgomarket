<?php
require_once '../config/db.php';

class ProductController {

    public function index(): void {
        $pdo = getDB();
        $products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetchAll();
        $title = 'Boutique — GinkGoMarket';
        require_once '../app/Views/product/index.php';
    }

    public function show(): void {
        $pdo = getDB();
        $id = (int)($_GET['url'] ? explode('/', $_GET['url'])[2] ?? 0 : 0);
        $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        if (!$product) {
            http_response_code(404);
            echo "Produit introuvable.";
            return;
        }
        $title = htmlspecialchars($product['name']) . ' — GinkGoMarket';
        require_once '../app/Views/product/show.php';
    }
}
