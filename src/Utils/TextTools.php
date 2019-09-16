<?php
/**
 * Created by PhpStorm.
 * User: Proprietaire
 * Date: 24/02/2018
 * Time: 19:27
 */

namespace AppBundle\Utils;

use AppBundle\Enums\DocumentTypeRechercheChampType;
use AppBundle\Enums\DocumentTypeRechercheType;
use AppBundle\Entity\DocumentTypeRecherche;
use AppBundle\Entity\DocumentTypeRechercheChamp;

class TextTools
{

  public static function get_longest_common_subsequence($string_1, $string_2)
  {
    $string_1_length = strlen($string_1);
    $string_2_length = strlen($string_2);
    $return          = '';

    if ($string_1_length === 0 || $string_2_length === 0)
    {
      // No similarities
      return $return;
    }

    $longest_common_subsequence = array();

    // Initialize the CSL array to assume there are no similarities
    $longest_common_subsequence = array_fill(0, $string_1_length, array_fill(0, $string_2_length, 0));

    $largest_size = 0;

    for ($i = 0; $i < $string_1_length; $i++)
    {
      for ($j = 0; $j < $string_2_length; $j++)
      {
        // Check every combination of characters
        if ($string_1[$i] === $string_2[$j])
        {
          // These are the same in both strings
          if ($i === 0 || $j === 0)
          {
            // It's the first character, so it's clearly only 1 character long
            $longest_common_subsequence[$i][$j] = 1;
          }
          else
          {
            // It's one character longer than the string from the previous character
            $longest_common_subsequence[$i][$j] = $longest_common_subsequence[$i - 1][$j - 1] + 1;
          }

          if ($longest_common_subsequence[$i][$j] > $largest_size)
          {
            // Remember this as the largest
            $largest_size = $longest_common_subsequence[$i][$j];
            // Wipe any previous results
            $return       = '';
            // And then fall through to remember this new value
          }

          if ($longest_common_subsequence[$i][$j] === $largest_size)
          {
            // Remember the largest string(s)
            $return = substr($string_1, $i - $largest_size + 1, $largest_size);
          }
        }
        // Else, $CSL should be set to 0, which it was already initialized to
      }
    }

    // Return the list of matches
    return $return;
  }

  public static function getLongestMatches($text, $StringTools) {
    $matches = array(array('longest' => "", 'length' => 0, 'obj' => null));

    foreach ($StringTools as $stringTool) {
      if ($stringTool instanceof IStringTool) {
        $string = strtoupper($stringTool->getLibelle());
        $longest = TextTools::get_longest_common_subsequence($text, $string);
        $arrayTempResult = array('longest' => $longest, 'length' => strlen($longest), 'obj' => $stringTool);

        if ($arrayTempResult['length'] > $matches[0]['length']) {
          $matches = array();
          array_push($matches, $arrayTempResult);
        } elseif ($arrayTempResult['length'] == $matches[0]['length']) {
          array_push($matches, $arrayTempResult);
        }
      }

    }

    // on boucle sur les résultat pour afficher le pourcentage de 'trouvé'
    foreach ($matches as $key => $match) {
      $matches[$key]['percentage'] = TextTools::getPercentageMutual($match['obj']->getLibelle(), $match['longest']);
    }

    return $matches;
  }

  public static function getPercentageMutual($stringInit, $stringFind) {
    $div = strlen($stringFind) / strlen($stringInit);
    $percentage = round((100*$div),2);

    return $percentage;
  }

  /**
   * @param $text
   * @param $searchs
   * @param string $mode
   * @return array
   */
  public static function getInformationsFromText($text, $searchs, $mode='error') {

    $informations = array();
    foreach ($searchs as $search) {
      if ($search instanceof DocumentTypeRecherche) {

        TextTools::writeMessage($mode, 'verbose', "On lance la recherche du '" . $search->getLibelleRecherche() . "'");
        $resultats = array();

        // on doit faire la mécanique pour rechercher dans le texte
        switch ($search->getTypeRecherche()) {
          case DocumentTypeRechercheType::TYPE_CHAMPS_COLLES:
            $resultats = TextTools::searchGluedFields($text, $search);
            break;
          case DocumentTypeRechercheType::TYPE_CHAMPS_SEPARES:
            $resultats = TextTools::searchSeparatedFields($text, $search);
            break;
          default:
            var_dump("On ne gère pas encore ce cas");
            die;
        }


        $informations[$search->getCode()] = $resultats;
      } else {
        var_dump("Ce n'est pas un document recherche");
        die;
      }

    }

    return $informations;
  }

  public static function writeMessage($mode, $niveauMessage, $message) {
    echo($message);
  }

