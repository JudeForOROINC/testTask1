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

    public function testSet()
    {
        $entity = new RouterHelper();

        $this->assertEquals('router_helper',$entity->getName());

        $User = new User();

        $r = new \ReflectionClass($User);
        $prop = $r->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($User, 5);
        $prop->setAccessible(false);

        $User->setUsername('ggg');

        $res = $entity->getRoute($User);

        $this->assertEquals('magecore_test_task_user_view', $res['route']);

        $this->assertEquals(['id'=>5], $res['route_params']);
        $this->assertEquals('', $res['anchor']);
        $this->assertEquals('ggg', $res['text']);

        $issue = new Issue();

        $r = new \ReflectionClass($issue);
        $prop = $r->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($issue, 5);
        $prop->setAccessible(false);

        $issue->setCode('ggg');

        $res = $entity->getRoute($issue);

        $this->assertEquals('magecore_testtask_issue_show', $res['route']);

        $this->assertEquals(['id'=>5], $res['route_params']);
        $this->assertEquals('', $res['anchor']);
        $this->assertEquals('ggg', $res['text']);

        $project = new Project();

        $r = new \ReflectionClass($project);
        $prop = $r->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($project, 5);
        $prop->setAccessible(false);

        $project->setCode('ggg');

        $res = $entity->getRoute($project);

        $this->assertEquals('magecore_test_task_project_view', $res['route']);

        $this->assertEquals(['id'=>5], $res['route_params']);
        $this->assertEquals('', $res['anchor']);
        $this->assertEquals('ggg', $res['text']);

        $comment = new Comment();

        $r = new \ReflectionClass($comment);
        $prop = $r->getProperty('id');
        $prop->setAccessible(true);
        $prop->setValue($comment, 9);
        $prop->setAccessible(false);

        $comment->setIssue($issue);

        $res = $entity->getRoute($comment);

        $this->assertEquals('magecore_testtask_issue_show', $res['route']);

        $this->assertEquals(['id'=>5], $res['route_params']);
        $this->assertEquals('#comment-9', $res['anchor']);
        $this->assertEquals('9', $res['text']);

    }
}