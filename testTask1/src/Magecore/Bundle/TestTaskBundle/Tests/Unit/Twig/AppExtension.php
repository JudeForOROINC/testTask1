<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Twig;

use Magecore\Bundle\TestTaskBundle\Twig\AppExtension;

class AppExtensionTest extends \PHPUnit_Framework_TestCase
{

    protected function testGetGlobals()
    {
        $ext = new AppExtension();
        $globals = $ext->getGlobals();
        $this->assertEquals(['route_helper' => '@my.helper'],$globals);
    }

}
