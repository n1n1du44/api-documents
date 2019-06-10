<?php
/**
 * Created by PhpStorm.
 * User: Antonin Auffray
 * Date: 02/04/2019
 * Time: 22:18
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Table(name="document_storage")
 * @ORM\Entity
 * @ApiResource
 */
class DocumentStorage
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

  /**
   * @var Document
   *
   * @ManyToOne(targetEntity="Document")
   * @JoinColumn(name="document_id", referencedColumnName="id")
   */
  private $document;

  /**
   * @var Action
   *
   * @ManyToOne(targetEntity="Storage")
   * @JoinColumn(name="storage_id", referencedColumnName="id")
   */
  private $storage;

  /**
   * @var string|null
   *
   * @ORM\Column(nullable=true)
   */
  public $filepath;

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
  public function getDocument()
  {
    return $this->document;
  }

  /**
   * @param mixed $document
   */
  public function setDocument($document): void
  {
    $this->document = $document;
  }

  /**
   * @return Action
   */
  public function getStorage(): Action
  {
    return $this->storage;
  }

  /**
   * @param Action $storage
   */
  public function setStorage(Action $storage): void
  {
    $this->storage = $storage;
  }

}