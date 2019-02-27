<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BanknoteDictionaryRepository")
 */
class BanknoteDictionary
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
    private $banknote_name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $banknote_active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $banknote_created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $banknote_updated;

    /**
      * @ORM\OneToMany(targetEntity="App\Entity\ProductCommunication", mappedBy="banknote")
      */
    private $communication_banknote;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBanknoteName(): ?string
    {
        return $this->banknote_name;
    }

    public function setBanknoteName(string $banknote_name): self
    {
        $this->banknote_name = $banknote_name;

        return $this;
    }

    public function getBanknoteActive(): ?bool
    {
        return $this->banknote_active;
    }

    public function setBanknoteActive(bool $banknote_active): self
    {
        $this->banknote_active = $banknote_active;

        return $this;
    }

    public function getBanknoteCreated(): ?\DateTimeInterface
    {
        return $this->banknote_created;
    }

    public function setBanknoteCreated(\DateTimeInterface $banknote_created): self
    {
        $this->banknote_created = $banknote_created;

        return $this;
    }

    public function getBanknoteUpdated(): ?\DateTimeInterface
    {
        return $this->banknote_updated;
    }

    public function setBanknoteUpdated(\DateTimeInterface $banknote_updated): self
    {
        $this->banknote_updated = $banknote_updated;

        return $this;
    }
}
