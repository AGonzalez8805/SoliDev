<?php

namespace App\Service;

use App\Repository\UserRepository;
use App\Config\Mailer;

class UserService
{
    protected UserRepository $userRepo;
    protected Mailer $mailer;

    public function __construct(UserRepository $userRepo, Mailer $mailer)
    {
        $this->userRepo = $userRepo;
        $this->mailer = $mailer;
    }

    public function registerUser(string $name, string $firstName, string $email, string $password): array
    {
        // Vérifier si l'email existe déjà
        if ($this->userRepo->findByEmail($email)) {
            return [
                'success' => false,
                'message' => 'Cet email est déjà utilisé.'
            ];
        }

        // Hash du mot de passe
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Générer un token unique pour la confirmation email
        $token = bin2hex(random_bytes(16));

        // Créer l'utilisateur en base
        $userId = $this->userRepo->create([
            'name' => $name,
            'firstName' => $firstName,
            'email' => $email,
            'password' => $passwordHash,
            'role' => 'utilisateur',
            'email_verification_token' => $token,
            'is_verified' => 0
        ]);

        if (!$userId) {
            return [
                'success' => false,
                'message' => 'Impossible de créer le compte, réessayez plus tard.'
            ];
        }

        // Préparer le mail de confirmation
        $verificationLink = "https://tonsite.com/?controller=auth&action=confirmEmail&token=$token";
        $subject = "Confirmez votre adresse email";
        $body = "
            <p>Bonjour $firstName,</p>
            <p>Merci pour votre inscription sur SoliDev.</p>
            <p>Cliquez sur le lien ci-dessous pour activer votre compte :</p>
            <p><a href='$verificationLink'>$verificationLink</a></p>
            <p>Si vous n'avez pas créé ce compte, ignorez ce message.</p>
        ";

        $mailResult = $this->mailer->sendMail($email, $subject, $body);

        if (!$mailResult['success']) {
            return [
                'success' => false,
                'message' => 'Compte créé mais impossible d’envoyer le mail de confirmation : ' . $mailResult['message']
            ];
        }

        return [
            'success' => true,
            'message' => 'Compte créé avec succès ! Un email de confirmation vous a été envoyé.'
        ];
    }

    // Méthode pour valider le token après clic sur le mail
    public function confirmEmail(string $token): bool
    {
        $user = $this->userRepo->findByToken($token);
        if (!$user) {
            return false;
        }

        // Activer le compte
        $this->userRepo->verifyUser($user['users_id']);
        return true;
    }
}
