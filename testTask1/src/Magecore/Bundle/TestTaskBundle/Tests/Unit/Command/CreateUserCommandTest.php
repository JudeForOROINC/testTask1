<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\EventListener;

use Magecore\Bundle\TestTaskBundle\Command\CreateUserCommand;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;
use Magecore\Bundle\TestTaskBundle\Entity\User;
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

    }


    /**
     * @dataProvider dataProvider
     */
    public function testExecuteHelp($username, $chek_name, $check_mail, $role, $isSuper)
    {
        $application = new Application();

        $commande = new CreateUserCommand();

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $doctrine  = $this->getMock('Symfony\Bridge\Doctrine\RegistryInterface');

        $manager = $this->getMock('Doctrine\ORM\EntityManagerInterface');

        $doctrine->expects($this->once())->method('getEntityManager')->will($this->returnValue($manager));


        $container->expects($this->at(0))->method('get')->with($this->equalTo('doctrine'))
            ->will($this->returnValue($doctrine));

        if (!$chek_name && !$check_mail) {
            $factory = $this->getMock('Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface');
            $encoder = $this->getMock('Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface');
            $factory->expects($this->once())->method('getEncoder')->will($this->returnValue($encoder));

            $container->expects($this->at(1))->method('get')->with($this->equalTo('security.encoder_factory'))
                ->will($this->returnValue($factory));
        }
        $QueryBuilder = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')->disableOriginalConstructor()->getMock();
        $QueryBuilder->expects($this->any())->method('select')->will($this->returnSelf());
        $QueryBuilder->expects($this->any())->method('from')->will($this->returnSelf());
        $QueryBuilder->expects($this->any())->method('where')->will($this->returnValue($QueryBuilder));
        $QueryBuilder->expects($this->any())->method('setParameter')->will($this->returnValue($QueryBuilder));

        $query = $this->getMockBuilder('Doctrine\ORM\AbstractQuery')->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $QueryBuilder->expects($this->any())->method('getQuery')->will($this->returnValue($query));


        $repa = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');

        if ($chek_name) {
            $repa->expects($this->at(0))->method('findBy')->will($this->returnValue(array(1)));
            $this->setExpectedException('Exception', 'User already exists.');
        }
        if ($check_mail) {
            $repa->expects($this->at(1))->method('findBy')->will($this->returnValue(array(1)));
            $this->setExpectedException('Exception', 'This mailbox is forbidden. Please choose another one.');
        }

        $manager->expects($this->exactly($chek_name? 1 : 2))->method('getRepository')
            ->will($this->returnValue($repa));

        //test entity
        if (!$chek_name && !$check_mail) {
            $manager->expects($this->once())->method('persist')->with($this->callback(
                function ($entity) use ($username, $role) {
                    /** @var User $entity */
                    $this->assertInstanceOf('Magecore\Bundle\TestTaskBundle\Entity\User', $entity);
                    $this->assertEquals($username, $entity->getUsername());
                    $this->assertEquals($role, $entity->getRole());
                    return true;
                }
            ));
        }

        $commande->setContainer($container);

        $application->add($commande);

        $command = $application->find('my:user:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
        'username' => 'username',
        'email' => 'email',
        'password' => 'password',
        'fullname' => 'full',
            '--help' => true,
            '--super-admin' => !$isSuper,
        ));


    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return array(
            array('username',0,0,'ROLE_OPERATOR',0),
            array('username',0,0,'ROLE_ADMIN',1),
            array('username',1,0,0,0),
            array('username',0,1,0,0),
        );
    }
}
