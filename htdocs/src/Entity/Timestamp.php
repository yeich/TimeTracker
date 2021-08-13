<?php

namespace App\Entity;

use App\Repository\HourlyRateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class Timestamp
{

    /**
     * @ORM\Column(type="datetime")
     */
    private $start_stamp;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end_stamp;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="timestamps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStartStamp(): ?\DateTime
    {
        return $this->start_stamp;
    }

    public function setStartStamp(\DateTime $start_stamp): self
    {
        $this->start_stamp = $start_stamp;

        return $this;
    }

    public function getEndStamp(): ?\DateTime
    {
        return $this->end_stamp;
    }

    public function setEndStamp(\DateTime $end_stamp): self
    {
        $this->end_stamp = $end_stamp;

        return $this;
    }
}
