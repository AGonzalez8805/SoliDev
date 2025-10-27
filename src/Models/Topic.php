<?php

namespace App\Models;

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Topic
{
    private ?string $id;
    private string $title;
    private string $content;
    private int $authorId;
    private string $authorName;
    private string $category;
    private array $tags;
    private string $topicType;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt;
    private int $commentCount = 0; // ✅ Ajout de la propriété

    public function __construct(
        ?string $id,
        string $title,
        string $content,
        int $authorId,
        string $authorName,
        string $category,
        array $tags = [],
        string $topicType = 'discussion',
        \DateTime $createdAt = new \DateTime(),
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->authorName = $authorName;
        $this->category = $category;
        $this->tags = $tags;
        $this->topicType = $topicType;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Getters
    public function getId(): ?string
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
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
    public function getCategory(): string
    {
        return $this->category;
    }
    public function getTags(): array
    {
        return $this->tags;
    }
    public function getTopicType(): string
    {
        return $this->topicType;
    }
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
    public function getCommentCount(): int
    {
        return $this->commentCount;
    } // ✅ Getter

    // Setters
    public function setId(string $id): void
    {
        $this->id = $id;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
    public function setAuthorName(string $name): void
    {
        $this->authorName = $name;
    }
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }
    public function setUpdatedAt(\DateTime $date): void
    {
        $this->updatedAt = $date;
    }
    public function setCommentCount(int $count): void
    {
        $this->commentCount = $count;
    } // ✅ Setter

    /** Convertit en document MongoDB */
    public function toMongo(): array
    {
        $doc = [
            'title' => $this->title,
            'content' => $this->content,
            'author_id' => $this->authorId,
            'author_name' => $this->authorName,
            'category' => $this->category,
            'tags' => $this->tags,
            'topic_type' => $this->topicType,
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

        // Conversion du BSONArray en tableau PHP
        $tags = [];
        if (isset($doc['tags'])) {
            $tags = is_array($doc['tags']) ? $doc['tags'] : $doc['tags']->getArrayCopy();
        }

        return new self(
            id: $id,
            title: $doc['title'] ?? '',
            content: $doc['content'] ?? '',
            authorId: $doc['author_id'] ?? 0,
            authorName: $doc['author_name'] ?? 'Anonyme',
            category: $doc['category'] ?? 'general',
            tags: $tags, // maintenant c’est bien un array PHP
            topicType: $doc['topic_type'] ?? 'discussion',
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }
}
