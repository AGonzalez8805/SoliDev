<?php

namespace App\Repository;

use App\Db\Mongo;
use App\Models\Topic;
use MongoDB\BSON\ObjectId;

class TopicRepository
{
    private $collection;
    private $mysqlUserRepository;

    public function __construct($mysqlUserRepository)
    {
        $this->collection = Mongo::getInstance()->getDatabase()->selectCollection('topics');
        $this->mysqlUserRepository = $mysqlUserRepository; // instance de UserRepository
    }

    public function createTopic(array $data): string
    {
        $topic = new Topic(
            id: null,
            title: $data['title'],
            content: $data['content'],
            authorId: $data['author_id'],
            authorName: $data['author_name'] ?? 'Anonyme',
            category: $data['category'],
            tags: $data['tags'] ?? [],
            topicType: $data['topic_type'] ?? 'discussion',
            createdAt: new \DateTime()
        );

        return $this->create($topic);
    }

    public function create(Topic $topic): string
    {
        $result = $this->collection->insertOne($topic->toMongo());
        return (string) $result->getInsertedId();
    }

    /** Retourne tous les topics avec noms d'auteurs depuis MySQL */
    public function findAll(): array
    {
        $cursor = $this->collection->find([], ['sort' => ['created_at' => -1]]);
        $topics = array_map(fn($doc) => Topic::fromMongo((array)$doc), iterator_to_array($cursor));

        foreach ($topics as $topic) {
            $author = $this->mysqlUserRepository->findById($topic->getAuthorId());
            if ($author) {
                $authorName = $author['firstName'] ?? $author['name'] ?? 'Anonyme';
                $topic->setAuthorName($authorName);
            }
        }

        return $topics;
    }

    public function getTopicById(string $id): ?Topic
    {
        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        if (!$doc) return null;

        $topic = Topic::fromMongo((array)$doc);
        $author = $this->mysqlUserRepository->findById($topic->getAuthorId());
        if ($author) {
            $authorName = $author['firstName'] ?? $author['name'] ?? 'Anonyme';
            $topic->setAuthorName($authorName);
        }

        return $topic;
    }

    public function getTopicsByCategory(string $category, int $limit, int $offset): array
    {
        $cursor = $this->collection->find(
            ['category' => $category],
            ['sort' => ['created_at' => -1], 'skip' => $offset, 'limit' => $limit]
        );

        $topics = array_map(fn($doc) => Topic::fromMongo((array)$doc), iterator_to_array($cursor));

        foreach ($topics as $topic) {
            $author = $this->mysqlUserRepository->findById($topic->getAuthorId());
            if ($author) {
                $authorName = $author['firstName'] ?? $author['name'] ?? 'Anonyme';
                $topic->setAuthorName($authorName);
            }
        }

        return $topics;
    }

    public function countTopicsByCategory(string $category): int
    {
        return $this->collection->countDocuments(['category' => $category]);
    }
}
