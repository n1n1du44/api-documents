<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * DocumentType
 *
 * @ORM\Table(name="document_type")
 * @ORM\Entity
 */
class DocumentType
{
  /**
   * @var int
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;


  /**
   * @var string
   *
   * @ORM\Column(name="libelle", type="string", length=255)
   */
  private $libelle;


  /**
   * @var string
   *
   * @ORM\Column(name="code", type="string", length=255)
   */
  private $code;

  /**
   * @ManyToOne(targetEntity="User")
   * @JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;


  /**
   * @ORM\OneToMany(targetEntity="DocumentTypeRecherche", mappedBy="documentType")
   */
  private $recherches;

  public function __construct() {
    $this->recherches = new ArrayCollection();
  }

  /**
   * @return int
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * @return string
   */
  public function getLibelle()
  {
    return $this->libelle;
  }

  /**
   * @param string $libelle
   */
  public function setLibelle($libelle)
  {
    $this->libelle = $libelle;
  }

  /**
   * @return string
   */
  public function getCode()
  {
    return $this->code;
  }

  /**
   * @param string $code
   */
  public function setCode($code)
  {
    $this->code = $code;
  }

  /**
   * @return mixed
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param mixed $user
   */
  public function setUser($user): void
  {
    $this->user = $user;
  }

  /**
   * @return mixed
   */
  public function getRecherches()
  {
    return $this->recherches;
  }

  /**
   * @param mixed $recherches
   */
  public function setRecherches($recherches)
  {
    $this->recherches = $recherches;
  }






}

