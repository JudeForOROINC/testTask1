<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\EventListener;

use Doctrine\Common\Collections\ArrayCollection;
use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\DicPriority;
use Magecore\Bundle\TestTaskBundle\Entity\DicResolution;
use Magecore\Bundle\TestTaskBundle\Entity\DicStatus;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\EventListener\ActivityListener;
use Magecore\Bundle\TestTaskBundle\EventListener\MailerListener;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;

class MailerListenerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *@dataProvider dataProvider
     */
    public function testSet($users, $funk, $pattern, $letter)
    {

        $issue = $this->getMock('Magecore\Bundle\TestTaskBundle\Entity\Issue');
        $usersArray = [];
        foreach ($users as $u) {
            $user = $this->getMock('Magecore\Bundle\TestTaskBundle\Entity\User');

            $user->expects($this->once())->method('getFullName')->will($this->returnValue($u['getFullName']));
            $user->expects($this->once())->method('getEmail')->will($this->returnValue($u['getEmail']));
            $usersArray[] = $user;
        }
        $collaborators = new ArrayCollection($usersArray);

        $issue->expects($this->once())->method('getCollaborators')->will($this->returnValue($collaborators));

        $activity = $this->getMock('Magecore\Bundle\TestTaskBundle\Entity\Activity');

        $activity->expects($this->once())->method('getIssue')->will($this->returnValue($issue));
        if ($funk !='none') {
            $activity->expects($this->exactly(count($users)))->method($funk)->will($this->returnValue(true));
        }

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $trans = $this->getMock('Symfony\Component\Translation\TranslatorInterface');

        $render = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');

        $render->expects($this->exactly(count($users)))->method('render')->will($this->returnValue('ok'));

        if ($funk=='isNewIssueType') {
            $trans->expects($this->exactly(count($users)))->method('trans')->with(
                $this->equalTo('CIAT')
            )->will($this->returnValue('trance ok '));
        }
        if ($funk=='isChangeStatusType') {
            $trans->expects($this->exactly(count($users)))->method('trans')->with(
                $this->equalTo('CSAT')
            )->will($this->returnValue('trance ok '));
        }
        if ($funk=='isCommentType') {
            $trans->expects($this->exactly(count($users)))->method('trans')->with(
                $this->equalTo('COAT')
            )->will($this->returnValue('trance ok '));
        }

        foreach ($users as $k => $u) {
            $render->expects($this->at($k))->method('render')
                ->with(
                    $this->equalTo($pattern),
                    array(
                        'entity' => $activity, 'name' => $u['getFullName']
                    )
                );

        }

        if ($funk != 'none') {
            foreach ($users as $k => $u) {
                $container->expects($this->at(1+$k*2))->method('get')->with($this->equalTo('templating'))
                    ->will($this->returnValue(
                        $render
                    ));
                $container->expects($this->at($k*2))->method('get')
                    ->with($this->equalTo('translator'))
                    ->will($this->returnValue(
                        $trans
                    ));
            }
        } else {
            $container->expects($this->once())->method('get')->with($this->equalTo('templating'))
                ->will($this->returnValue(
                    $render
                ));
        }

        $listener = new MailerListener($container);

        $resultArray = $listener->formMailAction($activity);
        $this->assertEquals($letter, $resultArray);


    }


    /**
     *
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array(
                array(array('getEmail'=>'mail1',
                    'getFullName'=>'testname1'
                ),),
                'func' =>'none',
                'pattern'=>'MagecoreTestTaskBundle:Mailer:view.html.twig',
                'letter'=>array(
                    array(
                        'mail'=>'mail1',
                        'name'=>'testname1',
                        'title'=>'Event for testname1',
                        'letter'=>'ok',
                    )
                )
            ),
            array(
                array(
                    array('getEmail'=>'mail2',
                        'getFullName'=>'testname2'

                        ),
                    array('getEmail'=>'mail3',
                        'getFullName'=>'testname3'
                        ),
                    ),
                'func' =>'isNewIssueType',
                'pattern'=>'MagecoreTestTaskBundle:Mailer:cnia.html.twig',
                'letter'=>array(
                    array(
                        'mail'=>'mail2',
                        'name'=>'testname2',
                        'title'=>'trance ok ',
                        'letter'=>'ok',
                    ),
                    array(
                        'mail'=>'mail3',
                        'name'=>'testname3',
                        'title'=>'trance ok ',
                        'letter'=>'ok',
                    )
                )
            ),
            array(
                array(array('getEmail'=>'mail4',
                    'getFullName'=>'testname4'
                )),
                'func' =>'isChangeStatusType',
                'pattern'=>'MagecoreTestTaskBundle:Mailer:chia.html.twig',
                'letter'=>array(
                    array(
                        'mail'=>'mail4',
                        'name'=>'testname4',
                        'title'=>'trance ok ',
                        'letter'=>'ok',
                    )
                )
            ),
            array(
                array(array('getEmail'=>'mail5',
                    'getFullName'=>'testname5'
                )),
                'func' =>'isCommentType',
                'pattern'=>'MagecoreTestTaskBundle:Mailer:coia.html.twig',
                'letter'=>array(
                    array(
                        'mail'=>'mail5',
                        'name'=>'testname5',
                        'title'=>'trance ok ',
                        'letter'=>'ok',
                    )
                )
            )
        );
    }

    public function testPush()
    {
        // returnValueMap
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $issue = $this->getMock('Magecore\Bundle\TestTaskBundle\Entity\Issue');
        $usersArray = [];
        foreach (array(
                      array('getEmail'=>'mail2@mail.com',
                          'getFullName'=>'testname2'

                      ),
                      array('getEmail'=>'mail3@mail.com',
                          'getFullName'=>'testname3'
                      ),
                  ) as $u) {
            $user = $this->getMock('Magecore\Bundle\TestTaskBundle\Entity\User');

            $user->expects($this->once())->method('getFullName')->will($this->returnValue($u['getFullName']));
            $user->expects($this->once())->method('getEmail')->will($this->returnValue($u['getEmail']));
            $usersArray[] = $user;
        }
        $collaborators = new ArrayCollection($usersArray);

        $issue->expects($this->once())->method('getCollaborators')->will($this->returnValue($collaborators));

        $activity = $this->getMock('Magecore\Bundle\TestTaskBundle\Entity\Activity');

        $activity->expects($this->once())->method('getIssue')->will($this->returnValue($issue));

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $render = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');

        $render->expects($this->exactly(count($usersArray)))->method('render')->will($this->returnValue('ok'));

        $mailer= $this->getMockBuilder('Swift_Mailer')->disableOriginalConstructor()->getMock();

        $mailer->expects($this->any())->method('send')->with($this->isInstanceOf('Swift_Message'))
            ->will($this->returnValue(1));

        $container->expects($this->at(0))->method('get')->with($this->equalTo('mailer'))->will($this->returnValue(
            $mailer
        ));
//TODO find why array map do not work.
//        $container->expects($this->at(1))->method('get')->with($this->equalTo('templating'))
//            ->will($this->returnValueMap(
//                array(
//                    array('templating' , $render),
//                    array('mailer' , $mailer)
//                )
//            ));
        $container->expects($this->at(1))->method('get')->with($this->equalTo('templating'))->will($this->returnValue(
            $render

        ));
        $container->expects($this->at(2))->method('get')->with($this->equalTo('templating'))->will($this->returnValue(
            $render

        ));
//        $container->expects($this->at(3))->method('get')->with($this->equalTo('mailer'))->will($this->returnValueMap(
//            array(
//                array('templating' , $render),
//                array('mailer' , $mailer)
//            )
//        ));

//        $container->expects($this->any())->method('get')->will($this->returnValueMap(
//            array(
//                array('templating' , $render),
//                array('mailer' , $mailer)
//                )
//        ));

        $listener = new MailerListener($container);

        $result = $listener->pushMail($activity);
        $this->assertTrue($result);

    }
}
