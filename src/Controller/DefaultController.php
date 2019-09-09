<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 06/04/2019
 * Time: 17:05
 */

namespace App\Controller;


use App\Interfaces\ProviderOCRInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
  /**
   * @Route("/", name="api_validate_configuration")
   * @param ProviderOCRInterface $providerOCR
   * @return Response
   */
  public function validateConfiguration(ProviderOCRInterface $providerOCR) {
    return $this->render('default/validate_configuration.html.twig', array(
      'provider_ocr_validate_configuration' => $providerOCR->isConfigurationValid(),
      'provider_ocr_name' => $providerOCR->getName(),
      'provider_ocr_version' => $providerOCR->getVersion()
    ));
  }
}