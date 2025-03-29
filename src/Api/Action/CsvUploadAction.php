<?php declare(strict_types=1);

namespace App\Api\Action;

use App\Entity\Content;
use App\Entity\User;
use App\Service\FileUploadService;
use App\Service\SluggerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function count;

#[AsController]
class CsvUploadAction
{
    public function __construct(
        private FileUploadService $fileUploadService,
        private EntityManagerInterface $em,
        private SluggerService $sluggerService,
        private Security $security,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $file = $request->files->get('file');

        if (!$file instanceof UploadedFile) {
            throw new BadRequestHttpException('No valid file provided.');
        }

        $fileValidate = $this->fileUploadService->validateFile($file);

        $contents = $this->fileUploadService->processCsv($fileValidate);

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new BadRequestHttpException('Invalid user.');
        }

        foreach ($contents as $contentData) {
            $content = new Content();
            $content->title = $contentData['title'];
            $content->content = $contentData['content'];
            $content->metaTitle = $contentData['meta_title'];
            $content->metaDescription = $contentData['meta_description'];
            $content->tags = $contentData['tags'];

            $slug = $this->sluggerService->generateUniqueSlug($contentData['title']);
            $content->slug = $slug;

            $content->author = $user;

            $this->em->persist($content);
        }

        $this->em->flush();

        return new JsonResponse(['message' => 'CSV processed successfully', 'items' => count($contents)], 201);
    }
}
