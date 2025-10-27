<?php

namespace App\Repository;

use App\Db\Mongo;
use App\Models\Comment;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class CommentRepository
{
    private $collection;
    private $mysqlUserRepository;

    public function __construct($mysqlUserRepository)
    {
        $this->collection = Mongo::getInstance()->getDatabase()->selectCollection('comments');
        $this->mysqlUserRepository = $mysqlUserRepository;
    }

    /** Crée un nouveau commentaire */
    public function create(Comment $comment): string
    {
        $result = $this->collection->insertOne($comment->toMongo());
        return (string) $result->getInsertedId();
    }

    /** Récupère tous les commentaires d'un topic */
    public function getCommentsByTopicId(string $topicId): array
    {
        $cursor = $this->collection->find(
            ['topic_id' => $topicId],
            ['sort' => ['created_at' => 1]] // Ordre chronologique
        );

        $comments = array_map(
            fn($doc) => Comment::fromMongo((array)$doc),
            iterator_to_array($cursor)
        );

        // Enrichir avec les noms d'auteurs depuis MySQL
        foreach ($comments as $comment) {
            $author = $this->mysqlUserRepository->findById($comment->getAuthorId());
            if ($author) {
                $authorName = trim(($author['firstName'] ?? '') . ' ' . ($author['name'] ?? ''));
                if (empty($authorName)) {
                    $authorName = $author['username'] ?? 'Anonyme';
                }
                $comment->setAuthorName($authorName);
            }
        }

        return $comments;
    }

    /** Compte les commentaires d'un topic */
    public function countByTopicId(string $topicId): int
    {
        return $this->collection->countDocuments(['topic_id' => $topicId]);
    }

    /** Récupère un commentaire par ID */
    public function findById(string $id): ?Comment
    {
        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        if (!$doc) return null;

        $comment = Comment::fromMongo((array)$doc);

        // Enrichir avec le nom depuis MySQL
        $author = $this->mysqlUserRepository->findById($comment->getAuthorId());
        if ($author) {
            $authorName = trim(($author['firstName'] ?? '') . ' ' . ($author['name'] ?? ''));
            if (empty($authorName)) {
                $authorName = $author['username'] ?? 'Anonyme';
            }
            $comment->setAuthorName($authorName);
        }

        return $comment;
    }

    /** Supprime un commentaire */
    public function delete(string $id): bool
    {
        $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);
        return $result->getDeletedCount() > 0;
    }

    /** Met à jour un commentaire */
    public function update(string $id, array $data): bool
    {
        $updateData = [];

        if (isset($data['content'])) {
            $updateData['content'] = $data['content'];
        }

        if (!empty($updateData)) {
            $updateData['updated_at'] = new UTCDateTime((new \DateTime())->getTimestamp() * 1000);
            $result = $this->collection->updateOne(
                ['_id' => new ObjectId($id)],
                ['$set' => $updateData]
            );
            return $result->getModifiedCount() > 0;
        }

        return false;
    }
}
