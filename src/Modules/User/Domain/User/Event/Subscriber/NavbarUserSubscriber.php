<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\User\Event\Subscriber;

use App\Modules\User\Domain\User\Entity\UserEntity;
use KevinPapst\AdminLTEBundle\Event\NavbarUserEvent;
use KevinPapst\AdminLTEBundle\Event\ShowUserEvent;
use KevinPapst\AdminLTEBundle\Event\SidebarUserEvent;
use KevinPapst\AdminLTEBundle\Model\UserModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class NavbarUserSubscriber implements EventSubscriberInterface
{
    /**
     * @var Security
     */
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NavbarUserEvent::class => ['onShowUser', 100],
            SidebarUserEvent::class => ['onShowUser', 100],
        ];
    }

    public function onShowUser(ShowUserEvent $event)
    {
        if (null === $this->security->getUser()) {
            return;
        }

        /* @var $entity UserEntity */
        $entity = $this->security->getUser();

        $user = new UserModel();
        $user
            ->setId($entity->getId())
            ->setName($entity->getUsername())
            ->setUsername($entity->getUsername())
            ->setIsOnline(true)
            ->setTitle($entity->getFirstName() . ' ' . $entity->getLastName())
            ->setAvatar($entity->getAvatar())
            ->setMemberSince($entity->getCreatedAt());

        $event->setUser($user);
    }
}