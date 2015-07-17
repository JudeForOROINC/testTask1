<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Twig;

use Magecore\Bundle\TestTaskBundle\Twig\AppExtension;

class AppExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testGetGlobals()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $context = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();
        $filemanager = $this->getMock('Magecore\Bundle\TestTaskBundle\Helper\FileManagerHelper');
        $ext = new AppExtension($container, $context, $filemanager);
        $globals = $ext->getGlobals();
        $this->assertEquals(['route_helper' => '@my.helper'], $globals);

    }
    public function testWebpath()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $context = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();
        $filemanager = $this->getMock('Magecore\Bundle\TestTaskBundle\Helper\FileManagerHelper');
        $filemanager->expects($this->once())->method('getWebPath')->will($this->returnArgument(0));

        $ext = new AppExtension($container, $context, $filemanager);
        $globals = $ext->avaPathFilter('ava');
        $this->assertEquals('ava', $globals);

    }

    public function testGetFilters()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $context = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();
        $filemanager = $this->getMock('Magecore\Bundle\TestTaskBundle\Helper\FileManagerHelper');

        $ext = new AppExtension($container, $context, $filemanager);
        $resp = $ext->getFilters();
        $this->assertGreaterThan(0, count($resp));
        $val = $resp[0];
        $this->assertInstanceOf('\Twig_SimpleFilter', $val);
    }

    public function testGetName()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $context = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();
        $filemanager = $this->getMock('Magecore\Bundle\TestTaskBundle\Helper\FileManagerHelper');

        $ext = new AppExtension($container, $context, $filemanager);
        $globals = $ext->getName();
        $this->assertEquals('app_extension', $globals);

    }

    public function testGetTime(){
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $context = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()

            ->getMock();
        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\TokenInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $user = $this->getMockBuilder('Magecore\Bundle\TestTaskBundle\Entity\User')->getMock();
        $user->expects($this->once())->method('getTimezone')->will($this->returnValue('Europe/Paris'));
        $token->expects($this->once())->method('getUser')->will($this->returnValue($user));

        $context->expects($this->once())->method('getToken')->will($this->returnValue($token));




        $filemanager = $this->getMock('Magecore\Bundle\TestTaskBundle\Helper\FileManagerHelper');

        $ext = new AppExtension($container, $context, $filemanager);
        $datetime = new \DateTime('now', new \DateTimeZone('Europe/Kiev'));

        $datetime_new = $ext->datertzFilter($datetime);
        $this->assertEquals($datetime->setTimezone(
            new \DateTimeZone('Europe/Paris')
        )->format('Y.m.d H:i:s'), $datetime_new);
    }
}
