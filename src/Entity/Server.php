<?php

namespace App\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ServerRepository")
 * @UniqueEntity("name")
 */
class Server
{
    const STATUS_BLOCKED = 'blocked';
    const STATUS_DEPLOY = 'deploy';
    const STATUS_FREE = 'free';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $blockedSince;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $blockedBy;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * Server constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status = self::STATUS_FREE;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getBlockedSince(): ?\DateTimeInterface
    {
        return $this->blockedSince;
    }

    public function setBlockedSince(?\DateTimeInterface $blockedSince): self
    {
        $this->blockedSince = $blockedSince;

        return $this;
    }

    public function getBlockedBy(): ?string
    {
        return $this->blockedBy;
    }

    public function setBlockedBy(?string $blockedBy): self
    {
        $this->blockedBy = $blockedBy;

        return $this;
    }
}
