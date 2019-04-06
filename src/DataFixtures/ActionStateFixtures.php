<?php

namespace App\DataFixtures;

use App\Entity\ActionState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ActionStateFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      $states = array(
        array('waiting', 'Waiting', true),
        array('waiting', 'Waiting', true)
      );

      foreach ($states as $state) {
        $actionState = $manager->getRepository(ActionState::class)->findOneBy(array('code' => $state[0]));
        if (!($actionState instanceof ActionState)) {
          $actionState = new ActionState();
          $actionState->setCode($state[0]);
        }
        $actionState->setLabel($state[1]);
        $actionState->setDefaultValue($state[2]);
        $manager->persist($actionState);
      }

        $manager->flush();
    }
}
