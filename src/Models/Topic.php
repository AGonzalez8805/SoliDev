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

    public function __construct(
        ?string $id,
        string $title,
        string $content,
        int $authorId,
        string $authorName,
        string $category,
        array $tags,
        string $topicType,
        \DateTime $createdAt
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
    }

    public static function fromMongo(array $data): Topic
    {
        return new Topic(
            (string)($data['_id'] ?? ''),
            $data['title'] ?? '',
            $data['content'] ?? '',
            (int)($data['author_id'] ?? 0),
            $data['author_name'] ?? 'Anonyme',
            $data['category'] ?? 'general',
            isset($data['tags']) ? (array)$data['tags'] : [],
            $data['topic_type'] ?? 'discussion',
            isset($data['created_at']) && $data['created_at'] instanceof UTCDateTime
                ? $data['created_at']->toDateTime()
                : new \DateTime()
        );
    }

    public function toMongo(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
            'author_id' => $this->authorId,
            'author_name' => $this->authorName,
            'category' => $this->category,
            'tags' => $this->tags,
            'topic_type' => $this->topicType,
            'created_at' => new UTCDateTime($this->createdAt),
        ];
    }

    /**
     * Get the value of id
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Get the value of title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the value of content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Get the value of authorId
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * Get the value of authorName
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * Get the value of category
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Get the value of tags
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Get the value of topicType
     */
    public function getTopicType(): string
    {
        return $this->topicType;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set the value of authorName
     */
    public function setAuthorName(string $authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }
}
