<?php
require_once '../config/db.php';

class AdminController {

    private function guard(): void {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /?url=user/login');
            exit;
        }
    }

    // === DASHBOARD ===
    public function index(): void {
        $this->guard();
        $pdo = getDB();
        $stats = [
            'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
            'orders'   => $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(),
            'users'    => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
            'revenue'  => $pdo->query("SELECT COALESCE(SUM(total),0) FROM orders WHERE status != 'cancelled'")->fetchColumn(),
        ];
        $recentOrders = $pdo->query("SELECT o.*, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC LIMIT 5")->fetchAll();
        $title = 'Admin — GinkGoMarket';
        require_once '../app/Views/admin/dashboard.php';
    }

    // === PRODUITS ===
    public function products(): void {
        $this->guard();
        $pdo      = getDB();
        $products = $pdo->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.created_at DESC")->fetchAll();
        $title    = 'Produits — Admin';
        require_once '../app/Views/admin/products.php';
    }

    public function productCreate(): void {
        $this->guard();
        $pdo        = getDB();
        $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
        $error      = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name     = trim($_POST['name'] ?? '');
            $desc     = trim($_POST['description'] ?? '');
            $price    = (float)($_POST['price'] ?? 0);
            $stock    = (int)($_POST['stock'] ?? 0);
            $weight   = (int)($_POST['weight_g'] ?? 0);
            $catId    = (int)($_POST['category_id'] ?? 0) ?: null;
            $image    = null;

            if (!empty($_FILES['image']['name'])) {
                $ext   = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp'];
                if (in_array($ext, $allowed)) {
                    $filename = uniqid('prod_') . '.' . $ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $filename);
                    $image = $filename;
                }
            }

            if ($name && $price > 0) {
                $pdo->prepare("INSERT INTO products (category_id, name, description, price, stock, weight_g, image) VALUES (?,?,?,?,?,?,?)")
                    ->execute([$catId, $name, $desc, $price, $stock, $weight, $image]);
                header('Location: /?url=admin/products');
                exit;
            }
            $error = 'Nom et prix obligatoires.';
        }
        $title = 'Nouveau produit — Admin';
        require_once '../app/Views/admin/product_form.php';
    }

    public function productEdit(): void {
        $this->guard();
        $pdo        = getDB();
        $id         = (int)($_GET['id'] ?? 0);
        $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
        $stmt       = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();
        if (!$product) { header('Location: /?url=admin/products'); exit; }
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name   = trim($_POST['name'] ?? '');
            $desc   = trim($_POST['description'] ?? '');
            $price  = (float)($_POST['price'] ?? 0);
            $stock  = (int)($_POST['stock'] ?? 0);
            $weight = (int)($_POST['weight_g'] ?? 0);
            $catId  = (int)($_POST['category_id'] ?? 0) ?: null;
            $image  = $product['image'];

            if (!empty($_FILES['image']['name'])) {
                $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp'];
                if (in_array($ext, $allowed)) {
                    $filename = uniqid('prod_') . '.' . $ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $filename);
                    $image = $filename;
                }
            }

            $pdo->prepare("UPDATE products SET category_id=?, name=?, description=?, price=?, stock=?, weight_g=?, image=? WHERE id=?")
                ->execute([$catId, $name, $desc, $price, $stock, $weight, $image, $id]);
            header('Location: /?url=admin/products');
            exit;
        }
        $title = 'Modifier produit — Admin';
        require_once '../app/Views/admin/product_form.php';
    }

    public function productDelete(): void {
        $this->guard();
        $id = (int)($_POST['id'] ?? 0);
        getDB()->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        header('Location: /?url=admin/products');
        exit;
    }

    // === COMMANDES ===
    public function orders(): void {
        $this->guard();
        $pdo    = getDB();
        $orders = $pdo->query("SELECT o.*, u.email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();
        $title  = 'Commandes — Admin';
        require_once '../app/Views/admin/orders.php';
    }

    public function orderDetail(): void {
        $this->guard();
        $pdo  = getDB();
        $id   = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT o.*, u.email, a.rue, a.cp, a.ville, a.pays FROM orders o LEFT JOIN users u ON o.user_id = u.id LEFT JOIN addresses a ON o.address_id = a.id WHERE o.id = ?");
        $stmt->execute([$id]);
        $order = $stmt->fetch();
        if (!$order) { header('Location: /?url=admin/orders'); exit; }
        $items = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $items->execute([$id]);
        $items = $items->fetchAll();
        $title = 'Commande #' . $id . ' — Admin';
        require_once '../app/Views/admin/order_detail.php';
    }

    public function orderStatus(): void {
        $this->guard();
        $id     = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        $allowed = ['pending','paid','shipped','delivered','cancelled'];
        if (in_array($status, $allowed)) {
            getDB()->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$status, $id]);
        }
        header('Location: /?url=admin/orders');
        exit;
    }
}
