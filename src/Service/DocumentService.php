<?php
namespace App\Service;

use App\Entity\Document;
use App\Entity\DocumentFileFormatStorage;
use App\Entity\FileFormat;
use App\Entity\Storage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DocumentService
{
  private $em;
  private $filesystem;
  private $conversionService;
  private $tokenStorage;
  private $projectDir;

  /**
   * DocumentService constructor.
   * @param EntityManagerInterface $em
   * @param Filesystem $filesystem
   * @param ConversionService $conversionService
   * @param TokenStorageInterface $tokenStorage
   * @param $projectDir
   */
  public function __construct(EntityManagerInterface $em, Filesystem $filesystem, ConversionService $conversionService, TokenStorageInterface $tokenStorage, $projectDir)
  {
    $this->em = $em;
    $this->filesystem = $filesystem;
    $this->conversionService = $conversionService;
    $this->tokenStorage = $tokenStorage;
    $this->projectDir = $projectDir;
  }

  public function convert(DocumentFileFormatStorage $documentFileFormatStorage, FileFormat $newFormat) {
    $localStorage = $this->em->getRepository(Storage::class)->findOneBy(['code' => 'local']);
    if ($localStorage instanceof Storage) {
      if ($documentFileFormatStorage->getStorage()->getCode() == $localStorage->getCode()) {
        switch($newFormat->getExtention()) {
          case 'tiff':
            switch ($documentFileFormatStorage->getFileFormat()->getExtention()) {
              case 'pdf':
                // fichier initial
                $initFilepath = $this->projectDir . "/public" . $documentFileFormatStorage->getContentUrl();

                // fichier final
                $databasePath = "/" . $this->tokenStorage->getToken()->getUser()->getId() . "/" . $documentFileFormatStorage->getDocument()->getId() . "/" . $newFormat->getExtention();
                $dir = $this->projectDir . "/public" . $databasePath;
                if (!is_dir($dir)) {
                  mkdir($dir, 0777, true);
                }
                $newFilepath = $dir . '/' . pathinfo($documentFileFormatStorage->getContentUrl(),PATHINFO_FILENAME ). ".tiff";
                $databaseFilepath = $databasePath . '/' . pathinfo($documentFileFormatStorage->getContentUrl(),PATHINFO_FILENAME ). ".tiff";;


                if ($this->conversionService->convertPdfToTiff($initFilepath, $newFilepath)) {
                  if (file_exists($newFilepath)) {
                    // on peut créer le nouveau document
                    $newDoc = new DocumentFileFormatStorage($documentFileFormatStorage->getDocument(), $newFormat, $localStorage, $databaseFilepath);
                    $this->em->persist($newDoc);
                    $this->em->flush();

                    return [
                      'success' => true,
                      'document_file_format_storage' => $newDoc
                    ];
                  } else {
                    return [
                      'success' => false,
                      'error_message' => 'Une erreur est survenue lors de la conversion du document PDF en TIFF.'
                    ];
                  }
                } else {
                  return [
                    'success' => false,
                    'error_message' => 'Une erreur est survenue lors de la conversion du document PDF en TIFF.'
                  ];
                }
                var_dump($res);
                die;
              break;
            }
            break;
        }
      } else {
        var_dump("On est pas sur un local storage. Il faut le télécharger en local pour faire le traitement ?");
        die;
      }
    }
    var_dump($documentFileFormatStorage->getContentUrl());
    die;
    return $documentFileNewFormat;
  }

  public function getDocumentFileFormatStorageForOcr(Document $document, $format) {
    $formatOcr = $this->em->getRepository(FileFormat::class)->findOneBy(['extention' => $format]);
    $pdfFormat = $this->em->getRepository(FileFormat::class)->findOneBy(['extention' => 'pdf']);
    $localStorage = $this->em->getRepository(Storage::class)->findOneBy(['code' => 'local']);
    if ($formatOcr instanceof FileFormat && $localStorage instanceof Storage && $pdfFormat instanceof FileFormat) {
      // on cherche d'abord un TIFF dans le storage Local
      $localTiffDocument = $document->getDocumentFileFormatStorage($localStorage, $formatOcr);
      if ($localTiffDocument instanceof DocumentFileFormatStorage) {
        return $localTiffDocument;
      } else {
        // on tente de convertir .. a revoir
        // TODO convert document
        $localPdfDocument = $document->getDocumentFileFormatStorage($localStorage, $pdfFormat);
        if ($localPdfDocument instanceof DocumentFileFormatStorage) {
          // on convert en tiff le fichier local PDF
          $res = $this->convert($localPdfDocument, $formatOcr);
          if ($res['success']) {
            return $res['document_file_format_storage'];
          } else {
            // TODO ? Lire le message d'erreur et le logger
            return false;
          }
        }
      }
    }
    return null;
  }


}