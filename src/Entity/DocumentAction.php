<?php
/**
 * Created by PhpStorm.
 * User: Antonin Auffray
 * Date: 02/04/2019
 * Time: 22:18
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * @ORM\Table(name="document_action")
 * @ORM\Entity
 * @ApiResource
 */
class DocumentAction
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

  /**
   * @var Document
   *
   * @ManyToOne(targetEntity="Document")
   * @JoinColumn(name="document_id", referencedColumnName="id")
   */
  private $document;

  /**
   * @var Action
   *
   * @ManyToOne(targetEntity="Action")
   * @JoinColumn(name="action_id", referencedColumnName="id")
   */
  private $action;

  /**
   * @var ActionState
   *
   * @ManyToOne(targetEntity="ActionState")
   * @JoinColumn(name="action_state_id", referencedColumnName="id")
   */
  private $actionState;

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id): void
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getDocument()
  {
    return $this->document;
  }

  /**
   * @param mixed $document
   */
  public function setDocument($document): void
  {
    $this->document = $document;
  }

  /**
   * @return mixed
   */
  public function getAction()
  {
    return $this->action;
  }

  /**
   * @param mixed $action
   */
  public function setAction($action): void
  {
    $this->action = $action;
  }

  /**
   * @return ActionState
   */
  public function getActionState(): ActionState
  {
    return $this->actionState;
  }

  /**
   * @param ActionState $actionState
   */
  public function setActionState(ActionState $actionState): void
  {
    $this->actionState = $actionState;
  }
}