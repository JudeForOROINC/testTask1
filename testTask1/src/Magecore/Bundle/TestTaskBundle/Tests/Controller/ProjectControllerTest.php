<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ProjectControllerTest extends WebTestCase
{

    public function testIndex()
    {
        //return $this->render('Project/index.html.twig');
        $client = static::createClient();

        $crawler = $client->request('GET', '/project/list');

        $this->assertTrue($crawler->filter('html:contains("Projects list")')->count() > 0);
    }

}
