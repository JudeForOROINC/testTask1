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

    public function testView()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/view/1');



        $this->assertTrue($crawler->filter('html:contains("View project")')->count() > 0);

    }

    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/create');

        $this->assertTrue($crawler->filter('html:contains("Create new project")')->count() > 0);

    }


    public function testCreateProject()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/create');

        $this->assertTrue($crawler->filter('html:contains("Create new project")')->count() > 0);

    }

    public function testUpdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/update/1');

        $this->assertTrue($crawler->filter('html:contains("Edit the project")')->count() > 0);

    }

}
