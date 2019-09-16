<?php

namespace AppBundle\Entity;

use AppBundle\Utils\IStringTool;
use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentTypeRechercheChamp
 *
 * @ORM\Table(name="document_type_recherche_champ")
 * @ORM\Entity
 */
class DocumentTypeRechercheChamp
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
   * @ORM\Column(name="type", type="string", length=255)
   */
  private $type;


  /**
   * @var string
   *
   * @ORM\Column(name="value", type="string", length=255)
   */
  private $value;

  /**
   * @var
   * @ORM\ManyToOne(targetEntity="AppBundle\Entity\DocumentTypeRecherche", inversedBy="champs")
   * @ORM\JoinColumn(name="recherche_id", referencedColumnName="id", nullable=false)
   */
  private $recherche;

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
  public function getType()
  {
    return $this->type;
  }

  /**
   * @param string $type
   */
  public function setType($type)
  {
    $this->type = $type;
  }

  /**
   * @return string
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   * @param string $value
   */
  public function setValue($value)
  {
    $this->value = $value;
  }

  /**
   * @return mixed
   */
  public function getRecherche()
  {
    return $this->recherche;
  }

  /**
   * @param mixed $recherche
   */
  public function setRecherche($recherche)
  {
    $this->recherche = $recherche;
  }







}

