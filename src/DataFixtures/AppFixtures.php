<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Profil;
use Lcobucci\JWT\Parsing\Encoder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Role\Role;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this-> encoder =$encoder;
    }
    public function load(ObjectManager $manager)
    {
        $role= new Profil();
        $role->setLibelle('Admin_system');
        $manager->persist($role);
        
        $role1= new Profil();
        $role1->setLibelle('Admin');
        $manager->persist($role1);
    
        $role2= new Profil();
        $role2->setLibelle('Caissier');
        $manager->persist($role2);

        //
        $role3= new Profil();
        $role3->setLibelle('Partenaire');
        $manager->persist($role3);
        //
        $admin = new User ;
        $admin->setUsername('zahrati');
        $admin->setLogin('zahrati@gmail.com');
        $admin->setPassword($this->encoder->encodePassword($admin ,'admin123')) ;
        $admin->setProfil($role);
        $manager->persist($admin);
        //dd($admin);

        $manager->flush();
    }
}
