<?php

namespace Kms\Admin\Media\Uploader;

use Kms\Admin\Media\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploaderInterface
{
    public function upload(UploadedFile $file, string $directoryPath = null, string $filename = null): Media;

    public function getMediaBaseUrl(): string;
}
