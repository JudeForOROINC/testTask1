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
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    protected $issue_cort;

    protected $open_status;

    protected $progress_status;

    protected $close_status;


    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadDictionaries($manager);
        $this->loadProjects($manager);
        $this->loadIssue($manager);
        $this->loadComment($manager);
        $this->loadActivity($manager);

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
        $DicVals = array('Done', 'Fixed', 'Can not reproduce', 'Duplicate');
        $DicVals = array('value.done', 'value.fixed', 'value.nofind', 'value.duplicate');
        foreach ($DicVals as $dicVal) {

            $dicResolution = new Entity\DicResolution();
            $dicResolution->setValue($dicVal);
            $manager->persist($dicResolution);
        }

        $DicVals = array(3=>'Major', 1=>'Minor', 5=>'Blocker', 4=>'Kritical', 2=>'Trivial');
        $DicVals = array(3=>'value.major', 1=>'value.minor', 5=>'value.blocker', 4=>'value.kritical', 2=>'value.trivial');
        //value = link to dic major(3) ; minor(1); blocker(5);critical(4);trivial(2))
        foreach ($DicVals as $key=>$dicVal) {

            $dicPriority = new Entity\DicPriority();
            $dicPriority->setValue($dicVal);
            $dicPriority->setSortOrder($key);
            $manager->persist($dicPriority);
        }

        $DicVals = array(1=>'Open', 2=>'In progress', 3=>'Closed');
        $DicVals = array(1=>'value.open', 2=>'value.inpro', 3=>'value.closed');
        foreach ($DicVals as $key=>$dicVal) {

            $dicStatus = new Entity\DicStatus();
            $dicStatus->setValue($dicVal);
            $dicStatus->setSortOrder($key);
            $manager->persist($dicStatus);
            if ($dicVal === 'value.open'){
                $this->open_status = $dicStatus;
            }
            if ($dicVal === 'value.closed'){
                $this->close_status = $dicStatus;
            }
            if ($dicVal === 'value.inpro'){
                $this->progress_status = $dicStatus;
            }
        }

        $manager->flush();
    }


    protected function loadProjects(ObjectManager $manager){
        //pro
        $user = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Manager']);
        $userOperator = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Operator']);
        $userworker = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'JustUser']);


        $Project = new Project();
        //var_dump($Project);
        $Project->setCode('M1M');
        $Project->setLabel('Create An Enviroment');
        $Project->setSummary('To start must be installed inviroment. install all needable Programs. set up Storm, Xdebug ect.');
        $Project->addMember($user);
        $Project->addMember($userOperator);
        $Project->addMember($userworker);

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
        $userworker = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'JustUser']);
        $statusOpen = $this->open_status;
        //$manager->getRepository('MagecoreTestTaskBundle:DicStatus')->findOneBy(['value'=>'value.open']);
        $Prioritymid = $manager->getRepository('MagecoreTestTaskBundle:DicPriority')->findOneBy(['sortOrder'=>3]);



        $issue = new Issue();
        $issue->setType($issue::ISSUE_TYPE_TASK);
        $issue->setProject($project);
        $issue->setReporter($user);
        $issue->setAssignee($userOperator);
        $issue->setSummary('Buy a computer ');
        $issue->setStatus($statusOpen);
        $issue->setPriority($Prioritymid);
        $issue->addCollaborator($user);
        $issue->addCollaborator($userOperator);

        $issue->setCode('Prepere work place');//TODO fix this field!!!
        $issue->setDescription('Please , Buy a computer for NewCommer.' );


        $manager->persist($issue);

        $issue = new Issue();
        $issue->setType($issue::ISSUE_TYPE_BUG);
        $issue->setProject($project);
        $issue->setReporter($user);
        $issue->setAssignee($userOperator);
        $issue->setSummary('Not enaf operative memory ');
        $issue->setCode('cort');//TODO fix this field!!!
        $issue->setDescription('subj' );
        $issue->setStatus($statusOpen);
        $issue->setPriority($Prioritymid);
        $issue->addCollaborator($user);
        $issue->addCollaborator($userOperator);


        $this->issue_cort = $issue;
        $manager->persist($issue);

        $issue = new Issue();
        $issue->setType($issue::ISSUE_TYPE_STORY);
        $issue->setProject($project);
        $issue->setReporter($user);
        $issue->setAssignee($userOperator);
        $issue->setSummary('Prepere work place');
        $issue->setCode('Prepere work place');//TODO fix this field!!!
        $issue->setDescription('Must be prepere work place. install windows 8. Try to put all needed Programs. inspall php. read Jira. ' );
        $issue->setStatus($statusOpen);
        $issue->setPriority($Prioritymid);
        $issue->addCollaborator($user);
        $issue->addCollaborator($userOperator);

        $manager->persist($issue);


        $issuesub = new Issue();
        $issuesub->setType($issuesub::ISSUE_TYPE_SUBTASK);
        $issuesub->setProject($project);
        $issuesub->setReporter($user);
        $issuesub->setAssignee($userOperator);
        $issuesub->setParentIssue($issue);
        $issuesub->setSummary('Install windows');
        $issuesub->setCode('Wind');//TODO fix this field!!!
        $issuesub->setDescription('insstall w8x64' );
        $issuesub->setStatus($statusOpen);
        $issuesub->setPriority($Prioritymid);
        $issuesub->addCollaborator($user);
        $issuesub->addCollaborator($userOperator);

        $manager->persist($issuesub);

        $issuesub = new Issue();
        $issuesub->setType($issuesub::ISSUE_TYPE_SUBTASK);
        $issuesub->setProject($project);
        $issuesub->setReporter($user);
        $issuesub->setAssignee($userworker);
        $issuesub->setParentIssue($issue);
        $issuesub->setSummary('Prepare VM oracle VB with ubuntu ');
        $issuesub->setCode('Prepere work place');//TODO fix this field!!!
        $issuesub->setDescription('subj. prepeare phpstorm,xdebug,php,mysql,ect.' );
        $issuesub->setStatus($statusOpen);
        $issuesub->setPriority($Prioritymid);
        $issuesub->addCollaborator($user);
        $issuesub->addCollaborator($userOperator);

        $manager->persist($issuesub);

        $project = $manager->getRepository('MagecoreTestTaskBundle:Project')->findOneBy(['code'=>'P1']);

        $issue = new Issue();
        $issue->setType($issue::ISSUE_TYPE_STORY);
        $issue->setProject($project);
        $issue->setReporter($userOperator);
        $issue->setAssignee($userOperator);
        $issue->setSummary('TestTask1: made timeing');
        $issue->setCode('Yo');//TODO fix this field!!!
        $issue->setDescription('Plan work with a little tasks and add it like subtasks! ' );
        $issue->setStatus($statusOpen);
        $issue->setPriority($Prioritymid);
        $issue->addCollaborator($userOperator);

        $manager->persist($issue);


        $manager->flush();
    }


    protected function loadComment(ObjectManager $manager){
        //pro
        //$user = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Manager']);
        $userOperator = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Operator']);
        //$userworker = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'JustUser']);
        //$project = $manager->getRepository('MagecoreTestTaskBundle:Project')->findOneBy(['code'=>'M1M']);
        //$issue = $manager->getRepository('MagecoreTestTaskBundle:Issue')->findOneBy(['code'=>'cort']);
        $issue = $this->issue_cort;

        //$openStatus = $manager->getRepository('MagecoreTestTaskBundle:DicStatus')->findOneBy(['value'=>'Open']);


        $comment = new Entity\Comment();

        $comment->setIssue($issue);
        $comment->setAuthor($userOperator);
        $comment->setBody('do not find any memory problems. May be its good to remove windows?');

        $manager->persist($comment);


        $manager->flush();
    }




    protected function loadActivity(ObjectManager $manager){
        //pro
        $user = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Manager']);
        $userOperator = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Operator']);
        $userworker = $manager->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'JustUser']);
        $project = $manager->getRepository('MagecoreTestTaskBundle:Project')->findOneBy(['code'=>'M1M']);
        $issue = $this->issue_cort;//$manager->getRepository('MagecoreTestTaskBundle:Issue')->findOneBy(['code'=>'cort']);
        //$issue = $manager->getRepository('MagecoreTestTaskBundle:Issue')->findOneBy(['code'=>'cort']);
        $comment = $manager->getRepository('MagecoreTestTaskBundle:Comment')->findOneBy(['issue'=>$issue->getId()]);

        //var_dump($issue->getComments()->first());
       // return;


        $openStatus = $this->open_status;
            //$manager->getRepository('MagecoreTestTaskBundle:DicStatus')->findOneBy(['value'=>'Open']);
        $closeStatus = $this->close_status;
            //$manager->getRepository('MagecoreTestTaskBundle:DicStatus')->findOneBy(['value'=>'Closed']);
        $proStatus = $this->progress_status;



        $activity = new Entity\Activity();
        $activity->setType($activity::ACTIVITY_TYPE_CREATE_ISSUE);
        $activity->setIssue($issue);
        $activity->setUser($user);
        $activity->setTime( date_sub($activity->getTime(),\DateInterval::createFromDateString('2 days') ) );

        $manager->persist($activity);


        $activity = new Entity\Activity();
        $activity->setType($activity::ACTIVITY_TYPE_CHANGE_STATUS_ISSUE);
        $activity->setIssue($issue);
        $activity->setUser($userOperator);
        $activity->setFromIssueStatus($openStatus);
        $activity->setToIssueStatus($proStatus);
        $activity->setTime( date_sub($activity->getTime(),\DateInterval::createFromDateString('1 days') ) );

        $manager->persist($activity);



        $activity = new Entity\Activity();
        $activity->setType($activity::ACTIVITY_TYPE_COMMENT_IN_ISSUE);
        $activity->setIssue($issue);
        $activity->setUser($userOperator);
        $activity->setComment($comment);

        $manager->persist($activity);

        $activity = new Entity\Activity();
        $activity->setType($activity::ACTIVITY_TYPE_CHANGE_STATUS_ISSUE);
        $activity->setIssue($issue);
        $activity->setUser($userOperator);
        $activity->setFromIssueStatus($proStatus);
        $activity->setToIssueStatus($closeStatus);


        $manager->persist($activity);

        $issue = $manager->getRepository('MagecoreTestTaskBundle:Issue')->findOneBy(['code'=>'Yo']);

        $activity = new Entity\Activity();
        $activity->setType($activity::ACTIVITY_TYPE_CREATE_ISSUE);
        $activity->setIssue($issue);
        $activity->setUser($userOperator);
        $activity->setTime( date_sub($activity->getTime(),\DateInterval::createFromDateString('1 days') ) );

        $manager->persist($activity);




        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}

