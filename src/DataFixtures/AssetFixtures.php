<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Asset;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AssetFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference(UserFixtures::USER);

        $asset1 = new Asset();
        $asset1->setLabel('binance');
        $asset1->setCurrency('BTC');
        $asset1->setValue(1);
        $asset1->setUser($user);
        $manager->persist($asset1);

        $asset2 = new Asset();
        $asset2->setLabel('binance');
        $asset2->setCurrency('ETH');
        $asset2->setValue(1);
        $asset2->setUser($user);
        $manager->persist($asset2);

        $asset3 = new Asset();
        $asset3->setLabel('binance');
        $asset3->setCurrency('IOTA');
        $asset3->setValue(1);
        $asset3->setUser($user);
        $manager->persist($asset3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
