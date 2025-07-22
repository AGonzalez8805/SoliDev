<?php

namespace App\Models;

class Blog
{
    protected ?int $id = null;
    protected string $title;
    protected string $description;

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
     * Get the value of descrption
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set the value of descrption
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}