<?php

namespace Kms\Admin\Media\Uploader;

use Kms\Admin\Media\Entity\Media;
use Kms\Admin\Media\Factory\CreateFromString;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class Uploader implements UploaderInterface
{
    public function __construct(
        private readonly FilesystemAdapter $adapter,
        private readonly SluggerInterface $slugger,
        private readonly CreateFromString $createFromString,
        private readonly string $kmsMediaBaseUrl
    ) {
    }

    public function upload(UploadedFile $file, string $directoryPath = null, string $filename = null): Media
    {
        if (null === $filename) {
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = strtolower($this->slugger->slug($filename));
            $filename = $filename.'-'.(new \DateTime())->format('YmdHis').'.'.$file->guessExtension();
        }

        $filesystem = new Filesystem($this->adapter);
        $filesystem->write($filename, file_get_contents($file->getPathname()));

        return $this->createFromString->create($filename);
    }

    public function getMediaBaseUrl(): string
    {
        return $this->kmsMediaBaseUrl;
    }
}
