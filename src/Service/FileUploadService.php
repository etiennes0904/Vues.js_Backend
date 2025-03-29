<?php declare(strict_types=1);

namespace App\Service;

use App\Enum\UploadEnum;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use function in_array;
use const PATHINFO_FILENAME;

class FileUploadService
{
    private const MAX_FILE_SIZE = 2 * 1024 * 1024;

    public function handleFileUpload(UploadedFile $file, string $projectDir): string
    {
        $this->validateFile($file);

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $this->sanitizeFileName($originalName);
        $extension = $file->getClientOriginalExtension();

        $fileName = $this->generateUniqueFileName($safeName, $extension, $projectDir . '/public/medias');
        $targetDir = $projectDir . '/public/medias';

        $file->move($targetDir, $fileName);

        return "/medias/{$fileName}";
    }

    public function validateFile(UploadedFile $file): UploadedFile
    {
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new BadRequestHttpException('The file size exceeds the allowed limit of 2 MB.');
        }

        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, UploadEnum::ALL, true)) {
            throw new BadRequestHttpException('Unauthorized file type.');
        }

        return $file;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function processCsv(UploadedFile $file): array
    {
        $data = [];

        if (($handle = fopen($file->getPathname(), 'r')) !== false) {
            $headers = fgetcsv($handle);

            if (!$headers) {
                throw new BadRequestHttpException('CSV file is empty or invalid.');
            }

            $expectedHeaders = ['title', 'content', 'meta_title', 'meta_description', 'tags'];
            if (array_diff($expectedHeaders, $headers)) {
                throw new BadRequestHttpException('Invalid CSV format.');
            }

            while (($row = fgetcsv($handle)) !== false) {
                $rowData = array_combine($headers, $row);
                $rowData['tags'] = explode(',', $rowData['tags']);
                $data[] = $rowData;
            }

            fclose($handle);
        }

        return $data;
    }

    private function sanitizeFileName(string $name): string
    {
        return preg_replace('/[^a-zA-Z0-9_\-]/', '_', $name);
    }

    private function generateUniqueFileName(string $baseName, string $extension, string $targetDir): string
    {
        $fileName = "{$baseName}.{$extension}";
        $counter = 1;

        while (file_exists($targetDir . '/' . $fileName)) {
            $fileName = "{$baseName}_{$counter}.{$extension}";
            $counter++;
        }

        return $fileName;
    }
}
