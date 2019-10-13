<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 06/04/2019
 * Time: 17:05
 */

namespace App\Controller\Application;

use App\Entity\Document;
use App\Entity\DocumentFileFormatStorage;
use App\Entity\DocumentType;
use App\Entity\FileFormat;
use App\Entity\Storage;
use App\Entity\User;
use App\Service\DocumentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DocumentController extends AbstractController
{
  /**
   * @Route("/api/add-local-document", name="api_document_add_local_document")
   * @param Request $request
   * @param UserPasswordEncoderInterface $encoder
   * @return Response
   */
  public function addLocalDocument(Request $request, UserPasswordEncoderInterface $encoder) {
    $em = $this->getDoctrine()->getManager();
    $params = $request->request->all();

    $localPath = $params['local_path'];
    $format = $params['format'];
    $login = $params['login'];
    $password = $params['password'];

    $user = $em->getRepository(User::class)->findOneBy(['username' => $login]);
    $localStorage = $em->getRepository(Storage::class)->findOneBy(['code' => 'local']);
    if ($user instanceof User && $localStorage instanceof Storage) {
      if ($encoder->isPasswordValid($user, $password)) {
        $fileFormat = $em->getRepository(FileFormat::class)->findOneBy(['extention' => $format]);
        if ($fileFormat instanceof FileFormat) {
          $document = new Document();
          $document->setFilePath($localPath);
          $document->setUser($user);
          $em->persist($document);

          $file_format_storage = new DocumentFileFormatStorage($document, $fileFormat, $localStorage, $localPath, false);
          $em->persist($file_format_storage);
          $em->flush();

          return new JsonResponse(['document_id' => $document->getId()], 201);
        } else {
          return new JsonResponse(['error' => 'File format introuvable'], 500);
        }
      } else {
        return new JsonResponse(['error' => 'Mauvais password. Attendu : ' . $user->getPassword()], 500);
      }

    } else {
      return new JsonResponse(['error' => 'User introuvable'], 500);
    }
  }


  /**
   * @Route("/api/get-informations-from-text", name="api_document_get_informations_from_text")
   * @param Request $request
   * @param UserPasswordEncoderInterface $encoder
   * @param DocumentService $documentService
   * @return Response
   */
  public function getInformationsFromText(Request $request, UserPasswordEncoderInterface $encoder, DocumentService $documentService) {
    $em = $this->getDoctrine()->getManager();
    $params = $request->request->all();

    $text = $params['text'];
    $codeTypeDocument = $params['code_type_document'];
    $login = $params['login'];
    $password = $params['password'];

    $text = $documentService->encodeToUtf8($text);
    $text = str_replace (array("\r\n", "\n", "\r"), '', $text);

    $user = $em->getRepository(User::class)->findOneBy(['username' => $login]);
    if ($user instanceof User) {
      if ($encoder->isPasswordValid($user, $password)) {
        $documentType = $em->getRepository(DocumentType::class)->findOneBy(['code' => $codeTypeDocument]);
        if ($documentType instanceof DocumentType) {
          if ($documentType->getUser() instanceof User && $documentType->getUser()->getId() == $user->getId()) {
            $informations = $documentService->getInformationsFromDocumentType($documentType, $text);
            return new JsonResponse($informations, 200);
          } else {
            return new JsonResponse(['error' => 'Ce document ne vous appartient pas.'], 500);
          }
        } else {
          return new JsonResponse(['error' => 'Document type non trouvé : ' . $codeTypeDocument], 500);
        }
      } else {
        return new JsonResponse(['error' => 'Mauvais password. Attendu : ' . $user->getPassword()], 500);
      }

    } else {
      return new JsonResponse(['error' => 'User introuvable'], 500);
    }
  }
}