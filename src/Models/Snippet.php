<?php

namespace App\Models;

class Snippet
{
    private int $id;
    private int $authorId;
    private string $title;
    private string $description;
    private string $language;
    private string $category;
    private string $code;
    private ?string $usageExample = null;
    private ?string $tags = null;
    private string $visibility = 'public';
    private bool $allowComments = true;
    private bool $allowFork = true;
    private int $views = 0;
    private ?string $authorName = null;
    private string $createdAt;
    private string $updatedAt;

    /**
     * Get the value of id
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of authorId
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    /**
     * Set the value of authorId
     */
    public function setAuthorId(int $authorId): self
    {
        $this->authorId = $authorId;

        return $this;
    }

    /**
     * Get the value of title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set the value of title
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of language
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Set the value of language
     */
    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Set the value of category
     */
    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of code
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set the value of code
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of usageExample
     */
    public function getUsageExample(): ?string
    {
        return $this->usageExample;
    }

    /**
     * Set the value of usageExample
     */
    public function setUsageExample(?string $usageExample): self
    {
        $this->usageExample = $usageExample;

        return $this;
    }

    /**
     * Get the value of tags
     */
    public function getTags(): ?string
    {
        return $this->tags;
    }

    /**
     * Set the value of tags
     */
    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get the value of visibility
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * Set the value of visibility
     */
    public function setVisibility(string $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * Get the value of allowComments
     */
    public function isAllowComments(): bool
    {
        return $this->allowComments;
    }

    /**
     * Set the value of allowComments
     */
    public function setAllowComments(bool $allowComments): self
    {
        $this->allowComments = $allowComments;

        return $this;
    }

    /**
     * Get the value of allowFork
     */
    public function isAllowFork(): bool
    {
        return $this->allowFork;
    }

    /**
     * Set the value of allowFork
     */
    public function setAllowFork(bool $allowFork): self
    {
        $this->allowFork = $allowFork;

        return $this;
    }

    /**
     * Get the value of views
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * Set the value of views
     */
    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get the value of authorName
     */
    public function getAuthorName(): ?string
    {
        return $this->authorName;
    }

    /**
     * Set the value of authorName
     */
    public function setAuthorName(?string $authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     */
    public function setUpdatedAt(string $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
