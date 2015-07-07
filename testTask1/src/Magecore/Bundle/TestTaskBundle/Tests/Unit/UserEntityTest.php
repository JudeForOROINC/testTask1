<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;


class UserEntityTest extends \PHPUnit_Framework_TestCase
{
    protected $setVal=
        array(
            'setFullName'=>'mama mia',
            'setTimezone'=>'Europe/Kiev'
        );

    protected $propMeth=
        array(
            'setFullName'=>'full_name',
            'setTimezone'=>'timezone'
        );

    public function testSet()
    {
        $user = new User();

        $r = new \ReflectionClass($user);

        foreach ($this->setVal as $k=>$v){
            $user->$k($v);
            $prop = $r->getProperty($this->propMeth[$k]);
            $prop->setAccessible(true);
            $val = $prop->getValue($user);
            $this->assertEquals($v,$val,'Property '.$this->propMeth[$k].' has wrong value for '.$k );
            $prop->setAccessible(false);
        }

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