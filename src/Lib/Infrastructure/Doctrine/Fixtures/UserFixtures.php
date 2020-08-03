<?php
declare(strict_types=1);

namespace App\Lib\Infrastructure\Doctrine\Fixtures;

use App\Lib\Infrastructure\Doctrine\Mocks\UserDataMock;
use App\Modules\User\Domain\User\Entity\UserEntity;
use App\Modules\User\Domain\User\Enum\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user-';

    public function load(ObjectManager $manager)
    {
        $fixtures = [
            [
                'userName' => 'admin',
                'password' => 'admin',
                'role' => RoleEnum::ROLE_SUPER_ADMIN,
                'phone' => '500100100'
            ],
        ];

        foreach ($fixtures as $fixture) {
            $data = (new UserDataMock($fixture));

            $entity = new UserEntity();
            $entity->create($data);

            $manager->persist($entity);

            $this->addReference(self::USER_REFERENCE . $entity->getUsername(), $entity);
        }

        $manager->flush();
    }
}