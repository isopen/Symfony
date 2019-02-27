<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PriceDictionaryRepository")
 */
class PriceDictionary
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
    private $price_cost;

    /**
     * @ORM\Column(type="boolean")
     */
    private $price_active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $price_created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $price_updated;

    /**
      * @ORM\OneToMany(targetEntity="App\Entity\ProductCommunication", mappedBy="price")
      */
    private $communication_prices;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price_cost;
    }

    public function setPrice(int $cost): self
    {
        $this->price_cost = $cost;

        return $this;
    }

    public function getPriceActive(): ?bool
    {
        return $this->price_active;
    }

    public function setPriceActive(bool $price_active): self
    {
        $this->price_active = $price_active;

        return $this;
    }

    public function getPriceCreated(): ?\DateTimeInterface
    {
        return $this->price_created;
    }

    public function setPriceCreated(\DateTimeInterface $price_created): self
    {
        $this->price_created = $price_created;

        return $this;
    }

    public function getPriceUpdated(): ?\DateTimeInterface
    {
        return $this->price_updated;
    }

    public function setPriceUpdated(\DateTimeInterface $price_updated): self
    {
        $this->price_updated = $price_updated;

        return $this;
    }
}
