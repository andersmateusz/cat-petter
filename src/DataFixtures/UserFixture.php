<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserFixture extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = (new User())->setEmail('cat_lover@cat.com');
        $user2 = (new USer())->setEmail('cats_killingit@cat.com');
        $user1->setPassword($this->hasher->hashPassword($user1, 'mySuperStrongPassword'));
        $user2->setPassword($this->hasher->hashPassword($user2, 'mySuperStrongPassword'));
        $manager->persist($user1);
        $manager->persist($user2);
        $manager->flush();
    }
}