<?php declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CreateCommentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private Security $security,
    ) {
    }

    /** @param Comment $data */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): Comment {
        $comment = new Comment();

        $user = $this->security->getUser();
        if (!$user instanceof User) {
            throw new RuntimeException('Invalid user.');
        }

        $comment->author = $user;
        $comment->comment = $data->comment;

        // Gérer $data->content comme IRI ou entité
        if (is_string($data->content)) {
            $contentUuid = substr($data->content, strrpos($data->content, '/') + 1);
            $content = $this->em->getRepository('App\Entity\Content')->findOneBy(['uuid' => $contentUuid]);
            if (!$content) {
                throw new RuntimeException('Content not found for IRI: ' . $data->content);
            }
        } elseif ($data->content instanceof \App\Entity\Content) {
            $content = $data->content;
        } else {
            throw new RuntimeException('Invalid content type: ' . gettype($data->content));
        }

        $comment->content = $content;

        $this->em->persist($comment);
        $this->em->flush();

        return $comment;
    }
}