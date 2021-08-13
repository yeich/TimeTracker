<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectRepository::class)
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $management;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="projects")
     */
    private $workers;

    /**
     * @ORM\ManyToOne(targetEntity=HourlyRate::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=true)
     */
    private $hourly_rate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hours_planned;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectState::class, inversedBy="projects")
     * @ORM\JoinColumn(nullable=true)
     */
    private $state;

    /**
     * @ORM\OneToMany(targetEntity=TimestampProject::class, mappedBy="project")
     */
    private $timestampProjects;

    public function __construct()
    {
        $this->workers = new ArrayCollection();
        $this->timestampProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsers(): Collection {

        $users = $this->workers;

        return $users;
    }

    public function getManagement(): ?User
    {
        return $this->management;
    }

    public function setManagement(?User $management): self
    {
        $this->management = $management;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getWorkers(): Collection
    {
        return $this->workers;
    }

    public function addWorker(User $worker): self
    {
        if (!$this->workers->contains($worker)) {
            $this->workers[] = $worker;
        }

        return $this;
    }

    public function removeWorker(User $worker): self
    {
        $this->workers->removeElement($worker);

        return $this;
    }

    public function getHourlyRate(): ?HourlyRate
    {
        return $this->hourly_rate;
    }

    public function setHourlyRate(?HourlyRate $hourly_rate): self
    {
        $this->hourly_rate = $hourly_rate;

        return $this;
    }

    public function getHoursPlanned(): ?int
    {
        return $this->hours_planned;
    }

    public function setHoursPlanned(?int $hours_planned): self
    {
        $this->hours_planned = $hours_planned;

        return $this;
    }

    public function getState(): ?ProjectState
    {
        return $this->state;
    }

    public function setState(?ProjectState $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection|TimestampProject[]
     */
    public function getTimestampProjects(): Collection
    {
        return $this->timestampProjects;
    }

    public function addTimestampProject(TimestampProject $timestampProject): self
    {
        if (!$this->timestampProjects->contains($timestampProject)) {
            $this->timestampProjects[] = $timestampProject;
            $timestampProject->setProject($this);
        }

        return $this;
    }

    public function removeTimestampProject(TimestampProject $timestampProject): self
    {
        if ($this->timestampProjects->removeElement($timestampProject)) {
            // set the owning side to null (unless already changed)
            if ($timestampProject->getProject() === $this) {
                $timestampProject->setProject(null);
            }
        }

        return $this;
    }
}
