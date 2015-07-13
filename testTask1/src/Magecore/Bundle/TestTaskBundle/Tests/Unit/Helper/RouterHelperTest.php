<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Helper;

use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\DicPriority;
use Magecore\Bundle\TestTaskBundle\Entity\DicResolution;
use Magecore\Bundle\TestTaskBundle\Entity\DicStatus;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\Helper\RouterHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;


class RouterHelperTest extends \PHPUnit_Framework_TestCase
{

    protected function setid($class , $id){
        $r = new \ReflectionClass($class);
        $prop = $r->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($class, $id);
        $prop->setAccessible(false);
        return $class;
    }

    public function dataProvider(){

        $issue = new Issue();
        $User = new User();
        $project = new Project();
        $comment = new Comment();

        $issue->setCode('ggg');
        $this->setid($issue,2);

        $User->setUsername('ggg');
        $this->setid($User,3);

        $project->setCode('ggg');
        $this->setid($project,4);

        $comment->setIssue($issue);
        $this->setid($comment,5);

        return array(
            array(
                $comment,2, array(
                'route'=>'magecore_testtask_issue_show',
                'anchor'=>'#comment-5',
                'text'=>'5'
                ),
            ),
            array(
                $project,4, array(
                'route'=>'magecore_test_task_project_view',
                'anchor'=>'',
                'text'=>'ggg'
            ),
            ),
            array(
                $issue,2, array(
                'route'=>'magecore_testtask_issue_show',
                'anchor'=>'',
                'text'=>'ggg'
            ),
            ),
            array(
                $User,3, array(
                'route'=>'magecore_test_task_user_view',
                'anchor'=>'',
                'text'=>'ggg'
            ),
            ),

        );
    }


    /**
     * @param $entity
     * @param $id
     * @param $right_result
     * @dataProvider dataProvider
     */
    public function testRoute($entity,$id, $right_result){
        //
        $route = new RouterHelper();
        $res = $route->getRoute($entity);
        $this->assertEquals($right_result['route'], $res['route']);
        $this->assertEquals(['id'=>$id], $res['route_params']);
        $this->assertEquals($right_result['anchor'], $res['anchor']);
        $this->assertEquals($right_result['text'], $res['text']);
    }

    /**
     * @expectedException \Exception
     */
    public function testException()
    {
        $entity = new RouterHelper();
        $entity->getRoute(null);
    }


    public function testName(){
        //

        $entity = new RouterHelper();

        $this->assertEquals('router_helper',$entity->getName());
    }

//    public function testSet()
//    {
//        $entity = new RouterHelper();
//
//        $this->assertEquals('router_helper',$entity->getName());
//
//        $User = new User();
//
//        $r = new \ReflectionClass($User);
//        $prop = $r->getProperty('id');
//        $prop->setAccessible(true);
//        $prop->setValue($User, 5);
//        $prop->setAccessible(false);
//
//        $User->setUsername('ggg');
//
//        $res = $entity->getRoute($User);
//
//        $this->assertEquals('magecore_test_task_user_view', $res['route']);
//
//        $this->assertEquals(['id'=>5], $res['route_params']);
//        $this->assertEquals('', $res['anchor']);
//        $this->assertEquals('ggg', $res['text']);
//
//        $issue = new Issue();
//
//        $r = new \ReflectionClass($issue);
//        $prop = $r->getProperty('id');
//        $prop->setAccessible(true);
//        $prop->setValue($issue, 5);
//        $prop->setAccessible(false);
//
//        $issue->setCode('ggg');
//
//        $res = $entity->getRoute($issue);
//
//        $this->assertEquals('magecore_testtask_issue_show', $res['route']);
//
//        $this->assertEquals(['id'=>5], $res['route_params']);
//        $this->assertEquals('', $res['anchor']);
//        $this->assertEquals('ggg', $res['text']);
//
//        $project = new Project();
//
//        $r = new \ReflectionClass($project);
//        $prop = $r->getProperty('id');
//        $prop->setAccessible(true);
//        $prop->setValue($project, 5);
//        $prop->setAccessible(false);
//
//        $project->setCode('ggg');
//
//        $res = $entity->getRoute($project);
//
//        $this->assertEquals('magecore_test_task_project_view', $res['route']);
//
//        $this->assertEquals(['id'=>5], $res['route_params']);
//        $this->assertEquals('', $res['anchor']);
//        $this->assertEquals('ggg', $res['text']);
//
//        $comment = new Comment();
//
//        $r = new \ReflectionClass($comment);
//        $prop = $r->getProperty('id');
//        $prop->setAccessible(true);
//        $prop->setValue($comment, 9);
//        $prop->setAccessible(false);
//
//        $comment->setIssue($issue);
//
//        $res = $entity->getRoute($comment);
//
//        $this->assertEquals('magecore_testtask_issue_show', $res['route']);
//
//        $this->assertEquals(['id'=>5], $res['route_params']);
//        $this->assertEquals('#comment-9', $res['anchor']);
//        $this->assertEquals('9', $res['text']);
//
//    }
}