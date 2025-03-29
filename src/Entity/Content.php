<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Api\Filter\UuidFilter;
use App\Api\Processor\CreateContentProcessor;
use App\Doctrine\Trait\TimestampableTrait;
use App\Doctrine\Trait\UuidTrait;
use App\Enum\RoleEnum;
use App\Enum\TableEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: TableEnum::CONTENT)]
#[ApiResource]
#[Get(uriTemplate: '/contents/{slug}', uriVariables: ['slug'])]
#[GetCollection()]
#[Delete(security: 'is_granted("' . RoleEnum::ROLE_USER . '") or is_granted("' . RoleEnum::ROLE_ADMIN . '")', uriTemplate: '/contents/{slug}', uriVariables: ['slug'])]
#[Post(processor: CreateContentProcessor::class, security: 'is_granted("' . RoleEnum::ROLE_USER . '") or is_granted("' . RoleEnum::ROLE_ADMIN . '")')]
#[Put(security: 'is_granted("' . RoleEnum::ROLE_ADMIN . '")', uriTemplate: '/contents/{slug}', uriVariables: ['slug'])]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
#[ApiFilter(UuidFilter::class, properties: ['author' => 'partial'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt' => 'partial'])]
#[ApiFilter(DateFilter::class, properties: ['updatedAt' => 'exact'])]
class Content
{
    use TimestampableTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    public ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    public ?string $content = null;

    #[ORM\ManyToOne(targetEntity: Upload::class)]
    #[ORM\JoinColumn(name: 'upload_uuid', referencedColumnName: 'uuid', nullable: true, onDelete: 'CASCADE')]
    public ?Upload $cover = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    public ?string $metaTitle = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    public ?string $metaDescription = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_uuid', referencedColumnName: 'uuid', nullable: false, onDelete: 'CASCADE')]
    public ?User $author = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[ApiProperty(writable: false)]
    public ?string $slug = null;

    /**
     * @var array<int, string>
     */
    #[ORM\Column(type: Types::JSON)]
    public ?array $tags = [];

    public function __construct()
    {
        $this->defineUuid();
    }
}