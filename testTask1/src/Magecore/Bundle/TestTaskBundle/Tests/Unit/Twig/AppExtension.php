<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Twig;

use Magecore\Bundle\TestTaskBundle\Twig\AppExtension;

class AppExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testGetGlobals()
    {
        $container = $this->getMock('ContainerInterface');
        $context = $this->getMock('SecurityContext');
        //ContainerInterface $container,
        //SecurityContext $context

        $ext = new AppExtension($container, $context);
        $globals = $ext->getGlobals();
        $this->assertEquals(['route_helper' => '@my.helper'],$globals);

    }

    public  function testGetFilters()
    {
        $ext =  new AppExtension();
    }
}
