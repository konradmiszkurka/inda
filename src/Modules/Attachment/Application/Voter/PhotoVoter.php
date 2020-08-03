<?php

declare(strict_types=1);

namespace App\Modules\Attachment\Application\Voter;

use App\Lib\Domain\User\UserEntityInterface;
use App\Modules\Attachment\Domain\Photo\Entity\PhotoEntity;
use App\Modules\User\Domain\User\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PhotoVoter extends Voter
{
    public const CREATE = 'PhotoCreate';
    public const EDIT = 'PhotoEdit';
    public const REMOVE = 'PhotoRemove';
    public const SHOW = 'PhotoShow';
    public const LISTS = 'PhotoLists';

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
        if (!in_array($attribute, [self::CREATE, self::EDIT, self::REMOVE, self::SHOW, self::LISTS], true)) {
            return false;
        }

        if (!in_array($attribute, [self::CREATE, self::LISTS], true) && !$subject instanceof PhotoEntity) {
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
            return false;
        }

        switch ($attribute) {
            case self::CREATE:
            case self::EDIT:
            case self::REMOVE:
            case self::SHOW:
            case self::LISTS:
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