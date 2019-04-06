<?php
/**
 * Created by PhpStorm.
 * User: anton
 * Date: 06/04/2019
 * Time: 20:46
 */

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Document;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

final class ResolveDocumentContentUrlSubscriber implements EventSubscriberInterface
{
  private $storage;

  public function __construct(StorageInterface $storage)
  {
    $this->storage = $storage;
  }

  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
    ];
  }

  public function onPreSerialize(GetResponseForControllerResultEvent $event): void
  {
    $controllerResult = $event->getControllerResult();
    $request = $event->getRequest();

    if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
      return;
    }

    if (!$attributes = RequestAttributesExtractor::extractAttributes($request) || !\is_a($attributes['resource_class'], Document::class, true)) {
      return;
    }

    $documents = $controllerResult;

    if (!is_iterable($documents)) {
      $documents = [$documents];
    }

    foreach ($documents as $document) {
      if (!$document instanceof Document) {
        continue;
      }

      $document->contentUrl = $this->storage->resolveUri($document, 'file');
    }
  }
}