<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;


class UserControllerTest extends WebTestCase
{

    public function setUp()
    {
        /*$client = static::createClient();

        //$crawler = $client->request('GET', '/user/list');
        $conte = $client->getContainer();
        $en = $conte->get('doctrine')->getManager();
        $fixture = new ORM\LoadUserData();
        $fixture->setContainer($conte);
        $fixture->load($en);*/

    }
    public function testIndex()
    {
        //return $this->render('Project/index.html.twig');
        $client = static::createClient();

        //$crawler = $client->request('GET', '/user/list');
        $crawler = $client->request('GET', $client->getContainer()->get('router')->generate('magecore_test_task_user_index'));

        $this->assertTrue($crawler->filter('html:contains("User list")')->count() > 0);//test page;

        //$this->assertTrue($crawler->filter('html:contains("'..'")')->count() > 0);//test page;



    }

    public function testView()
    {
        $client = static::createClient();

        $en = $client->getContainer()->get('doctrine')->getManager();

        $user = $en->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username' => 'JustUser']);

        //var_dump($user);

        $this->assertTrue(!empty($user),'User found in storage.');
        //var_dump(!empty($user));

        $url = $client->getContainer()->get('router')->generate('magecore_test_task_user_view',['id'=>$user->getId()]);

        $crawler = $client->request('GET', $url);

        //var_dump( $client->getResponse()->getStatusCode() );

        $this->assertEquals($client->getResponse()->getStatusCode(),200);
       // var_dump($crawler->filter('html:contains("View user")')->count() > 0);

        $this->assertTrue($crawler->filter('html:contains("View user")')->count() > 0);

    }

    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/create');

        $this->assertEquals($client->getResponse()->getStatusCode(),200);
//        $this->assertEquals($crawler->filter('html:contains("Create new project")')->count() > 0);

    }


    /*
    public function testCreateProject()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/create');

        $this->assertTrue($crawler->filter('html:contains("Create new project")')->count() > 0);

    }
    */
    public function testUpdate()
    {
        $client = static::createClient();


        $en = $client->getContainer()->get('doctrine')->getManager();

        $user = $en->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username' => 'JustUser']);

        //var_dump($user);

        $this->assertTrue(!empty($user),'User found in storage.');
        //var_dump(!empty($user));

        $url = $client->getContainer()->get('router')->generate('magecore_test_task_user_update',['id'=>$user->getId()]);

        $crawler = $client->request('GET', $url);

        //var_dump( $client->getResponse()->getStatusCode() );

        $this->assertEquals($client->getResponse()->getStatusCode(),200);
        // var_dump($crawler->filter('html:contains("View user")')->count() > 0);

        $this->assertTrue($crawler->filter('html:contains("Edit User`s Profile!")')->count() > 0);
        //chech fields - is it with right data;
        $form= $crawler->selectButton('Save user')->form();
        //var_dump($form);
        $userfullname = $form->getValues()['user[fullname]'];

        if ($userfullname == 'JustUser Full Name' ){
            $form['user[fullname]']= $compstr = 'Full Name';

        } else {
            $form['user[fullname]']= $compstr = 'JustUser Full Name';
        }
        //var_dump($userfullname);
        $form['user[timezone]']='Europe/Kiev';
        $client->followRedirects(true);
        $crawler = $client->submit($form);

        //$result = $this->client->getResponse();

        $this->assertEquals($client->getResponse()->getStatusCode(),200);
        $this->assertTrue($crawler->filter('html:contains("'.$compstr.'")')->count() > 0);

    }
    public function testAccess(){
        //test access rights!
        //TODO: write tests for buttons && 403.
        //TODO: write test for check Ava Manage;
    }

}
