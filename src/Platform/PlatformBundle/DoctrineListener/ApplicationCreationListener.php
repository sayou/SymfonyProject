<?php
// src/OC/PlatformBundle/DoctrineListener/ApplicationCreationListener.php

namespace Platform\PlatformBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Platform\PlatformBundle\Email\ApplicationMailer;
use Platform\PlatformBundle\Entity\Application;

class ApplicationCreationListener
{
  /**
   * @var ApplicationMailer
   */
  private $applicationMailer;

  public function __construct(ApplicationMailer $applicationMailer)
  {
    $this->applicationMailer = $applicationMailer;
  }

  public function postPersist(LifecycleEventArgs $args)
  {
    $entity = $args->getObject();

    // On ne veut envoyer un email que pour les entités Application
    if (!$entity instanceof Application) {
      return;
    }

    $this->applicationMailer->sendNewNotification($entity);
  }
}
