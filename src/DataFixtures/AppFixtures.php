<?php

namespace App\DataFixtures;

use App\Factory\AdFactory;
use App\Factory\CategoryFactory;
use App\Factory\RoleFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        RoleFactory::createRoles();
        UserFactory::createMany(10);
        CategoryFactory::createCategories();
        AdFactory::createMany(50);
    }
}
