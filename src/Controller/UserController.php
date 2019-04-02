<?php
/**
 * Created by PhpStorm.
 * User: Antonin Auffray
 * Date: 02/04/2019
 * Time: 21:43
 */

namespace App\Controller;


use App\Entity\User;
use FOS\RestBundle\Controller\Annotations\Get;


class UserController extends RestController
{
    /**
     * @Get("/api/users", name="get_users")
     */
    public function getUsersAction() {
      $users = $this->getDoctrine()->getRepository(User::class)->findAll();
      $data = array(
        'users' => $users
      ); // get data, in this case list of users.
      $view = $this->getView($data, 200);

      return $this->handleView($view);
    }

}