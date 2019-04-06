<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 06/04/2019
 * Time: 16:56
 */

namespace App\Interfaces;


interface ProviderOCRInterface
{
  public function getName() : string;
  public function getVersion() : string;
  public function isConfigurationValid() : bool;
  public function run($filepath) : string;
}