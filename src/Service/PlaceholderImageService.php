<?php

namespace App\Service;

use Doctrine\DBAL\Driver\OCI8\Exception\Error;
use Error;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PlaceholderImageService
{
    private string $saveDirectory;
    private FilenameGenerator $generator;
    private string $placeholderServiceUrl = "https://via.placeholder.com/";
    private int $minimumImageWidth = 150;
    private int $minimumImageHeight = 150;

    public function __construct(FilenameGenerator $generator, ParameterBagInterface $container)
    {
        $this->generator = $generator;
        $this->saveDirectory = $container->get("upload.directory");
    }

    /**
     * Return the downloaded image contents.
     * @param int $imageWidth
     * @param int $imageHeight
     * @return string
     * @throws Error
     */
    public function getNewImageStream(int $imageWidth, int $imageHeight): string
    {
        if ($imageWidth < $this->minimumImageWidth || $imageHeight < $this->minimumImageHeight) {
            throw new Error("The requested image format is too small, please provide us a larger format");
        }
        $contents = file_get_contents("{$this->placeholderServiceUrl}/{$imageWidth}x{$imageHeight}");
        if (!$contents) {
            throw new \Error("Placeholder image cannot be downloaded");
        }
        return $contents;
    }

    public function getNewImageAndSave(int $imageWidth, int $imageHeight, string $filename): bool
    {
        $file = __DIR__ . "/../../uploads/$filename" . $this->filenameGenerator->getUniqFilename();
        $contents = $this->getNewImageStream($imageWidth, $imageHeight);
        $bytes = file_put_contents($file, $contents);
        return file_exists($file) && $bytes;
    }
}