<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Content;
use App\Entity\User;
use App\Service\SluggerService;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CreateContentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
        private SluggerService $sluggerService,
    ) {
    }

    /** @param Content $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Content {
        $content = new Content();

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new RuntimeException('Invalid user.');
        }

        $content->author = $user;

        $content->title = $data->title;

        $content->content = $data->content;

        $content->cover = $data->cover;

        $content->tags = $data->tags;

        $content->metaTitle = $data->metaTitle;

        $content->metaDescription = $data->metaDescription;

        $content->slug = $this->sluggerService->generateUniqueSlug($data->title);

        $this->em->persist($content);
        $this->em->flush();

        return $content;
    }
}
