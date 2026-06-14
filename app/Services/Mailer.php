<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

class Mailer {

    public static function send(string $to, string $subject, string $htmlBody): bool {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USER;
            $mail->Password   = MAIL_PASS;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = (int)MAIL_PORT;
            $mail->CharSet    = 'UTF-8';
            $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->send();
            return true;
        } catch (\Exception $e) {
            error_log('Mailer error: ' . $mail->ErrorInfo);
            return false;
        }
    }

    public static function orderConfirmation(array $order, array $items, string $email): bool {
        $lignes = '';
        foreach ($items as $item) {
            $lignes .= '<tr>
                <td style="padding:8px;border-bottom:1px solid #e0e0e0">' . htmlspecialchars($item['name']) . '</td>
                <td style="padding:8px;border-bottom:1px solid #e0e0e0;text-align:center">' . $item['quantity'] . '</td>
                <td style="padding:8px;border-bottom:1px solid #e0e0e0;text-align:right">' . number_format($item['price_at_purchase'] * $item['quantity'], 2) . ' €</td>
            </tr>';
        }

        $html = '<!DOCTYPE html>
<html lang="fr"><head><meta charset="UTF-8"></head>
<body style="font-family:Georgia,serif;background:#f0fffb;margin:0;padding:0">
<div style="max-width:600px;margin:2rem auto;background:#fff;border-radius:12px;overflow:hidden;border:1px solid #a8edd8">
    <div style="background:#009F74;padding:2rem;text-align:center">
        <h1 style="color:#fff;margin:0">🌿 GinkGoMarket</h1>
    </div>
    <div style="padding:2rem">
        <h2 style="color:#0d3d2e">Commande confirmée !</h2>
        <p>Bonjour,</p>
        <p>Votre commande <strong>#' . $order['id'] . '</strong> a bien été reçue.</p>
        <table style="width:100%;border-collapse:collapse;margin:1.5rem 0">
            <thead>
                <tr style="background:#f0fffb">
                    <th style="padding:8px;text-align:left">Produit</th>
                    <th style="padding:8px;text-align:center">Qté</th>
                    <th style="padding:8px;text-align:right">Sous-total</th>
                </tr>
            </thead>
            <tbody>' . $lignes . '</tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="padding:8px;font-weight:bold">Frais de port</td>
                    <td style="padding:8px;text-align:right">' . number_format($order['shipping_cost'], 2) . ' €</td>
                </tr>
                <tr style="background:#f0fffb">
                    <td colspan="2" style="padding:8px;font-weight:bold">Total</td>
                    <td style="padding:8px;text-align:right;font-weight:bold">' . number_format($order['total'], 2) . ' €</td>
                </tr>
            </tfoot>
        </table>
        <p style="color:#5a7a6e">Nous vous tiendrons informé de l\'expédition.</p>
        <p>Merci pour votre confiance,<br><strong>GinkGoMarket</strong></p>
    </div>
    <div style="background:#009F74;padding:1rem;text-align:center">
        <p style="color:#a8edd8;margin:0;font-size:0.85rem">© 2026 GinkGoMarket — Amel Ben Maamar</p>
    </div>
</div>
</body></html>';

        return self::send($email, 'Confirmation de votre commande #' . $order['id'], $html);
    }
}
