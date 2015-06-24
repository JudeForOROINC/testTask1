<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ProjectControllerTest extends WebTestCase
{

    public function testIndex()
    {
        //return $this->render('Project/index.html.twig');
        $client = static::createClient();

        $crawler = $client->request('GET', '/project');

        $this->assertTrue($crawler->filter('html:contains("Projects list")')->count() > 0);
    }
    /*
    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/view/1');



        $this->assertTrue($crawler->filter('html:contains("View project")')->count() > 0);

    }
*/
    /**
     * @depends testIndex
     */
    public function testCreate()
    {
        $client = static::createClient();

        $en = $client->getContainer()->get('doctrine')->getManager();

        $project = $en->getRepository('MagecoreTestTaskBundle:Project')->findOneBy(['code' => 'T3T']);

        //$crawler = $client->request('GET', '/user/list');
        if ($project){
            $en->remove($project);
            $en->flush();
            $project=null;
        }


        $url = $client->getContainer()->get('router')->generate('magecore_test_task_project_create');

        $crawler = $client->request('GET', $url);

        $this->assertEquals($client->getResponse()->getStatusCode(),200);
         //var_dump($crawler->filter('html:contains("Create new project")')->count() > 0);

        $this->assertTrue($crawler->filter('html:contains("Create new project")')->count() > 0);
        //chech fields - is it with right data;
        $form= $crawler->selectButton('Create project')->form();
        //var_dump($form);
        //$userfullname = $form->getValues()['project[fullname]'];


        $form['project[label]']= 'Project Label';
        $form['project[code]']= 'T3T';
        $form['project[summary]']= 'Very big discription of a Project of a T3T';
        //summary


        $client->followRedirects(true);
        $crawler = $client->submit($form);

        //$result = $this->client->getResponse();

        $this->assertEquals($client->getResponse()->getStatusCode(),200);
        if(!$project) {
            $this->assertTrue($crawler->filter('html:contains("View project")')->count() > 0);
        } else {
            $this->assertTrue($crawler->filter('html:contains("This value is already used")')->count() > 0);
        }

    }

/*
    public function testCreateProject()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/create');

        $this->assertTrue($crawler->filter('html:contains("Create new project")')->count() > 0);

    }
*/
    /**
     * @depends testCreate
     */
    public function testUpdate()
    {
        /*$client = static::createClient();

        $crawler = $client->request('GET', '/project/update/1');

        $this->assertTrue($crawler->filter('html:contains("Edit the project")')->count() > 0);*/
        $client = static::createClient();


        $en = $client->getContainer()->get('doctrine')->getManager();

        $project = $en->getRepository('MagecoreTestTaskBundle:Project')->findOneBy(['code' => 'T3T']);

        //var_dump($user);

        $this->assertTrue(!empty($project),'Project found in storage.');
        //var_dump(!empty($user));

        $url = $client->getContainer()->get('router')->generate('magecore_test_task_project_update',['id'=>$project->getId()]);

        $crawler = $client->request('GET', $url);

        //var_dump( $client->getResponse()->getStatusCode() );

        $this->assertEquals($client->getResponse()->getStatusCode(),200);
        // var_dump($crawler->filter('html:contains("View user")')->count() > 0);

        $this->assertTrue($crawler->filter('html:contains("Edit the project")')->count() > 0);
        //chech fields - is it with right data;
        $form= $crawler->selectButton('Save project')->form();
        //var_dump($form);
        $projectlabel = $form->getValues()['project[label]'];

        if ($projectlabel == 'Project Label' ){
            $form['project[label]']= $compstr = 'Project New Label';

        } else {
            $form['project[label]']= $compstr = 'Project Label';
        }
        //var_dump($userfullname);
       // $form['user[timezone]']='Europe/Kiev';
        $client->followRedirects(true);
        $crawler = $client->submit($form);

        //$result = $this->client->getResponse();

        $this->assertEquals($client->getResponse()->getStatusCode(),200);
        $this->assertTrue($crawler->filter('html:contains("'.$compstr.'")')->count() > 0);

    }

}
