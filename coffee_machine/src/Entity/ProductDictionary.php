<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductDictionaryRepository")
 */
class ProductDictionary
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $product_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $product_active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $product_created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $product_updated;

    /**
      * @ORM\OneToMany(targetEntity="App\Entity\ProductCommunication", mappedBy="product_communication")
      */
    private $communication_product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    public function setProductName(string $product_name): self
    {
        $this->product_name = $product_name;

        return $this;
    }

    public function getProductActive(): ?bool
    {
        return $this->product_active;
    }

    public function setProductActive(bool $product_active): self
    {
        $this->product_active = $product_active;

        return $this;
    }

    public function getProductCreated(): ?\DateTimeInterface
    {
        return $this->product_created;
    }

    public function setProductCreated(\DateTimeInterface $product_created): self
    {
        $this->product_created = $product_created;

        return $this;
    }

    public function getProductUpdated(): ?\DateTimeInterface
    {
        return $this->product_updated;
    }

    public function setProductUpdated(\DateTimeInterface $product_updated): self
    {
        $this->product_updated = $product_updated;

        return $this;
    }
}
