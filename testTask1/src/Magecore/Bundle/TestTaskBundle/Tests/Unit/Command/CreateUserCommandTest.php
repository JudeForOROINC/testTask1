<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\EventListener;

use Magecore\Bundle\TestTaskBundle\Command\CreateUserCommand;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;


class CreateUserCommandTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @expectedException RuntimeException
     */
    public function testExecute()
    {
        $application = new Application();
        $application->add(new CreateUserCommand());

        $command = $application->find('my:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        //$this->assertRegExp('/.../', $commandTester->getDisplay());

    }

    public function testExecuteHelp()
    {
        $application = new Application();

        $commande = new CreateUserCommand();


        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $doctrine  = $this->getMock('Symfony\Bridge\Doctrine\RegistryInterface');

        $manager = $this->getMock('Doctrine\ORM\EntityManagerInterface');

        $doctrine->expects($this->once())->method('getEntityManager')->will($this->returnValue($manager));

        $container->expects($this->once())->method('get')->with($this->equalTo('doctrine'))->will($this->returnValue($doctrine));

        $QueryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')->disableOriginalConstructor()->getMock();
        $QueryBuilder->expects($this->any())->method('select')->will($this->returnSelf());
        $QueryBuilder->expects($this->any())->method('from')->will($this->returnSelf());
        $QueryBuilder->expects($this->any())->method('where')->will($this->returnValue($QueryBuilder));
        $QueryBuilder->expects($this->any())->method('setParameter')->will($this->returnValue($QueryBuilder));

        $query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')->disableOriginalConstructor()->getMockForAbstractClass();

        $QueryBuilder->expects($this->any())->method('getQuery')->will($this->returnValue($query));

        $manager->expects($this->once())->method('createQueryBuilder')->will($this->returnValue($QueryBuilder));

        $commande->setContainer($container);

        $application->add($commande);

        $command = $application->find('my:user:create');
        //$application->set
        //$command->set
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
        'username' => 'username',
        'email' => 'email',
        'password' => 'password',
        'fullname' => 'full',
            '--help' => true,
        ));

        //$this->assertRegExp('/.../',
        var_dump($commandTester->getDisplay());

    }

}
