<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder){
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user_email = new User();
        $user_email->setEmail('developers@digidev.io');
        $user_email->setPassword($this->passwordEncoder->encodePassword(
            $user_email,
            'FunnelKake$!'
        ));
        $user_email->setRoles(['ROLE_DEVELOPER']);

        $user_mobile = new User();
        $user_mobile->setMobileNumber('888-200-3110');
        $user_mobile->setPassword($this->passwordEncoder->encodePassword(
            $user_mobile,
            'FunnelKake$!'
        ));
        $user_mobile->setRoles(['ROLE_DEVELOPER']);

        $manager->persist($user_email);
        $manager->persist($user_mobile);

        $manager->flush();
    }
}
