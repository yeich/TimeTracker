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
     * @ORM\Column(type="datetime")
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
}
