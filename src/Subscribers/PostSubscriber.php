<?php

namespace App\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use App\Entity\Post;
use Symfony\Component\Security\Core\Security;

class PostSubscriber implements EventSubscriberInterface
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setUser']
        ];
    }

    public function setUser(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if ($entity instanceof Post) {
            $entity->setUser($this->security->getUser());
        }
    }
}
