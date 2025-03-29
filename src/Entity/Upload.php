<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Api\Action\UploadAction;
use App\Doctrine\Trait\TimestampableTrait;
use App\Doctrine\Trait\UuidTrait;
use App\Enum\RoleEnum;
use App\Enum\TableEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]
#[ORM\Table(name: TableEnum::UPLOAD)]
#[Get]
#[Post(controller: UploadAction::class, deserialize: false, security: 'is_granted("' . RoleEnum::ROLE_USER . '") or is_granted("' . RoleEnum::ROLE_ADMIN . '")')]
#[ApiFilter(DateFilter::class, properties: ['createdAt' => 'exact'])]
class Upload
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column]
    public ?string $path = null;

    public function __construct(
        string $path,
    ) {
        $this->defineUuid();
        $this->path = $path;
    }
}