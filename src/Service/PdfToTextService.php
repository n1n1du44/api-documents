<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 06/04/2019
 * Time: 16:55
 */

namespace App\Service;


use App\Interfaces\ProviderOCRInterface;
use Spatie\PdfToText\Exceptions\PdfNotFound;
use Spatie\PdfToText\Pdf;

class PdfToTextService implements ProviderOCRInterface
{

  private $path;
  private $version;
  private $name;

  public function __construct($path)
  {
    $this->path = $path;
    $this->name = 'pdftotext';
    $this->version = $this->getVersion();
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getVersion(): string
  {
    return "pasdeversion";
  }

  public function isConfigurationValid(): bool
  {
    return true;
  }

  public function run($filepath): string
  {
    try {
      $text = (new Pdf($this->path))
        ->setPdf($filepath)
        //      ->setOptions(['layout', 'r 96'])
        ->text();
      return utf8_encode($text);
    } catch (PdfNotFound $e) {
      return "";
    }
  }

  public function getInputFormat(): string
  {
    return 'pdf';
  }
}