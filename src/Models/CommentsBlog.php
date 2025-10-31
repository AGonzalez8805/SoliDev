<?php

namespace App\Models;

class CommentsBlog extends Models
{
    protected ?int $id = null;
    protected int $blog_id;
    protected int $user_id;
    protected string $content;
    protected ?string $created_at = null;
    protected ?string $updated_at = null;

    // Champs supplémentaires (jointure)
    protected ?string $userName = null;
    protected ?string $userFirstName = null;
    protected ?string $userPhoto = null;

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
     * Get the value of blog_id
     */
    public function getBlogId(): int
    {
        return $this->blog_id;
    }

    /**
     * Set the value of blog_id
     */
    public function setBlogId(int $blog_id): self
    {
        $this->blog_id = $blog_id;
        return $this;
    }

    /**
     * Get the value of user_id
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     */
    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;
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
     * Get the value of created_at
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * Set the value of created_at
     */
    public function setCreatedAt(?string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    /**
     * Get the value of updated_at
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * Set the value of updated_at
     */
    public function setUpdatedAt(?string $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * Get the value of userName
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * Set the value of userName
     */
    public function setUserName(?string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * Get the value of userFirstName
     */
    public function getUserFirstName(): ?string
    {
        return $this->userFirstName;
    }

    /**
     * Set the value of userFirstName
     */
    public function setUserFirstName(?string $userFirstName): self
    {
        $this->userFirstName = $userFirstName;
        return $this;
    }

    /**
     * Get the value of userPhoto
     */
    public function getUserPhoto(): ?string
    {
        return $this->userPhoto;
    }

    /**
     * Set the value of userPhoto
     */
    public function setUserPhoto(?string $userPhoto): self
    {
        $this->userPhoto = $userPhoto;
        return $this;
    }

    /**
     * Get full name of the user
     */
    public function getUserFullName(): string
    {
        $fullName = trim(($this->userFirstName ?? '') . ' ' . ($this->userName ?? ''));
        return $fullName ?: 'Utilisateur';
    }
}
