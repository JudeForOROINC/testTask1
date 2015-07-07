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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;


class UserEntityTest extends \PHPUnit_Framework_TestCase
{
    protected $setVal=
        array(
            'setTimezone'=>'Europe/Paris',
            'setFullName'=>'MyFullName',
            'setAvapath'=>'path/getAvapath',
            'setFile'=>null,
            'setRemoveAva'=>'getRemoveAva',
        );

    protected $propMeth=
        array(

            'setTimezone'=>'timezone',
            'setFullName'=>'full_name',
            'setAvapath'=>'avapath',
            'setFile'=>'file',
            'setRemoveAva'=>'remove_ava',
        );

    protected $proplist=
        array(
            'timezone'=>'getTimezone',
            'full_name'=>'getFullName',
            'avapath'=>'getAvapath',
            'id'=>'getId',
            'file'=>'getFile',
            'remove_ava'=>'getRemoveAva',

        );

    protected $propvals=
        array(
            'timezone'=>'Europe/Paris',
            'full_name'=>'MyFullName',
            'avapath'=>'path/getAvapath',
            'id'=>5,
            'file'=>'getFileName',
            'remove_ava'=>'getRemoveAva',

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
        $entity = new User();

        $file = null;
        //$this->propvals['comment'] = $comment;


        $this->setVal['setFile'] = $file;
        //$member = new User();

        $r = new \ReflectionClass($entity);
        $a = $r->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        //      var_dump($a);
        foreach ($this->setVal as $k=>$v){
            $entity->$k($v);
            $prop = $r->getProperty($this->propMeth[$k]);
            $prop->setAccessible(true);
            $val = $prop->getValue($entity);
            $this->assertEquals($v,$val,'Property '.$this->propMeth[$k].' has wrong value for '.$k );
            $prop->setAccessible(false);
        }

        foreach ($this->propvals as $k=>$v){
            $prop = $r->getProperty($k);
            $prop->setAccessible(true);
            $prop->setValue($entity,$v);
            $prop->setAccessible(false);
            $getter = $this->proplist[$k];
            $val = $entity->$getter();

            $this->assertEquals($v,$val,'method '.$this->proplist[$k].' return wrong value for property '.$k );

        }
         //owner

        $this->assertTrue($entity->isOwner($entity));

        $upl_dir = $entity->getUploadDir();
        $this->assertTrue(!empty($upl_dir));
        $upl_web_dir = $entity->getUploadRootDir();
        $this->assertTrue(!empty($upl_web_dir));

        $entity->setAvapath('pum');

        $this->assertEquals($upl_dir.'/pum',$entity->getWebPath() );

        $this->assertEquals($upl_web_dir.'/pum',$entity->getAbsolutePath() );

        //$upl_web_dir


//
//        //tostring
//        $this->assertEquals($entity->getCode(),(string)$entity);
//
//        //story
//        $entity->setType('Story');
//        $this->assertTrue($entity->isStory());
//
//        //subtask
//        $entity->setType($entity::ISSUE_TYPE_SUBTASK);
//        $this->assertTrue($entity->isSubtask());
//
//        //parenttypes
//        $ret = $entity->getParentTypes();
//        $this->assertEquals(3,count($ret));
//
//        //parentissue
//        $issue = new Issue();
//        $entity->setParentIssue($issue);
//        $this->assertEquals($issue,$entity->getParentIssue());
//
//        //project
//        $project = new Project();
//        $entity->setProject($project);
//        $this->assertEquals($project,$entity->getProject());
//
//        //child chek
//        $empty_child = $entity->getChildren();
//        $entity->addChild($issue);
//        $this->assertTrue($entity->getChildren()->contains($issue));
//        $entity->removeChild($issue);
//        $this->assertEquals($empty_child,$entity->getChildren());
//
//        //comment chek
//        $comment = new Comment();
//        $empty_comment = $entity->getComments();
//        $entity->addComment($comment);
//        $this->assertTrue($entity->getComments()->contains($comment));
//        $entity->removeComment($comment);
//        $this->assertEquals($empty_comment,$entity->getComments());



        //Issues chek
        $opt['get_collection']='getIssues';
        $opt['add_element']='addIssue';
        $opt['remove_element']='removeIssue';
//        $add_element = $opt['add_element'];
//        $remove_element = $opt['remove_element'];
//        $get_collection = 'getCollaborators';
//        $add_element = 'addCollaborator';
//        $remove_element = 'removeCollaborator';
        $element = new Issue();

        $this->CheckCollection($entity,$element,$opt);


        //activity chek
        $opt['get_collection']='getActivity';
        $opt['add_element']='addActivity';
        $opt['remove_element']='removeActivity';
        $element = new Activity();

        $this->CheckCollection($entity,$element,$opt);





        /*//set members check
        $entity->addMember($member);
        $this->assertTrue($entity->isMember($member));

        $entity->setMembers($emtymembers);

        $this->assertEquals($emtymembers,$entity->getMembers());

        //members chech;
        /*$emtymembers = $entity->getMembers();
        $entity->addMember($member);
        $this->assertTrue($entity->getMembers()->contains($member));
        $entity->removeMember($member);
        $this->assertEquals($emtymembers,$entity->getMembers());
        //set members check
        $entity->addMember($member);
        $this->assertTrue($entity->isMember($member));

        $entity->setMembers($emtymembers);

        $this->assertEquals($emtymembers,$entity->getMembers());



        //issue

        $issue = new Issue();

        $empty_issue = $entity->getIssues();

        $entity->addIssue($issue);

        $this->assertGreaterThan(0, count($entity->getIssues()));

        $entity->removeIssue($issue);

        $this->assertEquals($empty_issue,$entity->getIssues());

*/
        /**
        [
        [8] =>
        class ReflectionProperty#36 (2) {
        public $name =>
        string(6) "status"
        public $class =>
        string(43) "Magecore\Bundle\TestTaskBundle\Entity\Issue"
        }
        [9] =>
        class ReflectionProperty#37 (2) {
        public $name =>
        string(10) "resolution"
        public $class =>
        string(43) "Magecore\Bundle\TestTaskBundle\Entity\Issue"
        }
        [10] =>
        class ReflectionProperty#38 (2) {
        public $name =>
        string(7) "created"
        public $class =>
        string(43) "Magecore\Bundle\TestTaskBundle\Entity\Issue"
        }
        [11] =>
        class ReflectionProperty#39 (2) {
        public $name =>
        string(7) "updated"
        public $class =>
        string(43) "Magecore\Bundle\TestTaskBundle\Entity\Issue"
        }
        [12] =>
        class ReflectionProperty#40 (2) {
        public $name =>
        string(8) "children"
        public $class =>
        string(43) "Magecore\Bundle\TestTaskBundle\Entity\Issue"
        }
        [13] =>
        class ReflectionProperty#41 (2) {
        public $name =>
        string(11) "parentIssue"
        public $class =>
        string(43) "Magecore\Bundle\TestTaskBundle\Entity\Issue"
        }
        [14] =>
        class ReflectionProperty#42 (2) {
        public $name =>
        string(7) "project"
        public $class =>
        string(43) "Magecore\Bundle\TestTaskBundle\Entity\Issue"
        }
        [15] =>
        class ReflectionProperty#43 (2) {
        public $name =>
        string(8) "comments"
        public $class =>
        string(43) "Magecore\Bundle\TestTaskBundle\Entity\Issue"
        }
        [16] =>
        class ReflectionProperty#44 (2) {
        public $name =>
        string(13) "collaborators"
        public $class =>
        string(43) "Magecore\Bundle\TestTaskBundle\Entity\Issue"
        }
        [17] =>
        class ReflectionProperty#45 (2) {
        public $name =>
        string(10) "activities"
        public $class =>
        string(43) "Magecore\Bundle\TestTaskBundle\Entity\Issue"
        }

         *
         */
//        $properties = $r->getProperties( \ReflectionProperty::IS_PROTECTED or \ReflectionProperty::IS_PRIVATE);
//
//        foreach ( $properties as $prop){
//
//        }
//
//        $property->setAccessible(true);
//
//        return $property->getValue($this->testClass);
//        $user->getId();
//        $user->getAbsolutePath();
//        $user->getActivity();
//        $user->getAvapath();
//        $user->getFile();
//        $user->getFullName();
//        $user->getTimezone();
//        $user->setTimezone
//
//
//        // assert that your calculator added the numbers correctly!
//        $this->assertEquals(42, $result);
    }
}
/*
 *
 * abstract class Base extends \PHPUnit_Framework_TestCase
{
    protected $testClass;

    protected $reflection;

    public function setUp()
    {
        $this->reflection = new \ReflectionClass($this->testClass);
    }

    public function getMethod($method)
    {
        $method = $this->reflection->getMethod($method);
        $method->setAccessible(true);

        return $method;
    }

    public function getProperty($property)
    {
        $property = $this->reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->getValue($this->testClass);
    }

    public function setProperty($property, $value)
    {
        $property = $this->reflection->getProperty($property);
        $property->setAccessible(true);

        return $property->setValue($this->testClass, $value);
    }
}*/