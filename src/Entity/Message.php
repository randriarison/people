<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\ManyToOne(inversedBy: 'author')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conversation $conversation = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(nullable: true)]
    private ?bool $seen = null;

    /**
     * @var Collection<int, SeenBy>
     */
    #[ORM\OneToMany(targetEntity: SeenBy::class, mappedBy: 'message', orphanRemoval: true)]
    private Collection $seensBy;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    public function __construct()
    {
        $this->seensBy = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function getHumanDate(): ?string
    {
        $now = new \DateTime();
        if ($this->creationDate->format('Y-m-d') === $now->format('Y-m-d')) {
            return $this->creationDate->format('H:i');
        }
        return $this->creationDate->format('d/m/Y H:i');
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }
    #[ORM\PrePersist]
    public function setCreationDateValue(): void
    {
        $now = new \DateTimeImmutable();
        $this->creationDate = $now;
        $this->getConversation()->setUpdateDate($now);
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function isSeen(): ?bool
    {
        return $this->seen;
    }

    public function setSeen(?bool $seen): static
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * @return Collection<int, SeenBy>
     */
    public function getSeensBy(): Collection
    {
        return $this->seensBy;
    }

    public function addSeenBy(SeenBy $seenBy): static
    {
        if (!$this->seensBy->contains($seenBy)) {
            if (!$this->conversation->getUsers()->contains($seenBy->getUser())) {
                throw new \Exception("L'utilisateur n'est pas dans la conversation");
            }
            $this->seensBy->add($seenBy);
            $seenBy->setMessage($this);
        }

        return $this;
    }

    public function removeSeenBy(SeenBy $seenBy): static
    {
        if ($this->seensBy->removeElement($seenBy)) {
            // set the owning side to null (unless already changed)
            if ($seenBy->getMessage() === $this) {
                $seenBy->setMessage(null);
            }
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }
}
