<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\User\Event\Listener;

use KevinPapst\AdminLTEBundle\Event\ShowUserEvent;
use KevinPapst\AdminLTEBundle\Model\UserModel;

class NavbarUserListener
{
    public function onShowUser(ShowUserEvent $event)
    {
        $user = $this->getUser();
        $event->setUser($user);

        $event->setShowProfileLink(true);
    }

    protected function getUser()
    {
        return new UserModel();
    }
}