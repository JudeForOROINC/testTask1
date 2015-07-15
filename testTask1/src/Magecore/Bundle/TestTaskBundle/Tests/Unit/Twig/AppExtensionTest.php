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
        //ContainerInterface $container,
        //SecurityContext $context

        $ext = new AppExtension($container, $context);
        $globals = $ext->getGlobals();
        $this->assertEquals(['route_helper' => '@my.helper'], $globals);

    }

    public function testGetFilters()
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $context = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();
        $ext = new AppExtension($container, $context);
        $resp = $ext->getFilters();
        $this->assertGreaterThan(0,count($resp));
        $val = $resp[0];
        $this->assertInstanceOf('\Twig_SimpleFilter',$val);
    }

    public function testGetName(){
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $context = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()
            ->getMock();
        //ContainerInterface $container,
        //SecurityContext $context

        $ext = new AppExtension($container, $context);
        $globals = $ext->getName();
        $this->assertEquals('app_extension', $globals);

    }

    public function testGetTime(){
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
        $context = $this->getMockBuilder('Symfony\Component\Security\Core\SecurityContext')
            ->disableOriginalConstructor()

            ->getMock();
        $token = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token')->disableOriginalConstructor()
            ->setMethods(['getUser'])
            ->getMock();
        $user = $this->getMockBuilder('Magecore\Bundle\TestTaskBundle\Entity\User')->getMock();
        $user->expects($this->once())->method('getTimezone')->will($this->returnValue('Europe/Paris'));
        $token->expects($this->once())->method('getUser')->will( $this->returnValue($user) );

        $context->expects($this->once())->method('getToken')->will( $this->returnValue($token) );




        $ext = new AppExtension($container, $context);
        $datetime = new \DateTime('now',new \DateTimeZone('Europe/Kiev'));

        $datetime_new = $ext->datertzFilter($datetime);
        //$this->assertInstanceOf('DateTime',$datetime_new);
        $this->assertEquals($datetime->setTimezone(new \DateTimeZone('Europe/Paris'))->format('Y.m.d H:i:s'),$datetime_new);
    }
}
