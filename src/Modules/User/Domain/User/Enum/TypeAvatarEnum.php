<?php

declare(strict_types=1);

namespace App\Modules\User\Domain\User\Enum;

use App\Modules\User\Domain\User\Entity\UserEntity;
use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;
use LasseRafn\InitialAvatarGenerator\InitialAvatar;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class TypeAvatarEnum extends AbstractEnumType
{
    public const CACHE_INITIAL_NAME = 'cache_initial';
    public const CACHE_GRAVATAR_NAME = 'cache_gravatar';
    public const CACHE_TIME = 604800;

    public const GRAVATAR = 'gravatar';
    public const INITIAL = 'initial';
    public const FILE = 'file';
    public const DEFAULT = 'default';

    protected static $choices = [
        self::GRAVATAR => 'Gravatar',
        self::INITIAL => 'Initials',
        self::FILE => 'File',
        self::DEFAULT => 'Default',
    ];

    public static function avatarGenerate(UserEntity $userEntity, ?string $type = null): string
    {
        if ($type === null) {
            $type = null;
        }
        $result = '/img/defaultAvatar.jpg';

        switch ($type ?? $userEntity->getAvatarType()) {
            case self::GRAVATAR:
                $result = self::gravatar($userEntity->getEmail());
                break;
            case self::INITIAL:
                $result = self::initial($userEntity->getFirstName() . ' ' . $userEntity->getLastName());
                break;
            case self::FILE:
                if ($userEntity->getAvatarPhoto()) {
                    $result = $userEntity->getAvatarPhoto()->getPath();
                }

                break;
            case self::DEFAULT:
            default:
                break;
        }

        return $result;
    }

    public static function removedCachedFile(): void
    {
        $cache = new FilesystemAdapter();

        $cache->delete(self::CACHE_INITIAL_NAME);
        $cache->delete(self::CACHE_GRAVATAR_NAME);
    }

    /**
     * @source https://github.com/LasseRafn/php-initial-avatar-generator
     * @param string $name
     * @param int $size
     * @param string $background
     * @param string $color
     * @return string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private static function initial(
        string $name,
        int $size = 150,
        string $background = '#343a40',
        string $color = '#eeeeee'
    ): string {
        return (new FilesystemAdapter())->get(
            self::CACHE_INITIAL_NAME,
            function (ItemInterface $item) use ($name, $size, $background, $color) {
                $item->expiresAfter(self::CACHE_TIME);

                $avatar = new InitialAvatar();
                $image = $avatar->name($name)
                    ->length(2)
                    ->fontSize(0.5)
                    ->size($size)
                    ->background($background)
                    ->color($color)
                    ->generate()
                    ->stream('data-url');

                return $image->getContents();
            }
        );
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     * @source https://gravatar.com/site/implement/images/php/
     * @param string $email The email address
     * @param int $size Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $default Default imageset to use [ 404 | mp | identicon | monsterid | wavatar ]
     * @param string $rating Maximum rating (inclusive) [ g | pg | r | x ]
     * @return string containing either just a URL or a complete image tag
     * @throws \Psr\Cache\InvalidArgumentException
     */
    private static function gravatar(
        string $email,
        int $size = 150,
        string $default = 'mp',
        string $rating = 'g'
    ): string {
        return (new FilesystemAdapter())->get(
            self::CACHE_GRAVATAR_NAME,
            static function (ItemInterface $item) use ($email, $size, $default, $rating) {
                $item->expiresAfter(self::CACHE_TIME);

                return sprintf(
                    'https://www.gravatar.com/avatar/%s?s=%s&d=%s&r=%s',
                    md5(strtolower(trim($email))),
                    $size,
                    $default,
                    $rating
                );
            }
        );
    }
}