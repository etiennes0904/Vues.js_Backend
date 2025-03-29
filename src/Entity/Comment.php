<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Api\Filter\UuidFilter;
use App\Api\Processor\CreateCommentProcessor;
use App\Doctrine\Trait\TimestampableTrait;
use App\Doctrine\Trait\UuidTrait;
use App\Enum\RoleEnum;
use App\Enum\TableEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: TableEnum::COMMENT)]
#[ApiResource]
#[ORM\Entity]
#[GetCollection()]
#[Delete(security: 'is_granted("' . RoleEnum::ROLE_USER . '") and object.author == user or is_granted("' . RoleEnum::ROLE_ADMIN . '")')]
#[Post(processor: CreateCommentProcessor::class, security: 'is_granted("' . RoleEnum::ROLE_USER . '")')]
#[Put(denormalizationContext: ['groups' => ['comment:update']], security: 'is_granted("' . RoleEnum::ROLE_USER . '") and object.author == user or is_granted("' . RoleEnum::ROLE_ADMIN . '") and object.author == user')]
#[ApiFilter(SearchFilter::class, properties: ['content' => 'partial'])]
#[ApiFilter(UuidFilter::class, properties: ['author' => 'partial'])]
#[ApiProperty()]
class Comment
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Groups(['comment:update'])]
    public ?string $comment = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    public ?User $author = null;

    #[ORM\ManyToOne(targetEntity: Content::class)]
    #[ORM\JoinColumn(name: 'content_uuid', referencedColumnName: 'uuid', nullable: false)]
    #[Assert\NotBlank]
    public ?Content $content = null;

    public function __construct()
    {
        $this->defineUuid();
    }
}
