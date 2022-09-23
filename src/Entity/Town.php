<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[
	ORM\Entity,
	ApiResource
]
class Town {
	#[
   		ORM\Id,
   		ORM\GeneratedValue,
   		ORM\Column( type: 'integer' ),
   		Groups( [ 'read:adherent'] )
   	]
   	private int $id;

	#[
   		ORM\Column( type: 'string', length: 50, nullable: true ),
   		Assert\NotBlank,
   		Assert\Length(
   			min: 2,
   			max: 50,
   			minMessage: "Votre ville doit contenir au mois {{ limit }} lettres de longueur",
   			maxMessage: "Votre ville ne peut pas dépasser {{ limit }} lettres de longueur",
   		),
   		Groups( [ 'read:post', 'write:adherent' ] )
   	]
   	private ?string $name;

	#[
   		ORM\Column( type: 'string', nullable: true ),
   		Assert\Regex(
   			pattern: "/^[0-9]{5}$/",
   			message: "Le code postal saisi n'est pas valable"
   		),
   
   	]
   	private ?string $postalCode;

	#[ORM\Column( type: 'datetime', nullable: true )]
   	private \DateTimeInterface $createdAt;

	#[ORM\Column( type: 'datetime', nullable: true )]
   	private ?\DateTimeInterface $updatedAt;

	#[ORM\OneToMany( mappedBy: 'town', targetEntity: Adherent::class )]
   	private Collection $adherents;

	public function __construct() {
   		$this->createdAt = new \DateTime();
   		$this->adherents = new ArrayCollection();
   	}

	public function getId(): ?int {
   		return $this->id;
   	}

	public function getName(): ?string {
   		return $this->name;
   	}

	public function setName( ?string $name ): self {
   		$this->name = $name;
   
   		return $this;
   	}

	public function getPostalCode(): ?string {
   		return $this->postalCode;
   	}

	public function setPostalCode( ?string $postalCode ): self {
   		$this->postalCode = $postalCode;
   
   		return $this;
   	}

	public function getCreatedAt(): ?\DateTimeInterface {
   		return $this->createdAt;
   	}

	public function setCreatedAt( ?\DateTimeInterface $createdAt ): self {
   		$this->createdAt = $createdAt;
   
   		return $this;
   	}

	public function getUpdatedAt(): ?\DateTimeInterface {
   		return $this->updatedAt;
   	}

	public function setUpdatedAt( ?\DateTimeInterface $updatedAt ): self {
   		$this->updatedAt = $updatedAt;
   
   		return $this;
   	}

	/**
	 * @return Collection<int, Adherent>
	 */
	public function getAdherents(): Collection {
   		return $this->adherents;
   	}

	public function addAdherent( Adherent $adherent ): self {
   		if ( ! $this->adherents->contains( $adherent ) ) {
   			$this->adherents[] = $adherent;
   			$adherent->setTown( $this );
   		}
   
   		return $this;
   	}

	public function removeAdherent( Adherent $adherent ): self {
   		if ( $this->adherents->removeElement( $adherent ) ) {
   			// set the owning side to null (unless already changed)
   			if ( $adherent->getTown() === $this ) {
   				$adherent->setTown( null );
   			}
   		}
   
   		return $this;
   	}

}
