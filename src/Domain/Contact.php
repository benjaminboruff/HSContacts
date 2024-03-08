<?php

namespace App\Domain;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContactRepository;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[ORM\Table(name: 'contacts')]
final class Contact
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(name: 'first_name', type: 'string', nullable: true)]
    private string $firstName;

    #[ORM\Column(name: 'last_name', type: 'string', nullable: true)]
    private string $lastName;

    #[ORM\Column(type: 'string', unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(type: 'string', unique: true, nullable: true)]
    private string $phone;

    #[ORM\Column(name: 'registered_at', type: 'datetime', nullable: false)]
    private DateTime $registeredAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getRegisteredAt(): DateTime
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(): void
    {
        $this->registeredAt = new DateTime("now");
    }
}
