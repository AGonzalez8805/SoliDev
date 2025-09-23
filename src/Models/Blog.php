<?php

namespace App\Models;

class Blog
{
    protected ?int $id = null;
    protected string $title;
    protected string $category;
    protected string $content;
    protected string $status = 'draft';
    protected ?string $cover_image = null;
    protected bool $allow_comments = true;
    protected bool $featured = false;
    protected string $created_at;
    protected string $updated_at;
    protected string $excerpt;

    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

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
     * Get the value of content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of cover_image
     */
    public function getCoverImage(): ?string
    {
        return $this->cover_image;
    }

    /**
     * Set the value of cover_image
     */
    public function setCoverImage(?string $cover_image): self
    {
        $this->cover_image = $cover_image;

        return $this;
    }

    /**
     * Get the value of created_at
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     */
    public function setCreatedAt(string $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the value of updated_at
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     */
    public function setUpdatedAt(string $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the value of excerpt
     */
    public function getExcerpt(): string
    {
        return $this->excerpt;
    }

    /**
     * Set the value of excerpt
     */
    public function setExcerpt(string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get the value of allow_comments
     */
    public function isAllowComments(): bool
    {
        return $this->allow_comments;
    }

    /**
     * Set the value of allow_comments
     */
    public function setAllowComments(bool $allow_comments): self
    {
        $this->allow_comments = $allow_comments;

        return $this;
    }

    /**
     * Get the value of featured
     */
    public function isFeatured(): bool
    {
        return $this->featured;
    }

    /**
     * Set the value of featured
     */
    public function setFeatured(bool $featured): self
    {
        $this->featured = $featured;

        return $this;
    }
}
