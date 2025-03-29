<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Patch; // Ajout du support PATCH
use App\Api\Processor\CreateUserProcessor;
use App\Api\Resource\CreateUser;
use App\Doctrine\Trait\TimestampableTrait;
use App\Doctrine\Trait\UuidTrait;
use App\Enum\RoleEnum;
use App\Enum\TableEnum;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource]
#[Post(input: CreateUser::class, processor: CreateUserProcessor::class)]
#[Delete(security: 'is_granted("' . RoleEnum::ROLE_USER . '") and object == user or is_granted("' . RoleEnum::ROLE_ADMIN . '")')]
#[Put(security: 'is_granted("' . RoleEnum::ROLE_USER . '") and object == user or is_granted("' . RoleEnum::ROLE_ADMIN . '")')]
#[Patch(security: 'is_granted("' . RoleEnum::ROLE_ADMIN . '")')]  // Ajout du support PATCH pour les admins
#[GetCollection()]
#[Get()]
#[ORM\Table(name: TableEnum::USER)]
#[ApiFilter(DateFilter::class, properties: ['createdAt' => 'partial'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column]
    #[Assert\NotBlank]
    public ?string $firstName = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    public ?string $lastName = null;

    #[ORM\Column(unique: true)]
    #[Assert\Email]
    #[ApiProperty(writable: true)] // Rendu writable pour permettre les mises à jour
    public ?string $email = null;

    #[ORM\Column]
    #[Ignore]
    public ?string $password = null;

    /**
     * @var array<int, string>
     */
    #[ORM\Column]
    public array $roles = [];

    public function __construct()
    {
        $this->defineUuid();
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * Retourne les rôles de l'utilisateur
     * ROLE_USER est ajouté automatiquement sauf si l'utilisateur est ROLE_ADMIN
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // N'ajoute ROLE_USER que si l'utilisateur n'est pas déjà ROLE_ADMIN
        if (!in_array('ROLE_ADMIN', $roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    /**
     * Set les rôles de l'utilisateur
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function eraseCredentials(): void
    {
    }
}