<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IssueControllerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        //$client = static::createClient();
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Admin',//'JustUser',
            'PHP_AUTH_PW'   => '123',
        ));

        // Create a new entry in the database
        $crawler = $client->request('GET', '/issue/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /issue/");
        //var_dump($crawler);
        #$lnk =$crawler->filter('a:contains("Show")');
        #$lnk = $crawler->filter('a:contains("Show")')->eq(0)->link();
        $lnk =$crawler->selectLink('Show');
        #$lnk =$crawler->selectLink('action.show');
        #$url = $lnk->first()->link();

        $this->assertGreaterThan(0,$lnk->count(), 'Missing Show Button');

        $lnk = $crawler->selectLink('Edit');
        $this->assertGreaterThan(0,$lnk->count(), 'Missing Edit Button');

        $this->assertTrue($lnk->count() > 0);

        $crawler = $client->click($lnk->first()->link());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /issue/edit/");

        //var_dump($lnk);
        //var_dump($lnk->eq(1));
//        $crawler = $client->click($lnk->eq(0)->link());
        #$crawler = $client->click($lnk[0]->link());
        //$crawler = $client->click($lnk);


        //$client->followRedirects(true);
        #$client->click($url);

        //$crawler = $client->request('GET','/issue/4');//click($lnk->link());
//        $crawler = $client->request('Get',$url->getUri());//click($lnk->link());
        #$this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /issue/show/");

//
//        // Fill in the form and submit it
//        $form = $crawler->selectButton('Create')->form(array(
//            'magecore_bundle_testtaskbundle_issue[field_name]'  => 'Test',
//            // ... other fields to fill
//        ));
//
//        $client->submit($form);
//        $crawler = $client->followRedirect();
//
//        // Check data in the show view
//        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');
//
//        // Edit the entity
//        $crawler = $client->click($crawler->selectLink('Edit')->link());
//
//        $form = $crawler->selectButton('Update')->form(array(
//            'magecore_bundle_testtaskbundle_issue[field_name]'  => 'Foo',
//            // ... other fields to fill
//        ));
//
//        $client->submit($form);
//        $crawler = $client->followRedirect();
//
//        // Check the element contains an attribute with value equals "Foo"
//        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');
//
//        // Delete the entity
//        $client->submit($crawler->selectButton('Delete')->form());
//        $crawler = $client->followRedirect();
//
//        // Check the entity has been delete on the list
//        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
    }

    public function testView(){
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'JustUser',
            'PHP_AUTH_PW'   => '123',
        ));

        // Create a new entry in the database
        $crawler = $client->request('GET', '/issue/4/edit');
        $this->assertEquals(404, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /issue/");

    }

}
