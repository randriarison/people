<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Service\Interface\EmployedInterface;
use App\Service\Interface\NamedInterface;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
#[UniqueEntity(
    fields: ['name'],
    message: 'ce nom est déjà utilisé.'
)]
class Person implements NamedInterface, EmployedInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[Gedmo\Slug(fields: ['name'])]
    #[ORM\Column(length: 255)]
    private ?string $slug;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    /**
     * @var Collection<int, PersonSkills>
     */
    #[ORM\OneToMany(targetEntity: PersonSkills::class, mappedBy: 'person', orphanRemoval: true)]
    private Collection $Skill;

    #[ORM\ManyToOne(inversedBy: 'people')]
    private ?Occupation $occupation = null;

    /**
     * @var Collection<int, Work>
     */
    #[ORM\OneToMany(targetEntity: Work::class, mappedBy: 'assignedTo')]
    private Collection $works;

    #[ORM\OneToOne(inversedBy: 'person', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'people')]
    private Collection $teams;

    /**
     * @var Collection<int, Manager>
     */
    #[ORM\ManyToMany(targetEntity: Manager::class, inversedBy: 'people')]
    private Collection $managers;

    public function __construct()
    {
        $this->Skill = new ArrayCollection();
        $this->works = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->managers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
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
       return $this->name ? "je m'appelle $this->name \n" : '';
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
     * @return Collection<int, Work>
     */
    public function getWorks(): Collection
    {
        return $this->works;
    }

    public function addWork(Work $work): static
    {
        if (!$this->works->contains($work)) {
            $this->works->add($work);
            $work->setAssignedTo($this);
        }

        return $this;
    }

    public function removeWork(Work $work): static
    {
        if ($this->works->removeElement($work)) {
            // set the owning side to null (unless already changed)
            if ($work->getAssignedTo() === $this) {
                $work->setAssignedTo(null);
            }
        }

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
        }
        if ($team->getManager() !== null) {
            $this->addManager($team->getManager());
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        $this->teams->removeElement($team);
        if ($team->getManager() !== null && $this->getManagers()->contains($team->getManager())) {
            $this->removeManager($team->getManager());
        }

        return $this;
    }

    /**
     * @return Collection<int, Manager>
     */
    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function addManager(Manager $manager): static
    {
        if (!$this->managers->contains($manager)) {
            $this->managers->add($manager);
        }

        return $this;
    }

    public function removeManager(Manager $manager): static
    {
        $this->managers->removeElement($manager);

        return $this;
    }
}
