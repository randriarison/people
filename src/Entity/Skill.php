<?php

namespace App\Entity;

use App\Repository\SkillRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillRepository::class)]
class Skill
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptiion = null;

    #[ORM\ManyToOne(inversedBy: 'skills')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Occupation $occupation = null;

    /**
     * @var Collection<int, PersonSkills>
     */
    #[ORM\OneToMany(targetEntity: PersonSkills::class, mappedBy: 'skill', orphanRemoval: true)]
    private Collection $persons;

    public function __construct()
    {
        $this->persons = new ArrayCollection();
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

    public function getDescriptiion(): ?string
    {
        return $this->descriptiion;
    }

    public function setDescriptiion(?string $descriptiion): static
    {
        $this->descriptiion = $descriptiion;

        return $this;
    }

    public function getOccupation(): ?Occupation
    {
        return $this->occupation;
    }

    public function setOccupation(?Occupation $occupation): static
    {
        $this->occupation = $occupation;

        return $this;
    }

    /**
     * @return Collection<int, PersonSkills>
     */
    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function addPerson(PersonSkills $person): static
    {
        if (!$this->persons->contains($person)) {
            $this->persons->add($person);
            $person->setSkill($this);
        }

        return $this;
    }

    public function removePerson(PersonSkills $person): static
    {
        if ($this->persons->removeElement($person)) {
            // set the owning side to null (unless already changed)
            if ($person->getSkill() === $this) {
                $person->setSkill(null);
            }
        }

        return $this;
    }
}
