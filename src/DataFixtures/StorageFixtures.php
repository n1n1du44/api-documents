<?php

namespace App\DataFixtures;

use App\Entity\FileFormat;
use App\Entity\Storage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StorageFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      $storages = array(
        array('local', 'Api local storage')
      );

      foreach ($storages as $_storage) {
        $storage = $manager->getRepository(Storage::class)->findOneBy(array('code' => $_storage[0]));
        if (!($storage instanceof Storage)) {
          $storage = new Storage();
          $storage->setCode($_storage[0]);
        }
        $storage->setLabel($_storage[1]);
        $manager->persist($storage);
      }

       $manager->flush();
    }
}
