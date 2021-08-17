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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projectsManagement")
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

    /**
     * @ORM\OneToMany(targetEntity=ProjectUpdate::class, mappedBy="project")
     */
    private $projectUpdates;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectPriority::class, inversedBy="projects")
     */
    private $projectPriority;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $deadline;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $finised_at;

    public function __construct()
    {
        $this->workers = new ArrayCollection();
        $this->timestampProjects = new ArrayCollection();
        $this->projectUpdates = new ArrayCollection();
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

    /**
     * @return Collection|ProjectUpdate[]
     */
    public function getProjectUpdates(): Collection
    {
        return $this->projectUpdates;
    }

    public function addProjectUpdate(ProjectUpdate $projectUpdate): self
    {
        if (!$this->projectUpdates->contains($projectUpdate)) {
            $this->projectUpdates[] = $projectUpdate;
            $projectUpdate->setProject($this);
        }

        return $this;
    }

    public function removeProjectUpdate(ProjectUpdate $projectUpdate): self
    {
        if ($this->projectUpdates->removeElement($projectUpdate)) {
            // set the owning side to null (unless already changed)
            if ($projectUpdate->getProject() === $this) {
                $projectUpdate->setProject(null);
            }
        }

        return $this;
    }

    public function getProjectPriority(): ?ProjectPriority
    {
        return $this->projectPriority;
    }

    public function setProjectPriority(?ProjectPriority $projectPriority): self
    {
        $this->projectPriority = $projectPriority;

        return $this;
    }

    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    public function setDeadline(?\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    public function getFinisedAt(): ?\DateTimeInterface
    {
        return $this->finised_at;
    }

    public function setFinisedAt(?\DateTimeInterface $finised_at): self
    {
        $this->finised_at = $finised_at;

        return $this;
    }
}
