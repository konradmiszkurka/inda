<?php
declare(strict_types=1);

namespace App\Lib\Infrastructure\Doctrine\Fixtures;

use App\Lib\Infrastructure\Doctrine\Mocks\CurrencyLocaleDataMock;
use App\Lib\Infrastructure\Doctrine\Mocks\UserDataMock;
use App\Modules\Product\Domain\CurrencyLocale\Entity\CurrencyLocaleEntity;
use App\Modules\User\Domain\User\Entity\UserEntity;
use App\Modules\User\Domain\User\Enum\RoleEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CurrencyLocaleFixtures extends Fixture
{
    public const CURRENCYLOCALE_REFERENCE = 'currency_locale-';

    public function load(ObjectManager $manager)
    {
        $fixtures = [
            [
                'locale' => 'en',
                'value' => '$',
            ],
            [
                'locale' => 'pl',
                'value' => 'zł',
            ],
        ];

        foreach ($fixtures as $fixture) {
            $data = (new CurrencyLocaleDataMock())
                ->setValue($fixture['value'])
                ->setLocale($fixture['locale']);

            $entity = new CurrencyLocaleEntity($data);

            $manager->persist($entity);

            $this->addReference(self::CURRENCYLOCALE_REFERENCE . $entity->getLocale(), $entity);
        }

        $manager->flush();
    }
}