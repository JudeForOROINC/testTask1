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


class DicResolutionEntityTest extends \PHPUnit_Framework_TestCase
{
    protected $setVal =
        array(
            'setValue' => 'getValue',
        );

    protected $propMeth =
        array(

            'setValue' => 'value',
        );

    protected $proplist =
        array(

            'value' => 'getValue',
            'id' => 'getId',
        );

    protected $propvals =
        array(
            'value' => 'getValue',
            'id' => 5,
        );

    protected function CheckCollection($entity, $element, $opt = array())
    {
        $get_collection = $opt['get_collection'];
        $add_element = $opt['add_element'];
        $remove_element = $opt['remove_element'];

        $empty_collection = $entity->$get_collection();
        $entity->$add_element($element);
        $this->assertTrue($entity->$get_collection()->contains($element));
        $entity->$remove_element($element);
        $this->assertEquals($empty_collection, $entity->$get_collection());

    }

    public function testSet()
    {
        $entity = new DicResolution();


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

        //tostring
        $this->assertEquals($entity->getValue(), (string)$entity);

    }
}