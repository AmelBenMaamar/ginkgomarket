<?php
require_once '../config/db.php';

class HomeController {
    public function index(): void {
        $pdo = getDB();
        $products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 6")->fetchAll();
        $title = 'GinkGoMarket — Accueil';
        require_once '../app/Views/home/index.php';
    }
}
