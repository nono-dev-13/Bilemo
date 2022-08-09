<?php

namespace App\Entity;

use App\Repository\UserCompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserCompanyRepository::class)]
class UserCompany
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getCompany", "getUserCompany"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCompany", "getUserCompany"])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCompany", "getUserCompany"])]
    private ?string $firstname = null;

    #[ORM\ManyToOne(inversedBy: 'userCompanies')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getUserCompany"])]
    private ?Company $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
