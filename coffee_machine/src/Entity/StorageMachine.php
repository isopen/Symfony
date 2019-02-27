<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StorageMachineRepository")
 */
class StorageMachine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_coins;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalCoins(): ?int
    {
        return $this->total_coins;
    }
    public function setTotalCoins(int $total_coins): self
    {
      $this->total_coins = $total_coins;
      return $this;
    }
}
