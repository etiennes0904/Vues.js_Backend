<?php declare(strict_types=1);

namespace App\Api\Resource;

use ApiPlatform\Metadata\Post;
use App\Api\Model\Token;
use Symfony\Component\Validator\Constraints as Assert;

#[Post(uriTemplate: '/login', routeName: 'api_login', output: Token::class)]
class Login
{
    #[Assert\NotBlank]
    public ?string $email = null;

    #[Assert\NotBlank]
    public ?string $password = null;
}
