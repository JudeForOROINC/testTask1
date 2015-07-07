<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;


class ProjectEntityTest extends \PHPUnit_Framework_TestCase
{
    protected $setVal=
        array(
            'setLabel'=>'mama mia',
            'setSummary'=>'Europe/Kiev',
            'setCode'=>'rty'
        );

    protected $propMeth=
        array(
            'setLabel'=>'label',
            'setSummary'=>'summary',
            'setCode'=>'code'
        );

    protected $proplist=
        array(
            'label'=>'getLabel',
            'summary'=>'getSummary',
            'code'=>'getCode',
            'id'=>'getId'
        );

    protected $propvals=
        array(
            'label'=>'mama mia',
            'summary'=>'Europe/Kiev',
            'code'=>'rty',
            'id'=>5
        );

    public function testSet()
    {
        $user = new Project();

        $member = new User();

        $r = new \ReflectionClass($user);
        $a = $r->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
  //      var_dump($a);
        foreach ($this->setVal as $k=>$v){
            $user->$k($v);
            $prop = $r->getProperty($this->propMeth[$k]);
            $prop->setAccessible(true);
            $val = $prop->getValue($user);
            $this->assertEquals($v,$val,'Property '.$this->propMeth[$k].' has wrong value for '.$k );
            $prop->setAccessible(false);
        }

        foreach ($this->propvals as $k=>$v){
            $prop = $r->getProperty($k);
            $prop->setAccessible(true);
            $prop->setValue($user,$v);
            $prop->setAccessible(false);
            $getter = $this->proplist[$k];
            $val = $user->$getter();

            $this->assertEquals($v,$val,'method '.$this->proplist[$k].' return wrong value for property '.$k );

        }

        //members chech;
        $emtymembers = $user->getMembers();
        $user->addMember($member);
        $this->assertTrue($user->getMembers()->contains($member));
        $user->removeMember($member);
        $this->assertEquals($emtymembers,$user->getMembers());
        //set members check
        $user->addMember($member);
        $this->assertTrue($user->isMember($member));

        $user->setMembers($emtymembers);

        $this->assertEquals($emtymembers,$user->getMembers());

        //tostring
        $this->assertEquals($user->getCode(),(string)$user);

        //issue

        $issue = new Issue();

        $empty_issue = $user->getIssues();

        $user->addIssue($issue);

        $this->assertGreaterThan(0, count($user->getIssues()));

        $user->removeIssue($issue);

        $this->assertEquals($empty_issue,$user->getIssues());


    }
}
