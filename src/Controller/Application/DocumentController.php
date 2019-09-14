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
use App\Entity\FileFormat;
use App\Entity\Storage;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DocumentController extends AbstractController
{
  /**
   * @Route("/add-local-document", name="api_document_add_local_document")
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

          $file_format_storage = new DocumentFileFormatStorage($document, $fileFormat, $localStorage, $localPath);
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
}