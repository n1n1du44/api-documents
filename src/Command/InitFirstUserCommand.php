<?php
/**
 * Created by PhpStorm.
 * User: Antonin
 * Date: 01/04/2019
 * Time: 15:28
 */

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class InitFirstUserCommand extends Command
{
  protected $em;
  private $encoder;

  public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
  {
    parent::__construct();
    $this->em = $em;
    $this->encoder = $encoder;
  }

  protected function configure(){
    $this
      ->setName('site:initialisation:first-user')
      ->setDescription("Création d'un utilisateur admin / admin pour la première connexion.")
      ->setHelp("Création d'un utilisateur admin / admin pour la première connexion.")
    ;
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int|null|void
   */
  protected function execute(InputInterface $input, OutputInterface $output){

    $users = $this->em->getRepository(User::class)->findAll();
    if (count($users) > 0) {
      $output->writeln("<fg=red>Au moins un utilisateur dans la base de données, impossible de créer l'utilisateur admin.</>");
    } else {
      $user = new User();

      $plainPassword = 'admin';
      $encoded = $this->encoder->encodePassword($user, $plainPassword);

      $user->setId(1);
      $user->setUsername("admin");
      $user->setPassword($encoded);

      $this->em->persist($user);
      $this->em->flush();
      $output->writeln("<fg=green>L'utilisateur a été créé</>");
    }
  }
}