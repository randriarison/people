<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ){

    }
    public function load(ObjectManager $manager): void
    {
        $superAdminEmail = $_ENV['SUPER_ADMIN_EMAIL'];
        $superAdminUser = $manager->getRepository(User::class)->findOneBy(['email' => $superAdminEmail]);
        if (!$superAdminUser) {
            $superAdminUser = new User();
        }
        $superAdminUser->setEmail($superAdminEmail);
        $hashedPassword = $this->passwordHasher->hashPassword(
            $superAdminUser,
            "people123!"
        );
        $superAdminUser->setPassword($hashedPassword);
        $superAdminUser->setVerified(true);
        $superAdminUser->setPhoneNumber("0700000000");
        $superAdminUser->setFirstname("Super admin");
        $superAdminUser->setLastname("people");
        $superAdminUser->setRoles(["ROLE_SUPER_ADMIN"]);
        $manager->persist($superAdminUser);

        $manager->flush();
    }
}
