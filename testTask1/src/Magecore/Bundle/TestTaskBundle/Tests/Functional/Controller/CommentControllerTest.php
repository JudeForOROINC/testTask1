<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Functional\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Form\Form;

class CommentControllerTest extends WebTestCase
{
    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Admin',//'JustUser',
            'PHP_AUTH_PW'   => '123',
        ));

        // Create a new comment in issue
        $em = $client->getContainer()->get('doctrine')->getManager();

        $issue = $em->getRepository('MagecoreTestTaskBundle:Issue')->findOneBy(['summary' => 'TestTask1: made timeing']);

        $user = $em->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username' => 'Admin']);

        $url = $client->getContainer()->get('router')->generate('magecore_testtask_comment_create',array('id'=>$issue->getId()));
        //magecore_testtask_comment_create - url to new comment

        // Create a new entry in the database
        $crawler = $client->request('POST', $url);
        $this->assertEquals(400, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for corrupted request Post $url");
        //$this->asserttrue($crawler->filter('context:("You can access this only using Ajax!")')->count()>0);

        $crawler = $client->request('POST', $url, array(), array(), array(
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ));

        $this->assertEquals(500, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for corrupted request Post $url");


//        $comment = new Comment();
//
//        $comment->setIssue($issue);
//        $comment->setAuthor($user);
//
//        $type = new CommentType();
//        $form = $this->factory->create($type);



        /*$crawler = $client->click($crawler->selectLink('Create a new entry')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Create')->form(array(
            'magecore_bundle_testtaskbundle_comment[field_name]'  => 'Test',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Test")')->count(), 'Missing element td:contains("Test")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Edit')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'magecore_bundle_testtaskbundle_comment[field_name]'  => 'Foo',
            // ... other fields to fill
        ));

        $client->submit($form);
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="Foo"]')->count(), 'Missing element [value="Foo"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Delete')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/Foo/', $client->getResponse()->getContent());
        */
    }


}
