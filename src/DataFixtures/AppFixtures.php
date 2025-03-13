<?php

namespace App\DataFixtures;

use App\Factory\DragonTreasureFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Cria 10 registros de User
        UserFactory::createMany(10);
        // Cria 40 registros de DragonTreasure
        DragonTreasureFactory::createMany(40, function () {
            return [
                'owner' => UserFactory::random(), # Define um usuário aleatório como dono do tesouro
            ];
        });
    }
}
