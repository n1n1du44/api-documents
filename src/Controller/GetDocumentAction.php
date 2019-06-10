<?php

namespace App\Controller;

use App\Entity\Document;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class GetDocumentAction
{
  private $managerRegistry;
  private $tokenStorage;

  public function __construct(ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorage)
  {
    $this->managerRegistry = $managerRegistry;
    $this->tokenStorage = $tokenStorage;
  }

  /**
   * @param $id
   * @return Document
   * @throws Exception
   */
  public function __invoke($id)
  {
    $token = $this->tokenStorage->getToken();
    $user = $token->getUser();

    // on récupère tous les documents de l'utilisateurs
    $em = $this->managerRegistry->getManager();
    $document = $em->getRepository(Document::class)->findOneBy(array('user' => $user, 'id' => $id));
    if ($document instanceof Document) {
      return $document;
    } else {
      throw new NotAcceptableHttpException(sprintf("Vous n'êtes pas le propriétaire de ce document"));
    }

  }
}