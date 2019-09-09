<?php

namespace App\DataFixtures;

use App\Entity\FileFormat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FileFormatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      $fileFormats = array(
        array('jpg', 'Image JPEG'),
        array('pdf', 'Document PDF'),
        array('tiff', 'Image TIFF'),
        array('png', 'Image PNG'),
      );

      foreach ($fileFormats as $_fileFormat) {
        $fileFormat = $manager->getRepository(FileFormat::class)->findOneBy(array('extention' => $_fileFormat[0]));
        if (!($fileFormat instanceof FileFormat)) {
          $fileFormat = new FileFormat();
          $fileFormat->setExtention($_fileFormat[0]);
        }
        $fileFormat->setLabel($_fileFormat[1]);
        $manager->persist($fileFormat);
      }

       $manager->flush();
    }
}
