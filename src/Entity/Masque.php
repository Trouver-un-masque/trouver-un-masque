<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Les masques sont tous les masques produits ou demandés.
 * 
 * Quand un demandeur cherche un masque et qu’aucun masque n’est produit près de chez lui, il crée un ou plusieurs masques.
 * 
 * Quand un producteur crée un lot, autant de masques sont créés que de masques à produire dans le lot. 
 * Ceci permet aux demandeurs de réserver les masques à produire.
 * 
 * Les demandes spontanées ont deux destinées possibles :
 * 
 * - soit un producteur les intègre à l’un de ses lots (uniquement s’il distribue)
 * - soit la demande spontanée est supprimée au moment où l’utilisateur qui a créé la demande 
 *   réserve un masque lié à un lot (ou plusieurs masques, suivant les cas).
 * 
 * 
 * @ORM\Entity(repositoryClass="App\Repository\MasqueRepository")
 */
class Masque
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="masquesDemandes")
     */
    private $demandeur;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDemandeSpontanee;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lot", inversedBy="masques")
     */
    private $lot;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDelivered;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDemandeur(): ?User
    {
        return $this->demandeur;
    }

    public function setDemandeur(?User $demandeur): self
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getIsDemandeSpontanee(): ?bool
    {
        return $this->isDemandeSpontanee;
    }

    public function setIsDemandeSpontanee(bool $isDemandeSpontanee): self
    {
        $this->isDemandeSpontanee = $isDemandeSpontanee;

        return $this;
    }

    public function getLot(): ?Lot
    {
        return $this->lot;
    }

    public function setLot(?Lot $lot): self
    {
        $this->lot = $lot;

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
}
