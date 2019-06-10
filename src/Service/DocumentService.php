<?php
namespace App\Service;

use App\Entity\Document;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;

class DocumentService
{
  private $filesystem;

  public function __construct(Filesystem $filesystem)
  {

    $this->filesystem = $filesystem;
  }

  public function move(Document $document, $filepath) {
    $originalFilename = "media\\" . $document->getFilePath();
    $originalFileInfo = new SplFileInfo($originalFilename);

    $this->filesystem->copy($originalFilename, $filepath);
    $document->setFilePath($filepath);

  }
}