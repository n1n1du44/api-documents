<?php

namespace App\Controller\Webservice;

use ApiPlatform\Core\Exception\ResourceClassNotFoundException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Document;
use App\Entity\DocumentFileFormatStorage;
use App\Entity\FileFormat;
use App\Entity\Storage;
use App\Entity\User;
use App\Service\DocumentService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use SplFileInfo;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


final class CreateDocumentAction
{
  private $managerRegistry;
  private $validator;
  private $resourceMetadataFactory;
  private $tokenStorage;
  private $documentService;

  public function __construct(ManagerRegistry $managerRegistry, ValidatorInterface $validator,
                              ResourceMetadataFactoryInterface $resourceMetadataFactory,
                              TokenStorageInterface $tokenStorage, DocumentService $documentService)
  {
    $this->managerRegistry = $managerRegistry;
    $this->validator = $validator;
    $this->resourceMetadataFactory = $resourceMetadataFactory;
    $this->tokenStorage = $tokenStorage;
    $this->documentService = $documentService;
  }

  /**
   * @param Request $request
   * @return Document
   * @throws ResourceClassNotFoundException
   * @throws Exception
   */
  public function __invoke(Request $request): Document
  {
    $uploadedFile = $request->files->get('file');
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

  /**
   * @param Document $document
   * @param Request $request
   * @throws ResourceClassNotFoundException
   */
  private function validate(Document $document, Request $request): void
  {
    $attributes = RequestAttributesExtractor::extractAttributes($request);
    $resourceMetadata = $this->resourceMetadataFactory->create(Document::class);
    $validationGroups = $resourceMetadata->getOperationAttribute($attributes, 'validation_groups', null, true);

    $this->validator->validate($document, ['groups' => $validationGroups]);
  }

  /**
   * @param User $user
   * @param Document $document
   * @return Document|bool
   * @throws Exception
   */
  private function renameAndMoveFile(User $user, Document $document) {
    $em = $this->managerRegistry->getManager();
    $originalFilename = "media\\" . $document->getFilePath();
    $originalFileInfo = new SplFileInfo($originalFilename);

    $filesystem = new Filesystem();
    if ($filesystem->exists($originalFilename)) {
      $dir = $user->getId() . "/" . $document->getId() . "/" . $originalFileInfo->getExtension();
      $now = new \DateTime();
      $filename = md5($now->format('YmdHis') . $originalFileInfo->getFilename()) . "." . $originalFileInfo->getExtension();
      while ($filesystem->exists($filename)) {
        $now = new \DateTime();
        $filename = md5($now->format('YmdHis') . $originalFileInfo->getFilename()) . "." . $originalFileInfo->getExtension();
      }

      $filepath = $dir . "/" . $filename;
      $filesystem->copy($originalFilename, $filepath);
      if ($filesystem->exists($filepath)) {
        $document->setFilePath($filepath);
        $document->setContentUrl("/" . $filepath);

        $fileFormat = $em->getRepository(FileFormat::class)->findOneBy(array('extention' => $originalFileInfo->getExtension()));
        if ($fileFormat instanceof FileFormat) {
          $localStorage = $em->getRepository(Storage::class)->findOneBy(['code' => 'local']);
          if ($localStorage instanceof Storage) {
            $documentFileFormatStorage = new DocumentFileFormatStorage($document, $fileFormat, $localStorage, "/" . $filepath);
            $em->persist($documentFileFormatStorage);
            $em->persist($document);
            $em->flush();

            // on supprime le document initial
            unlink($originalFilename());

            return $document;
          } else {
            throw new BadRequestHttpException('Impossible de copier le document. Local storage non trouvé');
          }          
        } else {
          throw new BadRequestHttpException('Impossible de copier le document. Extention non gérée : ' . $originalFileInfo->getExtension());
        }
      } else {
          throw new BadRequestHttpException('Format non géré : ' . $originalFileInfo->getExtension());
      }
    } else {
        throw new BadRequestHttpException('Document non trouvé');
    }
  }
}