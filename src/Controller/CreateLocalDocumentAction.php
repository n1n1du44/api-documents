<?php

namespace App\Controller;


use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Document;
use App\Entity\User;
use App\Service\DocumentService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class CreateLocalDocumentAction
{
  private $managerRegistry;
  private $tokenStorage;
  private $documentService;

  public function __construct(TokenStorageInterface $tokenStorage, DocumentService $documentService)
  {
    $this->tokenStorage = $tokenStorage;
    $this->documentService = $documentService;
  }

  /**
   * @param Request $request
   * @return Document
   * @throws Exception
   */
  public function __invoke(Request $request): Document
  {
    $params = $request->request->all();
    $format = $request->get('data');
    $token = $this->tokenStorage->getToken();
    $user = $token->getUser();


    if (!$uploadedFile) {
      throw new BadRequestHttpException('"file" is required');
    }
    if (!($user instanceof User)) {
      throw new BadRequestHttpException('"user" not found');
    }

    $document = new Document();
    $document->file = $uploadedFile;
    $document->setUser($user);

    $this->validate($document, $request);

    $em = $this->managerRegistry->getManager();
    $em->persist($document);

    // on peut ensuite déplacer le PDF
    $document = $this->renameAndMoveFile($user, $document);
    if ($document instanceof Document) {
      $em->persist($document);
      $em->flush();
    }

    return $document;
  }
}