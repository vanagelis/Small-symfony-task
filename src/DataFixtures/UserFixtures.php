<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public const USER = 'user';
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('test1');
        $manager->persist($user);

        $manager->flush();

        $this->addReference(self::USER, $user);
    }
}
