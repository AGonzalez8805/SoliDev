<?php

namespace App\Models;

class Project
{
    private int $id;
    private int $ownerId;
    private string $title;
    private string $shortDescription;
    private string $description;
    private string $status;
    private array $technologies;
    private ?string $teamSize;
    private ?string $lookingFor;
    private ?string $repositoryUrl;
    private ?string $demoUrl;
    private ?string $documentationUrl;
    private ?string $coverImage;
    private string $createdAt;
    private string $updatedAt;
    private ?string $ownerName = null;
    private int $collaboratorsCount = 0;

    public function __construct(array $data)
    {
        $this->id = (int)($data['id'] ?? 0);
        $this->ownerId = (int)($data['owner_id'] ?? 0);
        $this->title = $data['title'] ?? '';
        $this->shortDescription = $data['short_description'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->collaboratorsCount = $data['collaborators_count'] ?? 0;

        // Gestion sécurisée de technologies
        $this->technologies = [];
        if (!empty($data['technologies'])) {
            $decoded = json_decode($data['technologies'], true);
            if (is_array($decoded)) {
                $this->technologies = $decoded;
            } elseif (is_string($data['technologies'])) {
                // Cas où la DB contient une chaîne séparée par des virgules
                $this->technologies = array_map('trim', explode(',', $data['technologies']));
            }
        }

        $this->teamSize = $data['team_size'] ?? null;
        $this->lookingFor = $data['looking_for'] ?? null;
        $this->repositoryUrl = $data['repository_url'] ?? null;
        $this->demoUrl = $data['demo_url'] ?? null;
        $this->documentationUrl = $data['documentation_url'] ?? null;
        $this->coverImage = $data['cover_image'] ?? null;
        $this->createdAt = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updatedAt = $data['updated_at'] ?? date('Y-m-d H:i:s');
        $this->ownerName = $data['owner_name'] ?? null;
    }


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
     * Get the value of ownerId
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    /**
     * Set the value of ownerId
     */
    public function setOwnerId(int $ownerId): self
    {
        $this->ownerId = $ownerId;

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
     * Get the value of shortDescription
     */
    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    /**
     * Set the value of shortDescription
     */
    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

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
     * Get the value of technologies
     */
    public function getTechnologies(): array
    {
        return $this->technologies;
    }

    /**
     * Set the value of technologies
     */
    public function setTechnologies(array $technologies): self
    {
        $this->technologies = $technologies;

        return $this;
    }

    /**
     * Get the value of teamSize
     */
    public function getTeamSize(): ?string
    {
        return $this->teamSize;
    }

    /**
     * Set the value of teamSize
     */
    public function setTeamSize(?string $teamSize): self
    {
        $this->teamSize = $teamSize;

        return $this;
    }

    /**
     * Get the value of lookingFor
     */
    public function getLookingFor(): ?string
    {
        return $this->lookingFor;
    }

    /**
     * Set the value of lookingFor
     */
    public function setLookingFor(?string $lookingFor): self
    {
        $this->lookingFor = $lookingFor;

        return $this;
    }

    /**
     * Get the value of repositoryUrl
     */
    public function getRepositoryUrl(): ?string
    {
        return $this->repositoryUrl;
    }

    /**
     * Set the value of repositoryUrl
     */
    public function setRepositoryUrl(?string $repositoryUrl): self
    {
        $this->repositoryUrl = $repositoryUrl;

        return $this;
    }

    /**
     * Get the value of demoUrl
     */
    public function getDemoUrl(): ?string
    {
        return $this->demoUrl;
    }

    /**
     * Set the value of demoUrl
     */
    public function setDemoUrl(?string $demoUrl): self
    {
        $this->demoUrl = $demoUrl;

        return $this;
    }

    /**
     * Get the value of documentationUrl
     */
    public function getDocumentationUrl(): ?string
    {
        return $this->documentationUrl;
    }

    /**
     * Set the value of documentationUrl
     */
    public function setDocumentationUrl(?string $documentationUrl): self
    {
        $this->documentationUrl = $documentationUrl;

        return $this;
    }

    /**
     * Get the value of coverImage
     */
    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    /**
     * Set the value of coverImage
     */
    public function setCoverImage(?string $coverImage): self
    {
        $this->coverImage = $coverImage;

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

    /**
     * Get the value of ownerName
     */
    public function getOwnerName(): ?string
    {
        return $this->ownerName;
    }

    /**
     * Set the value of ownerName
     */
    public function setOwnerName(?string $ownerName): self
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    /**
     * Get the value of collaboratorsCount
     */
    public function getCollaboratorsCount(): int
    {
        return $this->collaboratorsCount;
    }

    /**
     * Set the value of collaboratorsCount
     */
    public function setCollaboratorsCount(int $collaboratorsCount): self
    {
        $this->collaboratorsCount = $collaboratorsCount;

        return $this;
    }
}
