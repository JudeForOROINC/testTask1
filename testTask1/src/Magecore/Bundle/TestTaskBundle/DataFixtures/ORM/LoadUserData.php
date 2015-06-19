<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 19.06.15
 * Time: 14:00
 */
namespace Magecore\Bundle\TestTaskBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Magecore\Bundle\TestTaskBundle\Entity;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        //$this->loadPosts($manager);
    }

//    /**
//     * {@inheritDoc}
//     */
    /*
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword('test');

        $manager->persist($userAdmin);
        $manager->flush();
    }*/

    private function loadUsers(ObjectManager $manager)
    {


        $johnUser = new User();
        $johnUser->setUsername('JustUser');
        $johnUser->setEmail('john_user@symfony.com');


        $passwordEncoder = $this->container->get('security.encoder_factory')->getEncoder($johnUser);

        $encodedPassword = $passwordEncoder->encodePassword( '123', $johnUser->getSalt());
        $johnUser->setPassword($encodedPassword);
        $johnUser->setEnabled(true);

        $manager->persist($johnUser);

        $annaAdmin = new User();
        $annaAdmin->setUsername('Admin');
        $annaAdmin->setEmail('anna_admin@symfony.com');
        $annaAdmin->setRoles(array('ROLE_ADMIN'));
        $encodedPassword = $passwordEncoder->encodePassword( '123', $annaAdmin->getSalt());
        $annaAdmin->setPassword($encodedPassword);
        $annaAdmin->setEnabled(true);
        $manager->persist($annaAdmin);

        $manAdmin = new User();
        $manAdmin->setUsername('Manager');
        $manAdmin->setEmail('yo_man@symfony.com');
        $manAdmin->setRoles(array('ROLE_MANAGER'));
        $encodedPassword = $passwordEncoder->encodePassword( '123', $manAdmin->getSalt());
        $manAdmin->setPassword($encodedPassword);
        $manAdmin->setEnabled(true);
        $manager->persist($manAdmin);


        $manager->flush();
    }
    private function loadFosUsers(ObjectManager $manager)
    {

        $userManager = $this->container->get('fos_user.user_manager');

        $johnUser = $userManager->createUser();
        $johnUser->setUsername('JustUser');
        $johnUser->setEmail('john_user@symfony.com');

        $passwordEncoder = $this->container->get('security.encoder_factory')->getEncoder($johnUser);

        $encodedPassword = $passwordEncoder->encodePassword( '123', $johnUser->getSalt());
        $johnUser->setPassword($encodedPassword);
        $manager->persist($johnUser);

        $annaAdmin = new User();
        $annaAdmin->setUsername('Admin');
        $annaAdmin->setEmail('anna_admin@symfony.com');
        $annaAdmin->setRoles(array('ROLE_ADMIN'));
        $encodedPassword = $passwordEncoder->encodePassword($annaAdmin, '111');
        $annaAdmin->setPassword($encodedPassword);
        $manager->persist($annaAdmin);

        $manAdmin = new User();
        $manAdmin->setUsername('Manager');
        $manAdmin->setEmail('yo_man@symfony.com');
        $manAdmin->setRoles(array('ROLE_MANAGER'));
        $encodedPassword = $passwordEncoder->encodePassword($manAdmin, '111');
        $manAdmin->setPassword($encodedPassword);
        $manager->persist($manAdmin);


        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}

