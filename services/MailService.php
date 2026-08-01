<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use App\Models\Statement;

class MailService
{
    public function sendStatementEmail(int $userId, array $statementRecord): bool
    {
        // Load user email from DB
        $pdo = \App\Core\Database::getInstance();
        $q = $pdo->prepare('SELECT email, full_name FROM users WHERE id = :id LIMIT 1');
        $q->execute(['id' => $userId]);
        $user = $q->fetch();
        if (!$user) {
            return false;
        }

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = getenv('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = getenv('MAIL_USERNAME');
            $mail->Password = getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = getenv('MAIL_ENCRYPTION') ?: PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = getenv('MAIL_PORT') ?: 587;

            $mail->setFrom(getenv('MAIL_FROM_ADDRESS') ?: 'no-reply@example.com', getenv('MAIL_FROM_NAME') ?: 'Hana-Eunhaeng');
            $mail->addAddress($user['email'], $user['full_name']);

            $mail->isHTML(true);
            $mail->Subject = 'Your statement from Hana-Eunhaeng';

            $body = '<p>Hello ' . htmlspecialchars($user['full_name']) . ',</p>';
            $body .= '<p>Attached is your requested account statement.</p>';
            $body .= '<p>If you did not request this, contact support immediately.</p>';

            $mail->Body = $body;

            // Attach PDF if available
            if (!empty($statementRecord['file_path'])) {
                $path = __DIR__ . '/../../public/uploads/statements/' . $statementRecord['file_path'];
                if (file_exists($path)) {
                    $mail->addAttachment($path);
                }
            }

            $mail->send();
            return true;
        } catch (\Throwable $e) {
            // Log error in production
            return false;
        }
    }
}
