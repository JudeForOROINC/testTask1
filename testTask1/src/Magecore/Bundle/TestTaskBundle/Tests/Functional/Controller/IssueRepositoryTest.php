<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;


class IssueRepositoryTest extends WebTestCase
{

    public function setUp()
    {
//        $client = static::createClient();
//        $conte = $client->getContainer();
//        $em = $conte->get('doctrine')->getManager();
//
//        $fixture = new ORM\Test\LoadTestData();
//
//        $fixture->setContainer($conte);
//
//        $purger = new ORMPurger($em);
//        //$purger->setPurgeMode( ORMPurger::PURGE_MODE_TRUNCATE );
//        $purger->setPurgeMode( ORMPurger::PURGE_MODE_DELETE );
//        $executor = new ORMExecutor($em, $purger);
//
//        $executor->execute([$fixture], false);



//        $fixture->load($em);

    }
    public function testIndex()
    {
        //return $this->render('Project/index.html.twig');
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine')->getManager();

        $repo = $em->getRepository('MagecoreTestTaskBundle:Issue');

        $statusOpen = $em->getRepository('MagecoreTestTaskBundle:DicStatus')->findOneBy(['value'=>'value.open']);

        $Oper = $em->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'Operator']);

        $operatorList = $repo->findBy(array('status'=>$statusOpen,'assignee'=>$Oper));

        $testOperlist = $repo->findOpenByUserId($Oper->getId());

        $this->assertEquals($testOperlist,$operatorList);//test page;

        //$this->assertTrue($crawler->filter('html:contains("'..'")')->count() > 0);//test page;

        //findOpenByCollaboratorId
        $repo = $em->getRepository('MagecoreTestTaskBundle:Issue');

        $testOperlist =  $repo->findOpenByCollaboratorId($Oper->getId());

        $res = count($testOperlist);

        unset($testOperlist);

        $this->assertGreaterThan(0,$res, 'do not find collaborators');

        $repo = $em->getRepository('MagecoreTestTaskBundle:Issue');

        $operatorList1 = $repo->findBy(array('status'=>$statusOpen,'assignee'=>$Oper));

        $this->assertGreaterThan(0,count($operatorList1), 'do not find collaborators');

        $repo = $em->getRepository('MagecoreTestTaskBundle:Issue');

        $operatorList2 = $repo->findBy(array('status'=>$statusOpen,'reporter'=>$Oper));

        $this->assertGreaterThan(0,count($operatorList2), 'do not find collaborators');

        $operatorList = array_merge($operatorList1,$operatorList2);

        $this->assertGreaterThan(0,count($operatorList), 'do not find collaborators');

        $this->assertEquals($res,count($operatorList));
    }


}
