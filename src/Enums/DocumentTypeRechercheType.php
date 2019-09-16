<?php
/**
 * Created by PhpStorm.
 * User: antonin
 * Date: 10/11/2018
 * Time: 17:23
 */

namespace AppBundle\Enums;


abstract class DocumentTypeRechercheType
{
  const TYPE_CHAMPS_COLLES = "champs_colles";
  const TYPE_CHAMPS_SEPARES = "champs_separes";

  /** @var array categorie friendly named type */
  protected static $typeName = [
    self::TYPE_CHAMPS_COLLES    => "Champs qui se suivent",
    self::TYPE_CHAMPS_SEPARES    => "Champs séparés"
  ];

  /**
   * @param $typeShortName
   * @return string
   */
  public static function getTypeName($typeShortName)
  {
    if (!isset(static::$typeName[$typeShortName])) {
      return "Unknown type ($typeShortName)";
    }

    return static::$typeName[$typeShortName];
  }

  /**
   * @return array<string>
   */
  public static function getAvailableTypes()
  {
    return [
      self::TYPE_CHAMPS_COLLES,
      self::TYPE_CHAMPS_SEPARES
    ];
  }

  public static function getAvailableTypesWithLibelle() {
    return static::$typeName;
  }
}