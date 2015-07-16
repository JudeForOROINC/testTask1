<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\EventListener;

use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\DicPriority;
use Magecore\Bundle\TestTaskBundle\Entity\DicResolution;
use Magecore\Bundle\TestTaskBundle\Entity\DicStatus;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\EventListener\ActivityListener;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;

class ActivityListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *@dataProvider dataProvider
     */
    public function testSet($entity,$data_to_check)
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $arg = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')->disableOriginalConstructor()
            ->getMock()
        ;

        $this->data_to_check = $data_to_check;
        $arg->expects($this->once())->method('getEntity')->will($this->returnValue($entity));

        $manager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        //test active;
        $manager->expects($this->once())->method('persist')->with($this->callback(
            function($instance){
                $result =true;
                /** @var Activity $instance */
                $result *= ($instance instanceof Activity);
                //$result *= ($instance );
                $data_to_check = $this->data_to_check;
                if (isset($data_to_check['type'])) {
                    $this->assertTrue($instance->$data_to_check['type']());
                    $this->assertInstanceOf('Magecore\Bundle\TestTaskBundle\Entity\Issue', $instance->getIssue());
                    if (isset($data_to_check['issue_id'])) {
                        $this->assertEquals($data_to_check['issue_id'], $instance->getIssue()->getId());
                    }
                    if (isset($data_to_check['user'])) {
                        $this->assertEquals($data_to_check['user'], $instance->getUser()->getFullName());
                    }

                    if ($data_to_check['type'] == 'isNewIssueType') {
                        if (isset($data_to_check['issue_code'])) {
                            $this->assertEquals($data_to_check['issue_code'], $instance->getIssue()->getCode());
                        }
                    }
                    if ($data_to_check['type'] == 'isCommentType') {
                        $this->assertEquals($data_to_check['comment_body'],$instance->getComment()->getBody());
                    }
                }
                //if ($instance->isCommentType()

                return $result;
            }

        ));

        $arg->expects($this->once())->method('getEntityManager')->will($this->returnValue($manager));

        $listener = new ActivityListener($container);

        $listener->postPersist($arg);

    }

    public function dataProvider(){
        $issue = new Issue();
        $r = new \ReflectionClass($issue);
        $prop=$r->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($issue,6);
        $prop->setAccessible(false);
        $project = new Project();
        $project->setCode('TTT');
        $issue->setProject($project);
        $user=new User();
        $user->setFullName('Gro');
        $issue->setReporter($user);
        $first_line = array($issue,array('issue_id'=>6,'issue_code'=>'TTT-6','user'=>'Gro', 'type'=>'isNewIssueType'));

        $Comment = new Comment();
        $Comment->setIssue($issue);
        $Comment->setAuthor($user);
        $Comment->setBody('errare humanum est');
        $second_line = array($Comment,array('comment_body'=>'errare humanum est','issue_id'=>6,'user'=>'Gro','type'=>'isCommentType'));
        return array($first_line,$second_line);
    }

    public function testSendMail(){
        $entity = new Activity();
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $arg = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')->disableOriginalConstructor()
            ->getMock()
        ;

        $arg->expects($this->once())->method('getEntity')->will($this->returnValue($entity));

        $listener = new ActivityListener($container);

        $listener->postPersist($arg);

    }
}
