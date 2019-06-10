<?php
namespace App\Service;

use App\Entity\Document;
use App\Entity\DocumentFileFormat;
use App\Entity\FileFormat;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;

class DocumentService
{
  private $filesystem;

  public function __construct(Filesystem $filesystem)
  {

    $this->filesystem = $filesystem;
  }

  public function convert(DocumentFileFormat $documentFileFormat, FileFormat $newFormat) {

    

  }
}