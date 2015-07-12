<?php


namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;
use Magecore\Bundle\TestTaskBundle\Tests\SymfonyEntityTest;


class ProjectEntityTest extends SymfonyEntityTest
{

    public function dataProviderTest(){
        $entity = new Project();
        return array(
            array(
                'label', 'mama mia', $entity,
            ),
            array(
                'summary', 'mama mia dfd', $entity,
            ),
            array(
                'code','rty', $entity,
            ),
            array(
                'id', 5, $entity,
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
        $entity = new Project();
        return array(
            array(
                new User(),$entity,'getMembers','member'
            ),
            array(
                new Issue(),$entity,'getIssues','issue'
            ),
        );
    }


    public function testSetMembers()
    {
        $project = new Project();
        $user = new User;

        $collection = new ArrayCollection();

        $project->addMember($user);
        $this->assertGreaterThan(0,$project->getMembers()->count());

        $this->assertNotEquals($collection,$project->getMembers(),'Testing add user fail');

        $project->setMembers($collection);
        $this->assertTrue($project->getMembers()->count() == 0);

    }

    public function testIsMember()
    {
        $project = new Project();
        $user = new User();
        $project->addMember($user);

        $this->assertTrue($project->isMember($user));
        $this->assertFalse($project->isMember(new User()));

    }



    public function testToString()
    {
        $user = new Project();

        $user->setCode('dd');
        //tostring

        $this->assertEquals($user->getCode(),(string)$user);

    }
}
