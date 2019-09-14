<?php

namespace App\Controller\Webservice;

use App\Entity\Document;
use App\Entity\DocumentFileFormatStorage;
use App\Interfaces\ProviderOCRInterface;
use App\Service\DocumentService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class GetOcrDocumentAction
{
  private $managerRegistry;
  private $tokenStorage;
  private $documentService;
  private $projectDir;
  /**
   * @var ProviderOCRInterface
   */
  private $providerOCR;

  /**
   * GetOcrDocumentAction constructor.
   * @param ManagerRegistry $managerRegistry
   * @param TokenStorageInterface $tokenStorage
   * @param DocumentService $documentService
   * @param ProviderOCRInterface $providerOCR
   * @param $projectDir
   */
  public function __construct(ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorage,
                              DocumentService $documentService, ProviderOCRInterface $providerOCR,
                              $projectDir)
  {
    $this->managerRegistry = $managerRegistry;
    $this->tokenStorage = $tokenStorage;
    $this->documentService = $documentService;
    $this->projectDir = $projectDir;
    $this->providerOCR = $providerOCR;
  }

  /**
   * @param $id
   * @return string
   */
  public function __invoke($id)
  {
    $token = $this->tokenStorage->getToken();
    $user = $token->getUser();


    // on récupère tous les documents de l'utilisateurs
    $em = $this->managerRegistry->getManager();
    $document = $em->getRepository(Document::class)->findOneBy(array('user' => $user, 'id' => $id));
    if ($document instanceof Document) {
      $documentFormat = $this->documentService->getDocumentFileFormatStorageForOcr($document, $this->providerOCR->getInputFormat());
      if ($documentFormat instanceof DocumentFileFormatStorage) {
        if ($documentFormat->isRelativePath()) {
          // on peut lancer la récupération du texte en OCR
          $filePath = $this->projectDir . "/public" . $documentFormat->getContentUrl();
        } else {
          $filePath = $documentFormat->getContentUrl();
        }

        return $this->providerOCR->run($filePath);
      } else {
        throw new BadRequestHttpException(sprintf("Impossible de récuprer le document au format TIFF en local."));
      }
    } else {
      throw new NotAcceptableHttpException(sprintf("Vous n'êtes pas le propriétaire de ce document"));
    }
  }
}