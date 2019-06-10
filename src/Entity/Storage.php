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
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * @ORM\Table(name="storage")
 * @ORM\Entity
 * @ApiResource
 */
class Storage
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * Many Storages have Many Users.
     * @ManyToMany(targetEntity="User", mappedBy="storages")
     */
    private $users;

    /**
     * Many Storages have Many Users.
     * @ManyToMany(targetEntity="Document", mappedBy="storages")
     */
    private $documents;

    public function __construct() {
      $this->users = new ArrayCollection();
      $this->documents = new ArrayCollection();
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
     * @return mixed
     */
    public function getLabel()
    {
      return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
      $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
      return $this->users;
    }

    /**
     * @param mixed $users
     */
    public function setUsers($users): void
    {
      $this->users = $users;
    }
}