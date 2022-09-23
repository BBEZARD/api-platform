<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[
	ORM\Entity,
	ApiResource
]
class Receipt {

	#[
   		ORM\Id,
   		ORM\GeneratedValue,
   		ORM\Column( type: 'integer' )
   	]
   	private int $id;

	#[
   		ORM\Column( type: 'integer' ),
   		Assert\Length(
   			min: 1,
   			max: 4,
   			minMessage: "Votre montant doit contenir au mois {{ limit }} chiffre",
   			maxMessage: "Votre montant ne peut pas dépasser {{ limit }} chiffres",
   		)
   	]
   	private ?int $donation;

	#[ORM\Column( type: 'integer', unique: true )]
   	private ?int $number;

	#[
   		ORM\Column( type: 'datetime', nullable: true ),
   		Assert\NotBlank
   	]
   	private \DateTimeInterface $createdAt;

	#[ORM\Column( type: 'datetime', nullable: true )]
   	private ?\DateTimeInterface $updatedAt;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	#[ORM\Column( type: 'string', length: 255, nullable: true )]
   	private ?string $comment;

	#[
   		ORM\Column( type: 'string', length: 255 ),
   		Assert\NotBlank,
   		Assert\Length(
   			min: 2,
   			max: 50,
   			minMessage: "Votre commentaire doit contenir au mois {{ limit }} lettres",
   			maxMessage: "Votre commentaire ne peut pas dépasser {{ limit }} lettres",
   		)
   	]
   	private ?string $donationLetter;

	public function __construct() {
   		$this->createdAt = new \DateTime();
   	}

	public function getId(): ?int {
   		return $this->id;
   	}

	public function getDonation(): ?int {
   		return $this->donation;
   	}

	public function setDonation( int $donation ): self {
   		$this->donation = $donation;
   
   		return $this;
   	}


	public function getNumber(): ?int {
   		return $this->number;
   	}

	public function setNumber( int $number ): self {
   		$this->number = $number;
   
   		return $this;
   	}

	public function getCreatedAt(): ?\DateTimeInterface {
   		return $this->createdAt;
   	}

	public function setCreatedAt( \DateTimeInterface $createdAt ): self {
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

	public function getComment(): ?string {
   		return $this->comment;
   	}

	public function setComment( ?string $comment ): self {
   		$this->comment = $comment;
   
   		return $this;
   	}

	public function getDonationLetter(): ?string {
   		return $this->donationLetter;
   	}

	public function setDonationLetter( string $donationLetter ): self {
   		$this->donationLetter = $donationLetter;
   
   		return $this;
   	}

}
