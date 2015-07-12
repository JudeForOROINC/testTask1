<?php

namespace Magecore\Bundle\TestTaskBundle\Tests;

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


class SymfonyEntityTest extends \PHPUnit_Framework_TestCase
{

    protected function GetSetTest($property,$value,$entity){
        $r = new \ReflectionClass($entity);
        $setMethod = 'set'.ucfirst($property);
        $getMethod = 'get'.ucfirst($property);

        //test is it method exists:
        if (method_exists($entity,$setMethod)){
            $entity->$setMethod($value);
            $prop = $r->getProperty($property);
            $prop->setAccessible(true);
            $val = $prop->getValue($entity);
            $this->assertEquals($value, $val, ' Property ' . $property . ' has wrong value for ' . $setMethod);
            $prop->setAccessible(false);
        }

        //test is it method exists:
        if (method_exists($entity,$getMethod)){
            $prop = $r->getProperty($property);
            $prop->setAccessible(true);
            $prop->setValue($entity,$value);
            $prop->setAccessible(false);
            $val = $entity->$getMethod();

            $this->assertEquals($value, $val, ' Property ' . $property . ' has wrong value for ' . $getMethod);
        }
    }

    protected function CollectionTest($element,$entity,$get_collection,$add_remove_element){
        $add_element = 'add'.ucfirst($add_remove_element);

        $remove_element = 'remove'.ucfirst($add_remove_element);

        $empty_collection = null;

        if( method_exists($entity,$get_collection) ){
            //test empty state; to check from __construct.
            $empty_collection = $entity->$get_collection();
            $this->assertNotEquals($get_collection,null);

            //testing add element;
            if( method_exists($entity,$add_element) ) {
                $entity->$add_element($element);
                $this->assertTrue($entity->$get_collection()->contains($element));


                //testing remove element;
                if (method_exists($entity, $remove_element)) {
                    $entity->$remove_element($element);
                    $this->assertEquals($empty_collection,$entity->$get_collection());
                }
            }
        }

    }

}