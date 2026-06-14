<?php
require_once '../config/db.php';
require_once '../config/stripe.php';
require_once '../vendor/autoload.php';
require_once __DIR__ . '/../Services/Mailer.php';

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController {

    public function create(): void {
        if (empty($_SESSION['user']) || empty($_SESSION['pending_order_id'])) {
            header('Location: /?url=cart');
            exit;
        }

        $orderId = (int)$_SESSION['pending_order_id'];
        $pdo     = getDB();

        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $_SESSION['user']['id']]);
        $order = $stmt->fetch();
        if (!$order) { header('Location: /?url=cart'); exit; }

        $stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll();

        Stripe::setApiKey(STRIPE_SECRET_KEY);

        $lineItems = [];
        foreach ($items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => STRIPE_CURRENCY,
                    'unit_amount'  => (int)round($item['price_at_purchase'] * 100),
                    'product_data' => ['name' => $item['name']],
                ],
                'quantity' => $item['quantity'],
            ];
        }

        if ($order['shipping_cost'] > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency'     => STRIPE_CURRENCY,
                    'unit_amount'  => (int)round($order['shipping_cost'] * 100),
                    'product_data' => ['name' => 'Frais de port'],
                ],
                'quantity' => 1,
            ];
        }

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items'           => $lineItems,
            'mode'                 => 'payment',
            'success_url'          => 'http://localhost:8000/?url=payment/success&order=' . $orderId,
            'cancel_url'           => 'http://localhost:8000/?url=payment/cancel&order=' . $orderId,
            'metadata'             => ['order_id' => $orderId],
        ]);

        header('Location: ' . $session->url);
        exit;
    }

    public function success(): void {
        $orderId = (int)($_GET['order'] ?? 0);
        $pdo     = getDB();

        $pdo->prepare("UPDATE orders SET status = 'paid' WHERE id = ?")
            ->execute([$orderId]);

        // Récupérer commande + articles pour l'email
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();

        $stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll();

        // Envoyer email de confirmation
        if ($order && !empty($_SESSION['user']['email'])) {
            Mailer::orderConfirmation($order, $items, $_SESSION['user']['email']);
        }

        unset($_SESSION['pending_order_id']);

        $title = 'Paiement confirmé — GinkGoMarket';
        require_once '../app/Views/payment/success.php';
    }

    public function cancel(): void {
        $orderId = (int)($_GET['order'] ?? 0);
        getDB()->prepare("UPDATE orders SET status = 'pending' WHERE id = ?")->execute([$orderId]);
        $title = 'Paiement annulé — GinkGoMarket';
        require_once '../app/Views/payment/cancel.php';
    }
}
