<?php declare(strict_types=1);

namespace App\Api\Resource;

use ApiPlatform\Metadata\Post;
use App\Api\Action\CsvUploadAction;
use App\Enum\RoleEnum;
use Symfony\Component\Validator\Constraints as Assert;

#[Post(controller: CsvUploadAction::class, deserialize: false, security: 'is_granted("' . RoleEnum::ROLE_ADMIN . '")')]
class CsvUpload
{
    #[Assert\NotBlank]
    public ?string $file = null;
}
