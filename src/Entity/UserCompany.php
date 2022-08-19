<?php

namespace App\Entity;

use App\Repository\UserCompanyRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "detailUserCompanies",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUserCompany")
 * )
 *
 *
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "deleteUserCompanies",
 *          parameters = { "id" = "expr(object.getId())" },
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUserCompany", excludeIf = "expr(not is_granted('ROLE_ADMIN'))"),
 * )
 *
 * @Hateoas\Relation(
 *      "update",
 *      href = @Hateoas\Route(
 *          "updateUserCompanies",
 *          parameters = { "id" = "expr(object.getId())" },
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getUserCompany", excludeIf = "expr(not is_granted('ROLE_ADMIN'))"),
 * )
 *
 */
#[ORM\Entity(repositoryClass: UserCompanyRepository::class)]
class UserCompany
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getCompany", "getUserCompany"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCompany", "getUserCompany", "createUserCompany"])]
    #[Assert\NotBlank(message: "Le Nom du UserCompany est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le Nom doit faire au moins {{ limit }} caractères", maxMessage: "Le Nom ne peut pas faire plus de {{ limit }} caractères")]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getCompany", "getUserCompany", "createUserCompany"])]
    #[Assert\NotBlank(message: "Le Prénom du UserCompany est obligatoire")]
    #[Assert\Length(min: 1, max: 255, minMessage: "Le Prénom doit faire au moins {{ limit }} caractères", maxMessage: "Le Prénom ne peut pas faire plus de {{ limit }} caractères")]
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
