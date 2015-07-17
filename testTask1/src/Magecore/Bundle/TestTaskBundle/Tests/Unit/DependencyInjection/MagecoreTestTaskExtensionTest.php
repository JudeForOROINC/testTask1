<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\DependencyInjection;

use Magecore\Bundle\TestTaskBundle\DependencyInjection\MagecoreTestTaskExtension;
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


class MagecoreTestTaskExtensionTest extends SymfonyEntityTest
{

    public function testSet()
    {
        $file = new MagecoreTestTaskExtension();

        $cont = $this->getMockBuilder('Symfony\Component\DependencyInjection\ContainerBuilder')
           ->disableOriginalConstructor()->getMock();

        $file->load(array(), $cont);

       // $this->assertTrue('pum' !== $entity->getType());


    }
}