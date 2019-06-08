<?php

namespace App\Controller;

use App\Entity\Document;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class GetDocumentsAction
{
  private $managerRegistry;
  private $tokenStorage;

  public function __construct(ManagerRegistry $managerRegistry, TokenStorageInterface $tokenStorage)
  {
    $this->managerRegistry = $managerRegistry;
    $this->tokenStorage = $tokenStorage;
  }

  /**
   * @return Document
   */
  public function __invoke()
  {
    $token = $this->tokenStorage->getToken();
    $user = $token->getUser();

    // on récupère tous les documents de l'utilisateurs
    $em = $this->managerRegistry->getManager();
    $documents = $em->getRepository(Document::class)->findBy(array('user' => $user));

    return $documents;
  }
}