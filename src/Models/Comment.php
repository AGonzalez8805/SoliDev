<?php

namespace App\Models;

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Comment
{
    private ?string $id;
    private string $topicId;
    private string $content;
    private int $authorId;
    private string $authorName;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt;

    public function __construct(
        ?string $id,
        string $topicId,
        string $content,
        int $authorId,
        string $authorName,
        \DateTime $createdAt,
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->topicId = $topicId;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->authorName = $authorName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Getters
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getTopicId(): string
    {
        return $this->topicId;
    }
    public function getContent(): string
    {
        return $this->content;
    }
    public function getAuthorId(): int
    {
        return $this->authorId;
    }
    public function getAuthorName(): string
    {
        return $this->authorName;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    // Setters
    public function setAuthorName(string $name): void
    {
        $this->authorName = $name;
    }
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    public function setUpdatedAt(\DateTime $date): void
    {
        $this->updatedAt = $date;
    }

    /** Convertit en document MongoDB */
    public function toMongo(): array
    {
        $doc = [
            'topic_id' => $this->topicId,
            'content' => $this->content,
            'author_id' => $this->authorId,
            'author_name' => $this->authorName,
            'created_at' => new UTCDateTime($this->createdAt->getTimestamp() * 1000)
        ];

        if ($this->id) {
            $doc['_id'] = new ObjectId($this->id);
        }

        if ($this->updatedAt) {
            $doc['updated_at'] = new UTCDateTime($this->updatedAt->getTimestamp() * 1000);
        }

        return $doc;
    }

    /** Crée depuis un document MongoDB */
    public static function fromMongo(array $doc): self
    {
        $id = isset($doc['_id']) ? (string)$doc['_id'] : null;

        $createdAt = $doc['created_at'] instanceof UTCDateTime
            ? $doc['created_at']->toDateTime()
            : new \DateTime();

        $updatedAt = null;
        if (isset($doc['updated_at']) && $doc['updated_at'] instanceof UTCDateTime) {
            $updatedAt = $doc['updated_at']->toDateTime();
        }

        return new self(
            id: $id,
            topicId: $doc['topic_id'] ?? '',
            content: $doc['content'] ?? '',
            authorId: $doc['author_id'] ?? 0,
            authorName: $doc['author_name'] ?? 'Anonyme',
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }
}