  /**
   * @param $text
   * @param DocumentTypeRecherche $search
   * @param string $mode
   * @return array
   */
  public static function searchGluedFields($text, DocumentTypeRecherche $search, $mode='error') {
    TextTools::writeMessage($mode, 'verbose', "On est dans la recherche de champs collés");

    $searchFields = array();
    $continuerTraitement = true;

    foreach ($search->getChamps() as $champ) {
      $error = false;
      $libelleError = "";
      $result = "";
      $ecartChamps = 0;
      $type = "Type non géré";

      if ($continuerTraitement) {
        if ($champ instanceof DocumentTypeRechercheChamp) {
          if ($champ->getType() == DocumentTypeRechercheChampType::TYPE_EXACT) {
            $type = DocumentTypeRechercheChampType::TYPE_EXACT;

            $result = TextTools::get_longest_common_subsequence($text, $champ->getValue());
            TextTools::writeMessage($mode, 'verbose', "Texte exact trouvé : " . $result);
          } elseif ($champ->getType() == DocumentTypeRechercheChampType::TYPE_REGEX) {
            $type = DocumentTypeRechercheChampType::TYPE_REGEX;
            preg_match($champ->getValue(), $text, $matches, PREG_OFFSET_CAPTURE);


            if (count($matches) > 0) {
              $resultTemp = $matches[0];
              $ecartChamps = $resultTemp[1];
              if ($ecartChamps == 0) {
                $result = $resultTemp[0];
              } else {
                $error = true;
                $libelleError = "La regex a été trouvée, mais pas directement après la première chaine";
              }
            } else {
              // pas de résultat pour la regex cherchée
              $error = true;
              $libelleError = "La regex n'a pas été trouvée";
            }


          } else {
            $error = true;
            $libelleError = "Type de champ recherché non géré";
          }


          // on génère le resultat
          $searchResultTemp = array(
            'type' => $type,
            'value' => $champ->getValue(),
            'result' => $result,
            'ecart_champs' => $ecartChamps,
            'error' => $error,
            'libelle_error' => $libelleError
          );


          $searchFields[$champ->getCode()] = $searchResultTemp;

          if ($searchResultTemp['error'] == false) {
            // on récupère la postiion de cette chaine
            $strStartPos = strpos($text, $result);
            $strEndPos = $strStartPos + strlen($result);
            $text = substr($text, $strEndPos);
          } else {
            // on arrete le traitement directement car on n'a pas trouvé le résultat
            $continuerTraitement = false;
          }
        }
      }
    }

    return $searchFields;

  }

  private static function searchSeparatedFields($text, DocumentTypeRecherche $search, $mode='error') {
    TextTools::writeMessage($mode, 'verbose', "On est dans la recherche de champs collés");

    $searchFields = array();
    $continuerTraitement = true;

    foreach ($search->getChamps() as $champ) {
      $error = false;
      $libelleError = "";
      $result = "";
      $ecartChamps = 0;
      $type = "Type non géré";

      if ($continuerTraitement) {
        if ($champ instanceof DocumentTypeRechercheChamp) {
          if ($champ->getType() == DocumentTypeRechercheChampType::TYPE_EXACT) {
            $type = DocumentTypeRechercheChampType::TYPE_EXACT;

            $result = TextTools::get_longest_common_subsequence($text, $champ->getValue());
            TextTools::writeMessage($mode, 'verbose', "Texte exact trouvé : " . $result);
          } elseif ($champ->getType() == DocumentTypeRechercheChampType::TYPE_REGEX) {
            $type = DocumentTypeRechercheChampType::TYPE_REGEX;
            preg_match($champ->getValue(), $text, $matches, PREG_OFFSET_CAPTURE);


            if (count($matches) > 0) {
              $resultTemp = $matches[0];
              $result = $resultTemp[0];
              $ecartChamps = $resultTemp[1];

            } else {
              // pas de résultat pour la regex cherchée
              $error = true;
              $libelleError = "La regex n'a pas été trouvée";
            }


          } else {
            $error = true;
            $libelleError = "Type de champ recherché non géré";
          }


          // on génère le resultat
          $searchResultTemp = array(
            'type' => $type,
            'value' => $champ->getValue(),
            'result' => $result,
            'ecart_champs' => $ecartChamps,
            'error' => $error,
            'libelle_error' => $libelleError
          );


          $searchFields[$champ->getCode()] = $searchResultTemp;

          if ($searchResultTemp['error'] == false) {
            // on récupère la postiion de cette chaine
            $strStartPos = strpos($text, $result);
            $strEndPos = $strStartPos + strlen($result);
            $text = substr($text, $strEndPos);
          } else {
            // on arrete le traitement directement car on n'a pas trouvé le résultat
            $continuerTraitement = false;
          }
        }
      }
    }

    return $searchFields;
  }


}