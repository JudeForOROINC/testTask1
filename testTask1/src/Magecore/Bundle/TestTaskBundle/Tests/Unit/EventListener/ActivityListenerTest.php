<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\EventListener;

use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\DicPriority;
use Magecore\Bundle\TestTaskBundle\Entity\DicResolution;
use Magecore\Bundle\TestTaskBundle\Entity\DicStatus;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\EventListener\ActivityListener;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;

class ActivityListenerTest extends \PHPUnit_Framework_TestCase
{

    public function testSet()
    {
        $mock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $arg = $this->getMock('LifecycleEventArgs');

        $listener = new ActivityListener($mock);

        $listener->postPersist($arg);



    }
}
