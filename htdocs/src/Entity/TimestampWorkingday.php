<?php

namespace App\Entity;

use App\Repository\TimestampProjectRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TimestampWorkingdayRepository::class)
 */
class TimestampWorkingday extends Timestamp
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
