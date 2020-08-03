<?php

declare(strict_types=1);

namespace App\Modules\Product\Application\Voter;

use App\Lib\Domain\User\UserEntityInterface;
use App\Modules\Product\Domain\Product\Entity\ProductEntity;
use App\Modules\User\Domain\User\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    public const CREATE = 'ProductCreate';
    public const EDIT = 'ProductEdit';
    public const REMOVE = 'ProductRemove';
    public const SHOW = 'ProductShow';
    public const LISTS = 'ProductLists';

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
        if (!in_array(
            $attribute,
            [self::CREATE, self::EDIT, self::REMOVE, self::SHOW, self::LISTS],
            true
        )) {
            return false;
        }

        if (!in_array($attribute, [self::CREATE, self::LISTS], true) && !$subject instanceof ProductEntity) {
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