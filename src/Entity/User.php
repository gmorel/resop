<?php

declare(strict_types=1);

namespace App\Entity;

use Assert\Assertion;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\String\u;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users", uniqueConstraints={
 *   @ORM\UniqueConstraint(name="user_identification_number_unique", columns={"identification_number"}),
 *   @ORM\UniqueConstraint(name="user_email_address_unique", columns={"email_address"})
 * }, indexes={
 *   @ORM\Index(name="user_skill_set_idx", columns={"skill_set"}),
 *   @ORM\Index(name="user_vulnerable_idx", columns={"vulnerable"}),
 *   @ORM\Index(name="user_fully_equipped_idx", columns={"fully_equipped"}),
 * }))
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("emailAddress")
 * @UniqueEntity("identificationNumber")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    public ?int $id = null;

    /**
     * @ORM\Column
     * @Assert\NotBlank
     */
    private string $identificationNumber = '';

    /**
     * @ORM\Column
     * @Assert\NotBlank
     * @Assert\Email
     */
    public string $emailAddress = '';

    /**
     * @ORM\Column
     * @Assert\NotBlank
     */
    public string $firstName = '';

    /**
     * @ORM\Column
     * @Assert\NotBlank
     */
    public string $lastName = '';

    /**
     * @ORM\Column
     * @Assert\NotBlank
     */
    public string $phoneNumber = '';

    /**
     * @var string A "Y-m-d" formatted value
     *
     * @ORM\Column
     * @Assert\NotBlank
     * @Assert\Date
     */
    public string $birthday = '';

    /**
     * @ORM\Column
     * @Assert\NotBlank
     */
    public string $occupation = '';

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Organization")
     */
    public ?Organization $organization = null;

    /**
     * @ORM\Column(nullable=true)
     * @Assert\NotBlank
     */
    public string $organizationOccupation = '';

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    public array $skillSet = [];

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $vulnerable = false;

    /**
     * @ORM\Column(type="boolean")
     */
    public bool $fullyEquipped = false;

    public static function normalizeIdentificationNumber(string $identificationNumber): string
    {
        return u($identificationNumber)->trimStart('0')->toString();
    }

    public static function normalizeEmailAddress(string $emailAddress): string
    {
        return u($emailAddress)->trim()->lower()->toString();
    }

    public function __toString(): string
    {
        if (null === $this->organization) {
            return $this->getFullName();
        }

        return $this->organization->name.' / '.$this->getFullName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setIdentificationNumber(string $identificationNumber): void
    {
        $identificationNumber = self::normalizeIdentificationNumber($identificationNumber);

        Assertion::notEmpty($identificationNumber);

        $this->identificationNumber = $identificationNumber;
    }

    public function getIdentificationNumber(): string
    {
        return (string) $this->identificationNumber;
    }

    public function setEmailAddress(string $emailAddress): void
    {
        $emailAddress = self::normalizeEmailAddress($emailAddress);

        Assertion::email($emailAddress);

        $this->emailAddress = $emailAddress;
    }

    public function getEmailAddress(): ?string
    {
        return (string) $this->emailAddress;
    }

    public function getFullName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function getShortFullName(): string
    {
        return $this->firstName.' '.substr($this->lastName ?: '', 0, 1).'.';
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return '';
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return (string) $this->identificationNumber;
    }

    public function eraseCredentials(): void
    {
    }
}
