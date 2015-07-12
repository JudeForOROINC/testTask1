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


class IssueEntityTest extends SymfonyEntityTest
{

    public function dataProviderTest(){
        $entity = new Issue();
        return array(
            array(
                'code', 'getCode', $entity,
            ),
            array(
                'assignee', new User(), $entity,
            ),
            array(
                'reporter',new User(), $entity,
            ),
            array(
                'id', 5, $entity,
            ),
            array(
                'summary', 'getSummary', $entity,
            ),
            array(
                'description', 'getDescription', $entity,
            ),
            array(
                'type', 'Story', $entity,
            ),
            array(
                'priority', new DicPriority(), $entity,
            ),
            array(
                'resolution', new DicResolution(), $entity,
            ),
            array(
                'status', new DicStatus(), $entity,
            ),
            array(
                'created', new \DateTime(), $entity,
            ),
            array(
                'updated', new \DateTime(), $entity,
            ),
        );
    }

    /**
     * @dataProvider dataProviderTest()
     */
    public function testSettersTest($field,$value, $entity){
        $this->GetSetTest($field,$value,$entity);
    }


    /**
     * @param $element
     * @param $entity
     * @param $get_collection_method_name
     * @param $add_remove_collection_element_name
     * @dataProvider dataProviderCollections
     */
    public function testCollections($element,$entity, $get_collection_method_name, $add_remove_collection_element_name){
        $this->CollectionTest($element,$entity, $get_collection_method_name, $add_remove_collection_element_name);

    }

    public function dataProviderCollections(){
        $entity = new Issue();
        return array(
            array(
                new Comment(),$entity,'getComments','comment'
            ),
            array(
                new User(),$entity,'getCollaborators','collaborator'
            ),
            array(
                new Activity(),$entity,'getActivities','activity'
            ),
            array(
                new Issue(),$entity,'getChildren','child'
            ),
        );
    }

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

    public function testToString(){
        //
        $entity = new Issue();
        $entity->setCode('ABA-12');
        //tostring
        $this->assertEquals($entity->getCode(),(string)$entity);
    }

    public function testTypeCkeck(){
        $entity = new Issue();

        //story
        $entity->setType('Story');
        $this->assertTrue($entity->isStory());

        //subtask
        $entity->setType($entity::ISSUE_TYPE_SUBTASK);
        $this->assertTrue($entity->isSubtask());

        //parenttypes
        $ret = $entity->getParentTypes();
        $this->assertEquals(3,count($ret));

    }



    public function testParentIssue()
    {
        $entity = new Issue();

        //parentissue
        $issue = new Issue();
        $entity->setParentIssue($issue);
        $this->assertEquals($issue,$entity->getParentIssue());
    }

    public function testSet()
    {
        $entity = new Issue();

        //project
        $project = new Project();
        $entity->setProject($project);
        $this->assertEquals($project,$entity->getProject());
    }
}
