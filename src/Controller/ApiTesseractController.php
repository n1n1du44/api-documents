<?php
/**
 * Created by PhpStorm.
 * User: Antonin
 * Date: 05/04/2019
 * Time: 21:43
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ApiTesseractController extends AbstractController
{

  public function postTestMethodAction(Request $request) {
    var_dump("toto");
    die;

  }

}