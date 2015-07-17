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


class FileManagerTest extends \PHPUnit_Framework_TestCase
{

    protected function setid($class, $id)
    {
        $r = new \ReflectionClass($class);
        $prop = $r->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($class, $id);
        $prop->setAccessible(false);
        return $class;
    }

    public function dataProvider()
    {

        $issue = new Issue();
        $User = new User();
        $project = new Project();
        $comment = new Comment();

        $issue->setCode('ggg');
        $this->setid($issue, 2);

        $User->setUsername('ggg');
        $this->setid($User, 3);

        $project->setCode('ggg');
        $this->setid($project, 4);

        $comment->setIssue($issue);
        $this->setid($comment, 5);

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
    public function testRoute($entity, $id, $right_result)
    {
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

    public function testName()
    {
        $entity = new RouterHelper();
        $this->assertEquals('router_helper', $entity->getName());
    }
}
