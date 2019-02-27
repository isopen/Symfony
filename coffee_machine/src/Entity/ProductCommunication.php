<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductCommunicationRepository")
 */
class ProductCommunication
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
    private $product_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $price_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $banknote_id;

    /**
      * @ORM\ManyToOne(targetEntity="App\Entity\ProductDictionary", inversedBy="communication_product")
      */
    private $product;

    /**
      * @ORM\ManyToOne(targetEntity="App\Entity\PriceDictionary", inversedBy="communication_price")
      */
    private $price;

    /**
      * @ORM\ManyToOne(targetEntity="App\Entity\BanknoteDictionary", inversedBy="communication_banknote")
      */
    private $banknote;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function getPriceId(): ?int
    {
        return $this->price_id;
    }

    public function getBanknoteId(): ?int
    {
        return $this->banknote_id;
    }

    public function getCommunicationActive(): ?bool
    {
        return $this->communication_active;
    }

    public function setCommunicationActive(bool $communication_active): self
    {
        $this->communication_active = $communication_active;

        return $this;
    }

    public function getCommunicationCreated(): ?\DateTimeInterface
    {
        return $this->communication_created;
    }

    public function setCommunicationCreated(\DateTimeInterface $communication_created): self
    {
        $this->communication_created = $communication_created;

        return $this;
    }

    public function getCommunicationUpdated(): ?\DateTimeInterface
    {
        return $this->communication_updated;
    }

    public function setCommunicationUpdated(\DateTimeInterface $communication_updated): self
    {
        $this->communication_updated = $communication_updated;

        return $this;
    }

    /**
     * @return ProductDictionary
     */
    public function getCommunicationProduct(): ?ProductDictionary
    {
        return $this->product;
    }

    public function setCommunicationProduct(ProductDictionary $product)
    {
        $this->product = $product;
    }

    /**
     * @return PriceDictionary
     */
    public function getCommunicationPrice(): ?PriceDictionary
    {
        return $this->price;
    }

    public function setCommunicationPrice(PriceDictionary $price)
    {
        $this->price = $price;
    }

    /**
     * @return BanknoteDictionary
     */
    public function getCommunicationBanknote(): ?BanknoteDictionary
    {
        return $this->banknote;
    }

    public function setCommunicationBanknote(BanknoteDictionary $banknote)
    {
        $this->banknote = $banknote;
    }
}
