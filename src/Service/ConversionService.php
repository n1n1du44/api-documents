<?php
namespace App\Service;

use Exception;
use Orbitale\Component\ImageMagick\Command;

class ConversionService
{
  /**
   * @param $initialFilepath
   * @param $newFilepath
   * @return bool
   */
  public function convertPdfToTiff($initialFilepath, $newFilepath) {
    try {
      $command = new Command(getenv('IMAGEMAGICK_PATH'));
      $command->convert($initialFilepath )
        ->output($newFilepath)
        ->background('white')
        ->quality("9")
        ->run();

      return true;
    } catch (Exception $e) {
      var_dump($e->getMessage());
      die;
      return false;
    }
  }

}