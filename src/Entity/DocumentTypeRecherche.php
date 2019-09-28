<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentTypeRecherche
 *
 * @ORM\Table(name="document_type_recherche")
 * @ORM\Entity
 */
class DocumentTypeRecherche
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
   * @ORM\Column(name="code", type="string", length=255)
   */
  private $code;


  /**
   * @var string
   *
   * @ORM\Column(name="libelle_recherche", type="string", length=255)
   */
  private $libelleRecherche;


  /**
   * @var string
   *
   * @ORM\Column(name="type_recherche", type="string", length=255)
   */
  private $typeRecherche;



  /**
   * @var
   * @ORM\ManyToOne(targetEntity="DocumentType", inversedBy="recherches")
   * @ORM\JoinColumn(name="document_type_id", referencedColumnName="id", nullable=false)
   */
  private $documentType;

  /**
   * @ORM\OneToMany(targetEntity="DocumentTypeRechercheChamp", mappedBy="recherche")
   */
  private $champs;

  public function __construct() {
    $this->champs = new ArrayCollection();
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
   * @return string
   */
  public function getLibelleRecherche()
  {
    return $this->libelleRecherche;
  }

  /**
   * @param string $libelleRecherche
   */
  public function setLibelleRecherche($libelleRecherche)
  {
    $this->libelleRecherche = $libelleRecherche;
  }

  /**
   * @return string
   */
  public function getTypeRecherche()
  {
    return $this->typeRecherche;
  }

  /**
   * @param string $typeRecherche
   */
  public function setTypeRecherche($typeRecherche)
  {
    $this->typeRecherche = $typeRecherche;
  }



  /**
   * @return mixed
   */
  public function getChamps()
  {
    return $this->champs;
  }

  /**
   * @param mixed $champs
   */
  public function setChamps($champs)
  {
    $this->champs = $champs;
  }

  /**
   * @return mixed
   */
  public function getDocumentType()
  {
    return $this->documentType;
  }

  /**
   * @param mixed $documentType
   */
  public function setDocumentType($documentType)
  {
    $this->documentType = $documentType;
  }









}

