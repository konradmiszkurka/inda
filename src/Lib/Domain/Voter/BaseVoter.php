<?php

declare(strict_types=1);

namespace App\Lib\Domain\Voter;

use App\Lib\Domain\User\UserEntityInterface;
use stdClass;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BaseVoter extends Voter
{
    protected $classType = null;
    protected $all = [];
    protected $noSubject = [];

    protected $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, $this->all, true)) {
            return false;
        }

        if (!in_array($attribute, $this->noSubject, true)) {
            if ($this->classType !== null && !$subject instanceof $this->classType) {
                return false;
            }
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        /** @var UserEntityInterface $user */
        $user = $token->getUser();
        $result = false;

        if (!$user instanceof UserEntityInterface) {
            return $result;
        }

        if (!is_object($subject)) {
            $baseSubject = $subject;
            $subject = new stdClass();
            if (is_string($baseSubject)) {
                $subject->data = $baseSubject;
            }
            if (is_int($baseSubject)) {
                $subject->data = (string)$baseSubject;
            }
        }

        return $this->switching($attribute, $subject, $user);
    }

    protected function switching(string $attribute, object $subject, UserEntityInterface $user): bool
    {
        return false;
    }
}
