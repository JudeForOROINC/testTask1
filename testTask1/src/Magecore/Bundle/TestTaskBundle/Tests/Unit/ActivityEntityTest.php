<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\DicPriority;
use Magecore\Bundle\TestTaskBundle\Entity\DicResolution;
use Magecore\Bundle\TestTaskBundle\Entity\DicStatus;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;


class ActivityEntityTest extends \PHPUnit_Framework_TestCase
{
    protected $setVal=
        array(
            'setType'=>'CIAT',
            'setIssue'=>null,
            'setUser'=>null,
            'setTime'=>null,
            'setFromIssueStatus'=>null,
            'setToIssueStatus'=>null,
            'setComment'=>null,
        );

    protected $propMeth=
        array(


            'setType'=>'type',
            'setIssue'=>'issue',
            'setUser'=>'user',
            'setTime'=>'time',
            'setFromIssueStatus'=>'fromIssueStatus',
            'setToIssueStatus'=>'toIssueStatus',
            'setComment'=>'comment',

        );

    protected $proplist=
        array(
            'type'=>'getType',
            'issue'=>'getIssue',
            'user'=>'getUser',
            'id'=>'getId',
            'time'=>'getTime',
            'fromIssueStatus'=>'getFromIssueStatus',
            'toIssueStatus'=>'getToIssueStatus',
            'comment'=>'getComment',


        );

    protected $propvals=
        array(
            'type'=>'CIAT',
            'issue'=>null,
            'user'=>null,
            'id'=>5,
            'time'=>null,
            'fromIssueStatus'=>null,
            'toIssueStatus'=>null,
            'comment'=>null,

        );

    protected function CheckCollection($entity, $element,$opt=array()){
        $get_collection = $opt['get_collection'];
        $add_element = $opt['add_element'];
        $remove_element = $opt['remove_element'];

        $empty_collection = $entity->$get_collection();
        $entity->$add_element($element);
        $this->assertTrue($entity->$get_collection()->contains($element));
        $entity->$remove_element($element);
        $this->assertEquals($empty_collection,$entity->$get_collection());

    }

    public function testSet()
    {
        $entity = new Activity();

        $user = new User();
        $issue = new Issue();
        $Fromstatus = new DicStatus();
        $tostatus = new DicStatus();
        $comment = new Comment();
        $time = new \DateTime();

        $this->propvals['user'] = $user;
        $this->propvals['issue'] = $issue;

        $this->propvals['time'] = $time;
        $this->propvals['fromIssueStatus'] = $Fromstatus;
        $this->propvals['toIssueStatus'] = $tostatus;
        $this->propvals['comment'] = $comment;


        $this->setVal['setUser'] = $user;
        $this->setVal['setIssue'] = $issue;

        $this->setVal['setTime'] = $time;
        $this->setVal['setFromIssueStatus'] = $Fromstatus;
        $this->setVal['setToIssueStatus'] = $tostatus;
        $this->setVal['setComment'] = $comment;

        //$member = new User();

        $r = new \ReflectionClass($entity);
        $a = $r->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        //      var_dump($a);
        foreach ($this->setVal as $k => $v) {
            $entity->$k($v);
            $prop = $r->getProperty($this->propMeth[$k]);
            $prop->setAccessible(true);
            $val = $prop->getValue($entity);
            $this->assertEquals($v, $val, 'Property ' . $this->propMeth[$k] . ' has wrong value for ' . $k);
            $prop->setAccessible(false);
        }

        foreach ($this->propvals as $k => $v) {
            $prop = $r->getProperty($k);
            $prop->setAccessible(true);
            $prop->setValue($entity, $v);
            $prop->setAccessible(false);
            $getter = $this->proplist[$k];
            $val = $entity->$getter();

            $this->assertEquals($v, $val, 'method ' . $this->proplist[$k] . ' return wrong value for property ' . $k);

        }



        //new issue
        $entity->setType($entity::ACTIVITY_TYPE_CREATE_ISSUE);
        $this->assertTrue($entity->isNewIssueType());
        //upd issue
        $entity->setType($entity::ACTIVITY_TYPE_CHANGE_STATUS_ISSUE);
        $this->assertTrue($entity->isChangeStatusType());
        //comment
        $entity->setType($entity::ACTIVITY_TYPE_COMMENT_IN_ISSUE);
        $this->assertTrue($entity->isCommentType());
    }
}