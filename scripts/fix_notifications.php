<?php
require_once './public/index.php'; // fichier qui charge autoload, DB etc.

use App\Repository\BlogRepository;
use App\Repository\CommentsBlogRepository;
use App\Repository\NotificationRepository;

$blogRepo = new BlogRepository();
$commentRepo = new CommentsBlogRepository();
$notifRepo = new NotificationRepository();

// Récupérer tous les blogs
$blogs = $blogRepo->findAll(); // tu peux créer findAll si besoin

foreach ($blogs as $blog) {
    $comments = $commentRepo->findByBlogId($blog->getId());

    foreach ($comments as $comment) {
        // Ne pas notifier si l'auteur commente lui-même
        if ($comment->getUserId() === $blog->getAuthorId()) {
            continue;
        }

        // Vérifier si notification existante pour ce commentaire
        $existing = $notifRepo->exists([
            'user_id' => $blog->getAuthorId(),
            'type' => 'comment',
            'message' => "Votre article '{$blog->getTitle()}' a reçu un nouveau commentaire."
        ]);

        if (!$existing) {
            $notifRepo->insert([
                'user_id' => $blog->getAuthorId(),
                'type' => 'comment',
                'message' => "Votre article '{$blog->getTitle()}' a reçu un nouveau commentaire.",
                'link' => "/?controller=blog&action=show&id=" . $blog->getId(),
                'is_read' => 0,
                'created_at' => $comment->getCreatedAt()
            ]);
        }
    }
}

echo "Notifications mises à jour pour tous les posts existants.\n";
