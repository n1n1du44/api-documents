<?php
/**
 * Created by PhpStorm.
 * User: antonin
 * Date: 10/11/2018
 * Time: 17:18
 */

namespace App\Enums;


abstract class DocumentTypeRechercheChampType
{
  const TYPE_EXACT = "exact";
  const TYPE_REGEX = "regex";

  /** @var array categorie friendly named type */
  protected static $typeName = [
    self::TYPE_EXACT    => "Valeur exacte",
    self::TYPE_REGEX    => "Expression régulière"
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
      self::TYPE_EXACT,
      self::TYPE_REGEX
    ];
  }

  public static function getAvailableTypesWithLibelle() {
    return static::$typeName;
  }
}