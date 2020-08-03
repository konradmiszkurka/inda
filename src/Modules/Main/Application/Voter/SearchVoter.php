<?php

declare(strict_types=1);

namespace App\Modules\Main\Application\Voter;

use App\Lib\Domain\User\UserEntityInterface;
use App\Modules\User\Domain\User\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SearchVoter extends Voter
{
    public const SEARCH = 'SearchSearch';

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    protected function supports($attribute, $subject)
    {
        if ($attribute !== self::SEARCH) {
            return false;
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

        switch ($attribute) {
            case self::SEARCH:
                $result = $this->can();
                break;
            default:
                $result = false;
        }

        return $result;
    }

    private function can(): bool
    {
        if ($this->authorizationChecker->isGranted(RoleEnum::getRealRole(RoleEnum::ROLE_ADMIN)[0])) {
            return true;
        }

        return false;
    }
}