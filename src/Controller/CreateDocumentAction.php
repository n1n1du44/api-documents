<?php

namespace App\Controller;

use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Document;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CreateDocumentAction
{
  private $managerRegistry;
  private $validator;
  private $resourceMetadataFactory;

  public function __construct(ManagerRegistry $managerRegistry, ValidatorInterface $validator, ResourceMetadataFactoryInterface $resourceMetadataFactory)
  {
    $this->managerRegistry = $managerRegistry;
    $this->validator = $validator;
    $this->resourceMetadataFactory = $resourceMetadataFactory;
  }

  /**
   * @param Request $request
   * @return Document
   * @throws \ApiPlatform\Core\Exception\ResourceClassNotFoundException
   */
  public function __invoke(Request $request): Document
  {
    $uploadedFile = $request->files->get('file');


    if (!$uploadedFile) {
      throw new BadRequestHttpException('"file" is required');
    }

    $document = new Document();
    $document->file = $uploadedFile;

    $this->validate($document, $request);

    $em = $this->managerRegistry->getManager();
    $em->persist($document);
    $em->flush();

    return $document;
  }

  /**
   * @param Document $document
   * @param Request $request
   * @throws \ApiPlatform\Core\Exception\ResourceClassNotFoundException
   */
  private function validate(Document $document, Request $request): void
  {
    $attributes = RequestAttributesExtractor::extractAttributes($request);
    $resourceMetadata = $this->resourceMetadataFactory->create(Document::class);
    $validationGroups = $resourceMetadata->getOperationAttribute($attributes, 'validation_groups', null, true);

    $this->validator->validate($document, ['groups' => $validationGroups]);
  }
}