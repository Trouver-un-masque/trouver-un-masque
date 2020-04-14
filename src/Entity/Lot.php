<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LotRepository")
 */
class Lot
{
    use TimestampableEntity;
    
    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_PROCESSED = 2;
    const STATUS_CANCELLED = -1;
    
    private static $statusOptions = [
        'lot.status.pending'    => self::STATUS_PENDING,
        'lot.status.processing' => self::STATUS_PROCESSING,
        'lot.status.processed'  => self::STATUS_PROCESSED,
        'lot.status.cancelled'  => self::STATUS_CANCELLED,
    ];
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="lots")
     * @ORM\JoinColumn(nullable=false)
     */
    private $producteur;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDelivered;

    /**
     * @ORM\Column(type="date")
     */
    private $dateProductionPrevue;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateProductionPrete;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $instructionsPaiement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBanned;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantitePrevue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantitePrete;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMaterielRequis;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $materielRequis;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Masque", mappedBy="lot")
     */
    private $masques;

    public function __construct()
    {
        $this->masques = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProducteur(): ?User
    {
        return $this->producteur;
    }

    public function setProducteur(?User $producteur): self
    {
        $this->producteur = $producteur;

        return $this;
    }

    /**
     * Retourne les valeurs possibles pour la colonne `status`.
     * 
     * @return array
     */
    public static function getStatusOptions()
    {
        return self::$statusOptions;
    }

    public function getStatusLabel(): string
    {
        return array_search($this->getStatus(), self::$statusOptions);
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getIsDelivered(): ?bool
    {
        return $this->isDelivered;
    }

    public function setIsDelivered(bool $isDelivered): self
    {
        $this->isDelivered = $isDelivered;

        return $this;
    }

    public function getDateProductionPrevue(): ?\DateTimeInterface
    {
        return $this->dateProductionPrevue;
    }

    public function setDateProductionPreevue(\DateTimeInterface $dateProductionPrevue): self
    {
        $this->dateProductionPrevue = $dateProductionPrevue;

        return $this;
    }

    public function getDateProductionPrete(): ?\DateTimeInterface
    {
        return $this->dateProductionPrete;
    }

    public function setDateProductionPrete(?\DateTimeInterface $dateProductionPrete): self
    {
        $this->dateProductionPrete = $dateProductionPrete;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getInstructionsPaiement(): ?string
    {
        return $this->instructionsPaiement;
    }

    public function setInstructionsPaiement(?string $instructionsPaiement): self
    {
        $this->instructionsPaiement = $instructionsPaiement;

        return $this;
    }

    public function getIsBanned(): ?bool
    {
        return $this->isBanned;
    }

    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    public function getQuantitePrevue(): ?int
    {
        return $this->quantitePrevue;
    }

    public function setQuantitePrevue(int $quantitePrevue): self
    {
        $this->quantitePrevue = $quantitePrevue;

        return $this;
    }

    public function getQuantitePrete(): ?int
    {
        return $this->quantitePrete;
    }

    public function setQuantitePrete(?int $quantitePrete): self
    {
        $this->quantitePrete = $quantitePrete;

        return $this;
    }

    public function getIsMaterielRequis(): ?bool
    {
        return $this->isMaterielRequis;
    }

    public function setIsMaterielRequis(bool $isMaterielRequis): self
    {
        $this->isMaterielRequis = $isMaterielRequis;

        return $this;
    }

    public function getMaterielRequis(): ?string
    {
        return $this->materielRequis;
    }

    public function setMaterielRequis(?string $materielRequis): self
    {
        $this->materielRequis = $materielRequis;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
    
    /**
     * @return Collection|Masque[]
     */
    public function getMasques(): Collection
    {
        return $this->masques;
    }

    public function addMasque(Masque $masque): self
    {
        if (!$this->masques->contains($masque)) {
            $this->masques[] = $masque;
            $masque->setLot($this);
        }

        return $this;
    }

    public function removeMasque(Masque $masque): self
    {
        if ($this->masques->contains($masque)) {
            $this->masques->removeElement($masque);
            // set the owning side to null (unless already changed)
            if ($masque->getLot() === $this) {
                $masque->setLot(null);
            }
        }

        return $this;
    }
}
