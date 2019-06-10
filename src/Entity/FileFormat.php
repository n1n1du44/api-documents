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
 * @ORM\Table(name="file_format")
 * @ORM\Entity
 * @ApiResource
 */
class FileFormat
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
   * @ORM\Column(name="extention", type="string", length=255)
   */
  private $extention;

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
  public function getExtention()
  {
    return $this->extention;
  }

  /**
   * @param mixed $extention
   */
  public function setExtention($extention): void
  {
    $this->extention = $extention;
  }


}