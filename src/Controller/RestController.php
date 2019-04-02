<?php
/**
 * Created by PhpStorm.
 * User: Proprietaire
 * Date: 02/04/2019
 * Time: 21:58
 */

namespace App\Controller;


use FOS\RestBundle\Controller\AbstractFOSRestController;

class RestController extends AbstractFOSRestController
{
  public function getView($data, $code, $format = "json") {
    $view = $this->view($data, $code)
      ->setFormat($format)
    ;
    return $view;
  }
}