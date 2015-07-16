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
use Symfony\Component\Validator\Constraints\DateTime;

class ActivityListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *@dataProvider dataProvider
     */
    public function testSet($entity, $data_to_check)
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $arg = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')->disableOriginalConstructor()
            ->getMock()
        ;

        //$this->data_to_check = $data_to_check;
        $arg->expects($this->once())->method('getEntity')->will($this->returnValue($entity));

        $manager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        //test active;
        $manager->expects($this->once())->method('persist')->with($this->callback(
            function ($instance) use ($data_to_check) {
                $result =true;
                /** @var Activity $instance */
                $result *= ($instance instanceof Activity);
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
                        $this->assertEquals($data_to_check['comment_body'], $instance->getComment()->getBody());
                    }
                }
                return $result;
            }

        ));

        $arg->expects($this->once())->method('getEntityManager')->will($this->returnValue($manager));

        $listener = new ActivityListener($container);

        $listener->postPersist($arg);

    }

    public function dataProvider()
    {
        $issue = new Issue();
        $r = new \ReflectionClass($issue);
        $prop=$r->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($issue, 6);
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
        $second_line = array(
            $Comment,
            array('comment_body'=>'errare humanum est','issue_id'=>6,'user'=>'Gro','type'=>'isCommentType')
        );
        return array($first_line,$second_line);
    }

    /**
     * @dataProvider mailerDate
     * @param $set_to
     */
    public function testSendMail($set_to)
    {
        $entity = new Activity();
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $arg = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')->disableOriginalConstructor()
            ->getMock();
        if ($set_to) {
            $mailer = $this->getMockBuilder('Magecore\Bundle\TestTaskBundle\EventListener\MailerListener')
                ->disableOriginalConstructor()->getMock();

            $mailer->expects($this->once())->method('pushMail')->with($this->equalTo($entity));

            $container->expects($this->once())->method('get')->with($this->equalTo('my.mailer'))->will(
                $this->returnValue($mailer)
            );
        }

        $arg->expects($this->once())->method('getEntity')->will($this->returnValue($entity));

        $listener = new ActivityListener($container);

        $listener->setMaySendEmail($set_to);

        $this->assertEquals($set_to, $listener->getMaySendEmail());

        $listener->postPersist($arg);

    }

    /**
     * @return array
     */
    public function mailerDate()
    {
        return array(
            array(true),
            array(false),
        );
    }

    public function testUpdate()
    {
        $entity = new Issue();

        $user = new User();

        $user->setFullName('Bum');

        $entity->setReporter($user);

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $arg = $this->getMockBuilder('Doctrine\ORM\Event\PreUpdateEventArgs')->disableOriginalConstructor()
            ->getMock();

        $arg->expects($this->once())->method('getEntity')->will($this->returnValue($entity));

        $secure_service = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContextInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $user = new User();
        $user->setFullName('Mr Smith');
        $token->expects($this->once())->method('getUser')
            ->will($this->returnValue($user));
        $secure_service->expects($this->once())->method('getToken')->will($this->returnValue($token));

        $arg->expects($this->once())->method('hasChangedField')
            ->with($this->equalTo('status'))
            ->will($this->returnValue(true));
        $status1 = new \Magecore\Bundle\TestTaskBundle\Entity\DicStatus();
        $status1->setValue('old');
        $status2 = new \Magecore\Bundle\TestTaskBundle\Entity\DicStatus();
        $status2->setValue('new');

        $arg->expects($this->once())->method('getOldValue')
            ->with($this->equalTo('status'))
            ->will($this->returnValue($status1));
        $arg->expects($this->once())->method('getNewValue')
            ->with($this->equalTo('status'))
            ->will($this->returnValue($status2));

        $container->expects($this->once())->method('get')->with($this->equalTo('security.context'))
            ->will($this->returnValue($secure_service));

        $listener = new ActivityListener($container);

        $listener->preUpdate($arg);

        //get lifecikles mock;

        $argLife = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $argLife->expects($this->once())->method('getEntity')->will($this->returnValue($entity));
        $manager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();

        $manager->expects($this->once())->method('persist')->with(
            //
            $this->callback(function ($instace) {
                //
                $result = false;
                $this->assertInstanceOf('Magecore\Bundle\TestTaskBundle\Entity\Activity', $instace);
                /** @var Activity $instace */
                $this->assertEquals('Mr Smith', $instace->getUser()->getFullName());
                $this->assertEquals('old', $instace->getFromIssueStatus()->getValue());
                $this->assertEquals('new', $instace->getToIssueStatus()->getValue());
                $result = true;
                return $result;
            })
        );
        $argLife->expects($this->once())->method('getEntityManager')->will($this->returnValue($manager));
        $listener->postUpdate($argLife);
    }

    public function testDateTime()
    {
        //
        $entity = new Activity();
        $entity->setTime(new \DateTime('2015-01-01 10:00:00', new \DateTimeZone('Europe/Paris')));

        $dt = new \DateTime('2015-01-01 10:00:00', new \DateTimeZone('Europe/Paris'));
        $dt->setTimezone(new \DateTimeZone('UTC'));
        $dt = $dt->format('Y-m-d h:i:s');

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $listener = new ActivityListener($container);

        $arg = $this->getMockBuilder('Doctrine\ORM\Event\OnFlushEventArgs')->disableOriginalConstructor()
            ->getMock();
        $manager = $this->getMockBuilder('Doctrine\ORM\EntityManagerInterface')->getMock();

        $unitofwork = $this->getMockBuilder('Doctrine\ORM\UnitOfWork')
            ->disableOriginalConstructor()->getMock();
        $unitofwork->expects($this->once())->method('getScheduledEntityInsertions')
            ->will($this->returnValue([$entity]));
        $unitofwork->expects($this->once())->method('getScheduledEntityUpdates')
            ->will($this->returnValue([]));
        $unitofwork->expects($this->once())->method('recomputeSingleEntityChangeSet')
            ->with(
                $this->anything(),
                $this->callback(function ($active) use ($dt) {
                    $result = false;
                    /** @var Activity $active */
                    $this->assertInstanceOf('Magecore\Bundle\TestTaskBundle\Entity\Activity', $active);
                    $dt2 = $active->getTime();
                    $this->assertEquals('UTC', $dt2->getTimezone()->getName());
                    $dt2 = $dt2->format('Y-m-d h:i:s');
                    $this->assertEquals($dt, $dt2);

                    $result = true;
                    return $result;
                })
            );

        $manager->expects($this->once())->method('getUnitOfWork')->will(
            $this->returnValue($unitofwork)
        );

        $class_metadata = $this->getMockBuilder('\Doctrine\ORM\Mapping\ClassMetadata')
            ->disableOriginalConstructor()->getMock();

        $manager->expects($this->once())->method('getClassMetadata')
            ->with($this->stringContains('Activity'))
            ->will($this->returnValue($class_metadata));

        $arg->expects($this->once())
            ->method('getEntityManager')
            ->will($this->returnValue($manager));

        $listener->onFlush($arg);
    }

    public function testSetDateTime()
    {
        $entity = new Activity();
        $entity->setTime(new \DateTime('2015-01-01 10:00:00'));

        $dt = new \DateTime('2015-01-01 10:00:00', new \DateTimeZone('UTC'));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $listener = new ActivityListener($container);

        $arg = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')->disableOriginalConstructor()
            ->getMock()
        ;
        $arg->expects($this->once())->method('getEntity')->will($this->returnValue($entity));

        $listener->postLoad($arg);
        $this->assertEquals($dt, $entity->getTime());
    }

    public function testSetDateTimeUserProtect()
    {
        $entity = new User();
        $entity->setLastLogin(new \DateTime('2015-01-01 10:00:00'));

        $dt = new \DateTime('2015-01-01 10:00:00', new \DateTimeZone('UTC'));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $listener = new ActivityListener($container);

        $arg = $this->getMockBuilder('Doctrine\ORM\Event\LifecycleEventArgs')->disableOriginalConstructor()
            ->getMock()
        ;
        $arg->expects($this->once())->method('getEntity')->will($this->returnValue($entity));

        $listener->postLoad($arg);
        $this->assertNotEquals($dt, $entity->getLastLogin());
    }

}
