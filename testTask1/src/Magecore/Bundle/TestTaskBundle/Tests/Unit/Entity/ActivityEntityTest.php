<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Entity;

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
use Magecore\Bundle\TestTaskBundle\Tests\SymfonyEntityTest;


class ActivityEntityTest extends SymfonyEntityTest
{

    public function dataProviderTest(){
        $activity = new Activity();
        return array(
            array(
                'type', 'CIAT', $activity,
            ),
            array(
                'issue',new Issue(), $activity,
            ),
            array(
                'user', new User(), $activity,
            ),
            array(
                'id', 5, $activity,
            ),
            array(
                'time', new \DateTime(), $activity,
            ),
            array(
                'fromIssueStatus', new DicStatus(), $activity,
            ),
            array(
                'toIssueStatus', new DicStatus(), $activity,
            ),
            array(
                'comment', new Comment(), $activity,
            )
        );
    }

    /**
     * @dataProvider dataProviderTest()
     */
    public function testSettersTest($field,$value, $entity){
        $this->GetSetTest($field,$value,$entity);
    }


    /**
     * @depends testSettersTest
     */
    public function testSetTypes()
    {
        $entity = new Activity();

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

    public function testWrongType(){
        $entity = new Activity();

        $entity->setType($entity::ACTIVITY_TYPE_COMMENT_IN_ISSUE);
        $this->assertTrue($entity->isCommentType());
        $entity->setType('pum');
        $this->assertTrue('pum' !== $entity->getType());


    }
}