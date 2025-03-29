<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\Content;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SluggerService
{
    public function __construct(
        private SluggerInterface $slugger,
        private EntityManagerInterface $em,
    ) {
    }

    public function generateUniqueSlug(string $title): string
    {
        $slug = $this->slugger->slug($title)->lower()->toString();

        $originalSlug = $slug;
        $i = 1;

        while ($this->em->getRepository(Content::class)->findOneBy(['slug' => $slug])) {
            $slug = $originalSlug . '-' . $i++;
        }

        return $slug;
    }
}
