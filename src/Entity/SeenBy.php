<?php

namespace App\Entity;

use App\Repository\SeenByRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeenByRepository::class)]
class SeenBy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $seenDate = null;

    #[ORM\ManyToOne(inversedBy: 'seensBy')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Message $message = null;

    #[ORM\ManyToOne(inversedBy: 'seenMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeenDate(): ?\DateTimeInterface
    {
        return $this->seenDate;
    }

    public function setSeenDate(\DateTimeInterface $seenDate): static
    {
        $this->seenDate = $seenDate;

        return $this;
    }

    public function getMessage(): ?Message
    {
        return $this->message;
    }

    public function setMessage(?Message $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
