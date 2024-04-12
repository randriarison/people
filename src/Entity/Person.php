<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    /**
     * @var Collection<int, PersonSkills>
     */
    #[ORM\OneToMany(targetEntity: PersonSkills::class, mappedBy: 'person', orphanRemoval: true)]
    private Collection $Skill;

    public function __construct()
    {
        $this->Skill = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function introduceMyself(): string
    {
        $sentence = 'My name is ' . $name;
        return $sentence;
    }

    /**
     * @return Collection<int, PersonSkills>
     */
    public function getSkill(): Collection
    {
        return $this->Skill;
    }

    public function addSkill(PersonSkills $skill): static
    {
        if (!$this->Skill->contains($skill)) {
            $this->Skill->add($skill);
            $skill->setPerson($this);
        }

        return $this;
    }

    public function removeSkill(PersonSkills $skill): static
    {
        if ($this->Skill->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getPerson() === $this) {
                $skill->setPerson(null);
            }
        }

        return $this;
    }
}
