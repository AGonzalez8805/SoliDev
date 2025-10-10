<?php

namespace App\Config;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    protected PHPMailer $mail;

    public function __construct(bool $debug = false)
    {
        $this->mail = new PHPMailer(true);

        // Debug SMTP
        $this->mail->SMTPDebug = $debug ? 2 : 0;
        $this->mail->Debugoutput = function ($str, $level) {
            error_log("SMTP Debug level $level: $str");
        };

        $this->setupSMTP();
    }

    protected function setupSMTP(): void
    {
        $mail = $this->mail;

        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'] ?? '';
        $mail->Password   = $_ENV['MAIL_PASSWORD'] ?? '';
        $mail->Port       = (int)($_ENV['MAIL_PORT'] ?? 587);
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer'       => true,
                'verify_peer_name'  => true,
                'allow_self_signed' => false,
            ],
        ];

        // Expéditeur par défaut
        $fromEmail = $_ENV['MAIL_FROM'] ?? 'solidev.dev@gmail.com';
        $fromName  = $_ENV['MAIL_FROM_NAME'] ?? 'SoliDev';

        if (empty($fromEmail) || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Adresse FROM invalide : $fromEmail");
        }

        $mail->setFrom($fromEmail, $fromName);
    }

    public function sendContactMail(array $data): array
    {
        try {
            $mail = $this->mail;

            $mail->clearAddresses();
            $mail->clearReplyTos();

            // 📬 Destinataire (toi)
            $mail->addAddress($_ENV['MAIL_TO'] ?? 'solidev.dev@gmail.com');

            // 💡 Ajouter l’adresse de l’utilisateur en Reply-To (pas en From)
            if (!empty($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $name = trim(($data['firstName'] ?? '') . ' ' . ($data['name'] ?? ''));
                $mail->addReplyTo($data['email'], $name ?: 'Visiteur');
            }

            // Contenu du mail
            $mail->isHTML(true);
            $mail->Subject = $data['subject'] ?? '📩 Nouveau message de contact';

            $mail->Body = "
                <h2>📬 Nouveau message depuis le formulaire de contact</h2>
                <p><strong>Nom :</strong> " . htmlspecialchars($data['name'] ?? '', ENT_QUOTES) . "</p>
                <p><strong>Prénom :</strong> " . htmlspecialchars($data['firstName'] ?? '', ENT_QUOTES) . "</p>
                <p><strong>Email :</strong> " . htmlspecialchars($data['email'] ?? '', ENT_QUOTES) . "</p>
                <p><strong>Téléphone :</strong> " . htmlspecialchars($data['phone'] ?? '', ENT_QUOTES) . "</p>
                <hr>
                <p><strong>Message :</strong></p>
                <p>" . nl2br(htmlspecialchars($data['message'] ?? '', ENT_QUOTES)) . "</p>
            ";

            $mail->AltBody =
                "Nom : {$data['name']}\n" .
                "Prénom : {$data['firstName']}\n" .
                "Email : {$data['email']}\n" .
                "Téléphone : {$data['phone']}\n\n" .
                "Message :\n" . ($data['message'] ?? '');

            $mail->send();

            return ['success' => true, 'message' => 'Message envoyé avec succès !'];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => "Erreur SMTP : {$mail->ErrorInfo} ({$e->getMessage()})"
            ];
        }
    }


    public function sendMail(string $to, string $subject, string $body, bool $isHtml = true): array
    {
        try {
            $mail = $this->mail;

            $mail->clearAddresses();
            $mail->addAddress($to);

            $mail->isHTML($isHtml);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();

            return ['success' => true, 'message' => 'Email envoyé avec succès.'];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => "Erreur SMTP : {$mail->ErrorInfo} ({$e->getMessage()})"
            ];
        }
    }
}
