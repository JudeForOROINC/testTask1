<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Entity;

use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\DicPriority;
use Magecore\Bundle\TestTaskBundle\Tests\SymfonyEntityTest;
use Magecore\Bundle\TestTaskBundle\Entity\DicResolution;
use Magecore\Bundle\TestTaskBundle\Entity\DicStatus;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;


class DicPriorityEntityTest extends SymfonyEntityTest
{

    public function dataProviderTest(){
        $entity = new DicPriority();
        return array(
            array(
                'sortOrder', 1, $entity,
            ),
            array(
                'value','getValue', $entity,
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

    public function testToString()
    {
        $entity = new DicPriority();

        //tostring
        $this->assertEquals($entity->getValue(), (string)$entity);
    }


}