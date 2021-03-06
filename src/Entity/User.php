<?php
/**
 * Created by PhpStorm.
 * User: Antonin Auffray
 * Date: 02/04/2019
 * Time: 22:18
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity
 * @ApiResource
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * Many Users have Many Storages.
     * @ManyToMany(targetEntity="Storage", inversedBy="users")
     * @JoinTable(name="users_storages")
     */
    private $storages;

    public function __construct() {
      $this->storages = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
      return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
      $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
      return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
      $this->id = $id;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
      return array('ROLE_USER');
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
      return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
      $this->password = $password;
    }

      public function getSalt()
      {
          return null;
      }


      public function eraseCredentials()
      {
          // TODO: Implement eraseCredentials() method.
      }

    /**
     * @return mixed
     */
    public function getStorages()
    {
      return $this->storages;
    }

    /**
     * @param mixed $storages
     */
    public function setStorages($storages): void
    {
      $this->storages = $storages;
    }

}