<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 06/04/2019
 * Time: 16:55
 */

namespace App\Service;


use App\Interfaces\ProviderOCRInterface;
use thiagoalessio\TesseractOCR\TesseractOCR;

class TesseractOCRService implements ProviderOCRInterface
{

  private $path;
  private $version;
  private $name;

  public function __construct($path)
  {
    $this->path = $path;
    $this->name = 'Tesseract OCR';
    $this->version = $this->getVersion();
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getVersion(): string
  {
    return (new TesseractOCR())->executable($this->path)->version();
  }

  public function run($filepath): string
  {
    // Is useful to verify if the file exists, because the tesseract wrapper
    // will throw an error but without description
      if(!file_exists($filepath)){
        var_dump("Warning: the providen file [".$filepath."] doesn't exists.");
        die;
      }

      // Create an instanceof tesseract with the filepath as first parameter
      $tesseractInstance = new TesseractOCR($filepath);
      $tesseractInstance->executable($this->path)
      ->lang('fra');
//      $tesseractInstance->()
      // Execute tesseract to recognize text
      $content = $tesseractInstance->run();
      return $content;
    }

  public function isConfigurationValid(): bool
  {
    return true;
  }

  public function getInputFormat(): string
  {
    return 'tiff';
  }
}