<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Toute personne qui accède au site peut s’inscrire sur le site à l’aide de son adresse email.
 * 
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    use TimestampableEntity;
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Adresse email et nom d’utilisateur
     *
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * 
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="phone_number", nullable=true)
     * 
     * @PhoneNumber(defaultRegion="BE")
     */
    private $gsm;

    /**
     * Adresse (rue, numéro, boite) :
     *
     * - l’adresse du demandeur n’est visible que pour le producteur de son masque
     * - l’adresse du producteur n’est visible que pour le demandeur
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $codePostal;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", length=180, nullable=true)
     */
    private $localite;

    /**
     * @ORM\Column(type="float")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     *
     * @Assert\NotBlank()
     * @Assert\Type(type="float")
     */
    private $longitude;


    /**
     * Le nom public est visible pour :
     * 
     * - tous les utilisateurs identifiés si c’est un producteur
     * - seulement le producteur assigné si c’est un demandeur
     *
     * @var string
     * 
     * @ORM\Column(type="string", length=180)
     * 
     * @Assert\NotBlank()
     */
    private $publicName;

    /**
     * Indique si l’utilisateur a confirmé l’existence de son adresse email.
     *
     * @var bool
     * 
     * @ORM\Column(type="boolean")
     * 
     * @Assert\Type(type="boolean")
     * @Assert\NotBlank()
     */
    private $isEmailValidated = false;

    /**
     * Indique si l’utilisateur a été banni par un admin.
     *
     * @var bool
     * 
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="boolean")
     * @Assert\NotBlank()
     */
    private $isBanned = false;

    /**
     * Date et heure de la dernière identification
     * 
     * @var DateTime
     * 
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateLastLogin;

    // Champs pour les demandeurs

    /**
     * Indique si le demandeur peut se déplacer
     *
     * @var bool
     * 
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="boolean")
     * @Assert\NotBlank()
     */
    private $canMoveToProducer = true;

    /**
     * Indique si le demandeur déclare être une personne à risque (pour prévenir le producteur).
     *
     * @var bool
     * 
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="boolean")
     * @Assert\NotBlank()
     */
    private $isPersonneRisque = false;

    /**
     * Indique si le demandeur est une institution cherchant des masques pour son personnel (maison de repos, hôpital, …)
     *
     * @var bool
     * 
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="boolean")
     * @Assert\NotBlank()
     */
    private $isCollectivite = false;

    // Champs pour les producteurs

    /**
     * Indique si la production est à venir chercher sur place ou livrée dans un rayon (rayon_distribution)
     *
     * @var bool
     * 
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="boolean")
     * @Assert\NotBlank()
     */
    private $isTakeaway = true;

    /**
     * Nombre de kilomètres maximum que le producteur peut effectuer pour la distribution
     *
     * @var int
     * 
     * @ORM\Column(type="integer", nullable=true)
     * 
     * @Assert\Type(type="integer")
     */
    private $rayonDistribution;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lot", mappedBy="producteur")
     */
    private $lots;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Masque", mappedBy="demandeur")
     */
    private $masquesDemandes;

    public function __construct()
    {
        $this->lots = new ArrayCollection();
        $this->masquesDemandes = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->publicName;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return mixed
     */
    public function getGsm()
    {
        return $this->gsm;
    }

    /**
     * @param mixed $gsm
     *
     * @return User
     */
    public function setGsm($gsm)
    {
        $this->gsm = $gsm;

        return $this;
    }

    /**
     * @return string
     */
    public function getAdresse(): string
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     *
     * @return User
     */
    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return string
     */
    public function getCodePostal(): string
    {
        return $this->codePostal;
    }

    /**
     * @param string $codePostal
     *
     * @return User
     */
    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocalite(): string
    {
        return $this->localite;
    }

    /**
     * @param string $localite
     *
     * @return User
     */
    public function setLocalite(string $localite): self
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     *
     * @return User
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     *
     * @return User
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublicName(): string
    {
        return $this->publicName;
    }

    /**
     * @param string $publicName
     *
     * @return User
     */
    public function setPublicName(string $publicName): self
    {
        $this->publicName = $publicName;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmailValidated(): bool
    {
        return $this->isEmailValidated;
    }

    /**
     * @param bool $isEmailValidated
     *
     * @return User
     */
    public function setIsEmailValidated(bool $isEmailValidated): self
    {
        $this->isEmailValidated = $isEmailValidated;

        return $this;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return $this->isBanned;
    }

    /**
     * @param bool $isBanned
     *
     * @return User
     */
    public function setIsBanned(bool $isBanned): self
    {
        $this->isBanned = $isBanned;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateLastLogin(): DateTime
    {
        return $this->dateLastLogin;
    }

    /**
     * @param DateTime $dateLastLogin
     *
     * @return User
     */
    public function setDateLastLogin(DateTime $dateLastLogin): self
    {
        $this->dateLastLogin = $dateLastLogin;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCanMoveToProducer(): bool
    {
        return $this->canMoveToProducer;
    }

    /**
     * @param bool $canMoveToProducer
     *
     * @return User
     */
    public function setCanMoveToProducer(bool $canMoveToProducer): self
    {
        $this->canMoveToProducer = $canMoveToProducer;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPersonneRisque(): bool
    {
        return $this->isPersonneRisque;
    }

    /**
     * @param bool $isPersonneRisque
     *
     * @return User
     */
    public function setIsPersonneRisque(bool $isPersonneRisque): self
    {
        $this->isPersonneRisque = $isPersonneRisque;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCollectivite(): bool
    {
        return $this->isCollectivite;
    }

    /**
     * @param bool $isCollectivite
     *
     * @return User
     */
    public function setIsCollectivite(bool $isCollectivite): self
    {
        $this->isCollectivite = $isCollectivite;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTakeaway(): bool
    {
        return $this->isTakeaway;
    }

    /**
     * @param bool $isTakeaway
     *
     * @return User
     */
    public function setIsTakeaway(bool $isTakeaway): self
    {
        $this->isTakeaway = $isTakeaway;

        return $this;
    }

    /**
     * @return int
     */
    public function getRayonDistribution(): int
    {
        return $this->rayonDistribution;
    }

    /**
     * @param int $rayonDistribution
     *
     * @return User
     */
    public function setRayonDistribution(int $rayonDistribution): self
    {
        $this->rayonDistribution = $rayonDistribution;

        return $this;
    }


    // Validation des données

    /**
     * Vérifie si le rayon de distribution est défini quand le producteur distribue sa production.
     * 
     * @param ExecutionContextInterface $context
     * @param $payload
     *
     * @Assert\Callback()
     */
    public function isValidRayonDistribution(ExecutionContextInterface $context, $payload)
    {
        if (!$this->isTakeaway and null === $this->rayonDistribution) {
            $context->buildViolation('user.is_takeaway.validation.rayon_distribution')
                ->atPath('rayonDistribution')
                ->addViolation();
        }
    }

    /**
     * Vérifie si l’adresse est complète, si l’utilisateur l’a fournie
     *
     * @param ExecutionContextInterface $context
     * @param $payload
     *
     * @Assert\Callback()
     */
    public function isValidAdresse(ExecutionContextInterface $context, $payload)
    {
        if (null === $this->adresse and null === $this->codePostal and null === $this->localite) {
            return;
        }

        if (null === $this->adresse) {
            $context->buildViolation('user.adresse.validation.adresse')
                    ->atPath('adresse')
                    ->addViolation();
        }

        if (null === $this->codePostal) {
            $context->buildViolation('user.adresse.validation.codePostal')
                    ->atPath('codePostal')
                    ->addViolation();
        }

        if (null === $this->localite) {
            $context->buildViolation('user.adresse.validation.localite')
                    ->atPath('localite')
                    ->addViolation();
        }
    }

    /**
     * @return Collection|Lot[]
     */
    public function getLots(): Collection
    {
        return $this->lots;
    }

    public function addLot(Lot $lot): self
    {
        if (!$this->lots->contains($lot)) {
            $this->lots[] = $lot;
            $lot->setProducteur($this);
        }

        return $this;
    }

    public function removeLot(Lot $lot): self
    {
        if ($this->lots->contains($lot)) {
            $this->lots->removeElement($lot);
            // set the owning side to null (unless already changed)
            if ($lot->getProducteur() === $this) {
                $lot->setProducteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Masque[]
     */
    public function getMasquesDemandes(): Collection
    {
        return $this->masquesDemandes;
    }

    public function addMasquesDemande(Masque $masquesDemande): self
    {
        if (!$this->masquesDemandes->contains($masquesDemande)) {
            $this->masquesDemandes[] = $masquesDemande;
            $masquesDemande->setDemandeur($this);
        }

        return $this;
    }

    public function removeMasquesDemande(Masque $masquesDemande): self
    {
        if ($this->masquesDemandes->contains($masquesDemande)) {
            $this->masquesDemandes->removeElement($masquesDemande);
            // set the owning side to null (unless already changed)
            if ($masquesDemande->getDemandeur() === $this) {
                $masquesDemande->setDemandeur(null);
            }
        }

        return $this;
    }
}
