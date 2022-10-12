<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\AdherentCountController;
use App\Controller\AdherentPaidController;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[
    ORM\Entity,
    ApiResource(
        collectionOperations: [
            'get'   => [
                'parameters' => [],
                'openapi_context'    => [
                    'security' => [ [ 'bearerAuth' => [] ] ]
                ],
                'normalization_context' => [ 'groups' => [ 'read:adherents' ] ]
            ],
            'post',
            'count' => [
                'method'             => 'get',
                'path'               => '/adherents/count',
                'controller'         => AdherentCountController::class,
                'read'               => false,
                'pagination_enabled' => false,
                'filters'            => [],
                'openapi_context'    => [
                    'summary'    => 'Récupère le nombre total d\'adhérents',
                    'parameters' => [],
                    'responses'  => [
                        '200' => [
                            'description' => '2',
                            'content'     => [
                                'application/json' => [
                                    'schema' => [
                                        'type'    => 'integer',
                                        'example' => 1
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        itemOperations: [
            'put',
            'delete',
            'get'  => [
                'normalization_context' => [ 'groups' => [ 'read:adherents', 'write:adherent' ] ]
            ],
            'paid' => [
                'method'          => 'post',
                'path'            => '/adherents/{id}/paid',
                'controller'      => AdherentPaidController::class,
                'openapi_context' => [
                    'summary'     => 'Permet de valider le paiement',
                    'requestBody' => [
                        'content' => [
                            'application/json' => [
                                'schema' => []
                            ]
                        ]
                    ],
                ]
            ]
        ],
        denormalizationContext: [ 'groups' => [ 'write:adherent' ] ],
        normalizationContext: [ 'groups' => [ 'read:adherents' ] ],
    ),
    ApiFilter(
        SearchFilter::class,
        properties: [
        'id'        => 'exact',
        'firstName' => 'partial',
        'lastName'  => 'partial'
    ]
    ),
    ApiFilter(OrderFilter::class, properties: [
        'lastName'
    ], arguments: [ 'orderParameterName' => 'order' ])
]

class Adherent
{
	
    #[
        ORM\Id,
        ORM\GeneratedValue,
        ORM\Column(type: 'integer'),
        Groups([ 'read:adherents' ])
    ]
    private int $id;

    #[
        ORM\Column(type: 'string', length: 50, nullable: true),
        Assert\NotBlank,
        Assert\Type('string'),
        Assert\Length(
            min: 2,
            max: 50,
            minMessage: "Votre prénom doit contenir au mois {{ limit }} lettres de longueur",
            maxMessage: "Votre prénom ne peut pas dépasser {{ limit }} lettres de longueur",
        ),
        Groups([ 'read:adherents', 'write:adherent' ])
    ]
    private ?string $firstName;

    #[
        ORM\Column(type: 'string', length: 50, nullable: true),
        Assert\Type(
            type: 'string',
            message: 'Le champ doit être une chaîne de caratères'
        ),
        Assert\NotBlank,
        Assert\Length(
            min: 2,
            max: 50,
            minMessage: "Votre nom doit contenir au mois {{ limit }} lettres de longueur",
            maxMessage: "Votre nom ne peut pas dépasser {{ limit }} lettres de longueur",
        ),
        Groups([ 'read:adherents', 'write:adherent' ])
    ]
    private ?string $lastName;

    #[
        ORM\Column(type: 'datetime', nullable: true),
        Groups([ 'read:adherent', 'write:adherent' ])
    ]
    private ?DateTimeInterface $birthDate;

    #[
        ORM\Column(type: 'string', nullable: true),
        Assert\Type(
            type: 'string',
            message: 'Le champ doit être une chaîne de caratères'
        ),
        Assert\Regex(
            pattern: "/^0[0-9]{9}$/",
            message: "Le numéro de téléphone saisi n'est pas valable"
        ),
        Groups([ 'read:adherents', 'write:adherent' ])
    ]
    private ?string $phone;

    #[
        ORM\Column(type: 'string', length: 50, nullable: true),
        Assert\Type(
            type: 'string',
            message: 'Le champ doit être une chaîne de caratères'
        ),
        Groups([ 'read:adherents', 'write:adherent' ])
    ]
    private ?string $email;

    #[
        ORM\Column(type: 'string', length: 127, nullable: true),
        Assert\NotBlank,
        Assert\Type(
            type: 'string',
            message: 'Le champ doit être une chaîne de caratères'
        ),
        Assert\Length(
            min: 2,
            max: 124,
            minMessage: "Votre adresse doit contenir au mois {{ limit }} lettres",
            maxMessage: "Votre adresse ne peut pas dépasser {{ limit }} lettres",
        ),
        Groups([ 'read:adherent', 'write:adherent' ])
    ]
    private ?string $address;

    #[
        ORM\Column(type: 'datetime', nullable: true),
        Assert\NotBlank,
        Groups([ 'read:adherent' ])
    ]
    private \DateTimeInterface $createdAt;

    #[
        ORM\Column(type: 'datetime', nullable: true),
        Groups([ 'read:adherent' ])
    ]
    private ?DateTimeInterface $updatedAt;

    #[
        ORM\Column(type: 'datetime', nullable: true),
        Groups([ 'read:adherent', 'write:adherent' ])
    ]
    private ?DateTimeInterface $paidAt;

    #[
        ORM\Column(type: 'integer', options: [ 'default' => 0 ]),
        Assert\Type(
            type: 'integer',
            message: 'Le champ doit être un chiffre'
        ),
        Groups([ 'read:adherent', 'write:adherent' ])
    ]
    private ?int $payment = 0;

    #[
        ORM\Column(type: 'integer', options: [ 'default' => 0 ]),
        Assert\Type(
            type: 'integer',
            message: 'Le champ doit être un chiffre'
        ),
        Groups([ 'read:adherent', 'write:adherent' ])
    ]
    private ?int $receipt = 0;

    #[
        ORM\Column(type: 'integer', options: [ 'default' => 0 ]),
        Groups([ 'read:adherent', 'write:adherent' ]),
    ]
    private ?int $workshop = 0;

    #[
        ORM\ManyToOne(targetEntity: Town::class, cascade: [ "persist", "remove" ], inversedBy: 'adherents'),
        Groups([ 'read:adherent', 'write:adherent' ]),
        Assert\Valid()
    ]
    private ?Town $town;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return strtoupper($this->getLastName()) . ' ' . $this->getFirstName();
    }

    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPaidAt(): ?DateTimeInterface
    {
        return $this->paidAt;
    }


    /**
     * @param DateTimeInterface|null $paidAt
     *
     * @return Adherent
     */
    public function setPaidAt(?DateTimeInterface $paidAt): self
    {
        $this->paidAt = $paidAt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getPayment(): ?int
    {
        return $this->payment;
    }

    /**
     * @param int|null $payment
     *
     * @return Adherent
     */
    public function setPayment(?int $payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getReceipt(): ?int
    {
        return $this->receipt;
    }

    /**
     * @param mixed $receipt
     *
     * @return Adherent
     */
    public function setReceipt(?int $receipt): self
    {
        $this->receipt = $receipt;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getWorkshop(): ?int
    {
        return $this->workshop;
    }

    /**
     * @param mixed $workshop
     *
     * @return Adherent
     */
    public function setWorkshop(?int $workshop): self
    {
        $this->workshop = $workshop;

        return $this;
    }

    public function getTown(): ?Town
    {
        return $this->town;
    }

    public function setTown(?Town $town): self
    {
        $this->town = $town;

        return $this;
    }
}
