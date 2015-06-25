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
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadDictionaries($manager);
        $this->loadProjects($manager);
        $this->loadIssue($manager);
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
        //var_dump($johnUser->getSalt());
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

        $manAdmin = new User();
        $manAdmin->setUsername('Operator');
        $manAdmin->setEmail('yo_man_2@symfony.com');
        $manAdmin->setRoles(array('ROLE_OPERATOR'));
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
    private function loadDictionaries(ObjectManager $manager)
    {
        /*
        $DicVals = array('Bug', 'Subtask', 'Task', 'Story');
        foreach ($DicVals as $dicVal) {

            $dicType = new Entity\DicType();
            $dicType->setValue($dicVal);
            $manager->persist($dicType);
        }
        */

        $DicVals = array('Done', 'Fixed', 'Can not reproduce', 'Duplicate');
        foreach ($DicVals as $dicVal) {

            $dicResolution = new Entity\DicResolution();
            $dicResolution->setValue($dicVal);
            $manager->persist($dicResolution);
        }

        $DicVals = array(3=>'Major', 1=>'Minor', 5=>'Blocker', 4=>'Kritical', 2=>'Trivial');
        //value = link to dic major(3) ; minor(1); blocker(5);critical(4);trivial(2))
        foreach ($DicVals as $key=>$dicVal) {

            $dicPriority = new Entity\DicPriority();
            $dicPriority->setValue($dicVal);
            $dicPriority->setSortOrder($key);
            $manager->persist($dicPriority);
        }

        $DicVals = array(1=>'Open', 2=>'In progress', 3=>'Closed');
        //var_dump($DicVals);
        //value = link to dic major(3) ; minor(1); blocker(5);critical(4);trivial(2))
        foreach ($DicVals as $key=>$dicVal) {

            $dicStatus = new Entity\DicStatus();
            $dicStatus->setValue($dicVal);
            $dicStatus->setSortOrder($key);
            $manager->persist($dicStatus);
        }

        $manager->flush();
    }


    protected function loadProjects(ObjectManager $manager){
        //pro
        $user = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Manager']);
        $userOperator = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Operator']);


        $Project = new Project();
        //var_dump($Project);
        $Project->setCode('M1M');
        $Project->setLabel('Create An Enviroment');
        $Project->setSummary('To start must be installed inviroment. install all needable Programs. set up Storm, Xdebug ect.');
        $Project->addMember($user);
        $Project->addMember($userOperator);

        $manager->persist($Project);

        $Project = new Project();
        $Project->setCode('P1');
        $Project->setLabel('TestTask for Newcomer');
        $Project->setSummary('Set up a test task for a real sprint. logg and try to test skills and accurassy.');

        $manager->persist($Project);

        $manager->flush();
    }

    protected function loadIssue(ObjectManager $manager){
        //pro
        $user = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Manager']);
        $userOperator = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Operator']);
        $project = $manager->getRepository('MagecoreTestTaskBundle:Project')->findOneBy(['code'=>'M1M']);

        $issue = new Issue();
        $issue->setType($issue::ISSUE_TYPE_STORY);
        $issue->setProject($project);
        $issue->setReporter($user);
        $issue->setAssignee($userOperator);
        $issue->setSummary('Prepere work place');
        $issue->setCode('Prepere work place');//TODO fix this field!!!
        $issue->setDescription('Must be prepere work place. install windows 8. Try to put all needed Programs. inspall php. read Jira. ' );

        $manager->persist($issue);

        $issue = new Issue();
        $issue->setType($issue::ISSUE_TYPE_TASK);
        $issue->setProject($project);
        $issue->setReporter($user);
        $issue->setAssignee($userOperator);
        $issue->setSummary('Buy a computer ');
        $issue->setCode('Prepere work place');//TODO fix this field!!!
        $issue->setDescription('Please , Buy a computer for NewCommer.' );


        $manager->persist($issue);

        $issue = new Issue();
        $issue->setType($issue::ISSUE_TYPE_BUG);
        $issue->setProject($project);
        $issue->setReporter($user);
        $issue->setAssignee($userOperator);
        $issue->setSummary('Not enaf operative memory ');
        $issue->setCode('Prepere work place');//TODO fix this field!!!
        $issue->setDescription('subj' );


        $manager->persist($issue);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}

