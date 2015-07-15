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


        /** @var \Doctrine\Common\Persistence\ObjectManager $em */
        $em = $client->getContainer()->get('doctrine')->getManager();

        $issue = $em->getRepository('MagecoreTestTaskBundle:Issue')->findOneBy(['summary' => 'TestTask1: made timeing']);

        $user = $em->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username' => 'Admin']);

        //clear old data
        $comments = $em->getRepository('MagecoreTestTaskBundle:Comment')->findBy(array('issue'=>$issue,'author'=>$user),array('id'=>'DESC'));
        foreach ($comments as $line){
            $em->remove($line);
        }
        $em->flush();

        $url_issue = $client->getContainer()->get('router')->generate('magecore_testtask_issue_show',array('id'=>$issue->getId()));

        $url = $client->getContainer()->get('router')->generate('magecore_testtask_comment_create',array('id'=>$issue->getId()));
        //magecore_testtask_comment_create - url to new comment

        //Test bad request
        $crawler = $client->request('POST', $url);
        $this->assertEquals(400, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for corrupted request Post $url");

//        $this->assertJson( $client->getResponse()->getContent());

        $crawler = $client->request('GET', $url_issue);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for  $url_issue");

        $form = $crawler->selectButton('Submit')->form(array(
            'magecore_bundle_testtaskbundle_comment[body]'  => 'Mama mila ramu',)
        );


        $frm = $form->getValues();

        //var_dump($frm);

        //test wrong post - validator must acess.

        $data = array(
            'body'=>'Mama mila ramu',
            '_token'=>$frm['magecore_bundle_testtaskbundle_comment[_token]'],
        );

        $wrong_post_data = array(
            'magecore_bundle_testtaskbundle_comment'=>array(
                '_token' => $data['_token']
            )
        );
        $right_post_data = array(
            'magecore_bundle_testtaskbundle_comment'=>$data
        );

        $crawler = $client->request('POST', $url, $wrong_post_data, array(), array(
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
            ));

        $this->assertEquals(400, $client->getResponse()->getStatusCode());

        $this->assertJson($content = $client->getResponse()->getContent());


        $this->assertArrayHasKey('message', $content = json_decode($content, true));

        $this->assertEquals('Error', $content['message']);

        $this->assertArrayHasKey('form', $content);


        $crawler = $client->request('POST', $url, $right_post_data, array(), array(
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
                'CONTENT_TYPE' => 'application/json',
            ));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $comments = $em->getRepository('MagecoreTestTaskBundle:Comment')->findBy(array('issue'=>$issue,'author'=>$user),array('id'=>'DESC'));
        //var_dump($comments);
        $this->assertEquals(1,count($comments));
        $comment = $comments[0];
        $this->assertEquals('Mama mila ramu', $comment->getBody());

        //test user acess = member;
//        var_dump($comment->getId());
//
//        $this->setComment( $comment->getId());

        //$userMember = $em->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username' => 'M']);

    }

    /**
     *@depends testCompleteScenario
     */
    public function testEdit(){
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Admin',//'JustUser',
            'PHP_AUTH_PW'   => '123',
        ));


        $em = $client->getContainer()->get('doctrine')->getManager();

        $issue = $em->getRepository('MagecoreTestTaskBundle:Issue')->findOneBy(['summary' => 'TestTask1: made timeing']);

        $user = $em->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username' => 'Admin']);

        $comment = $em->getRepository('MagecoreTestTaskBundle:Comment')->findOneBy(array('issue'=>$issue,'author'=>$user));

        $url = $client->getContainer()->get('router')
            ->generate('magecore_testtask_comment_edit', array('id'=> $comment->getId()));
        //test bad request
        $crawler = $client->request('POST',$url);

        $this->assertEquals(400,$client->getResponse()->getStatusCode());
        //get form
        $crawler = $client->request('POST', $url, array(), array(), array(
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
        ));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertJson($client->getResponse()->getContent());

        $content = json_decode($client->getResponse()->getContent(),true);

        $this->assertArrayHasKey('message',$content);

        $crawler = $client->getCrawler();
        $crawler->clear();
        $crawler->addContent($content['message']);

        $form = $crawler->selectButton('Submit')->form(array(
                'magecore_bundle_testtaskbundle_comment[body]'  => 'Ababa Galamaga',)
        );

        $frm = $form->getValues();

        $data = array(
            'body'=>$frm['magecore_bundle_testtaskbundle_comment[body]'],
            '_token'=>$frm['magecore_bundle_testtaskbundle_comment[_token]'],
        );

        $right_post_data = array(
            'magecore_bundle_testtaskbundle_comment'=>$data
        );

        $wrong_post_data = array(
            'magecore_bundle_testtaskbundle_comment'=>array(
                '_token'=>$frm['magecore_bundle_testtaskbundle_comment[_token]'],
            )
        );

        $crawler = $client->request('POST', $url, $wrong_post_data, array(), array(
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        //test bad request
        $comments = $em->getRepository('MagecoreTestTaskBundle:Comment')->findBy(array('issue'=>$issue,'author'=>$user),array('id'=>'DESC'));
        $this->assertEquals(1,count($comments));
        $comment = $comments[0];
        $this->assertNotEquals('Ababa Galamaga', $comment->getBody());

        $crawler = $client->request('POST', $url, $right_post_data, array(), array(
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
        ));
        //test true edit;
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $comments = $em->getRepository('MagecoreTestTaskBundle:Comment')->findBy(array('issue'=>$issue,'author'=>$user),array('id'=>'DESC'));
        $this->assertEquals(1,count($comments));
        $comment = $comments[0];
        $this->assertEquals('Ababa Galamaga', $comment->getBody());

    }
    /**
     *@depends testEdit
     */
    public function testDelete(){
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Admin',//'JustUser',
            'PHP_AUTH_PW'   => '123',
        ));

        // Create a new comment in issue
        $em = $client->getContainer()->get('doctrine')->getManager();

        $issue = $em->getRepository('MagecoreTestTaskBundle:Issue')->findOneBy(['summary' => 'TestTask1: made timeing']);

        $user = $em->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username' => 'Admin']);

        //var_dump($this->getComment());

        $comment = $em->getRepository('MagecoreTestTaskBundle:Comment')->findOneBy(array('issue'=>$issue,'author'=>$user));

            //$this->comment;

        //$issue = $comment->getIssue();

        //$user = $comment->getAuthor();

        $url = $client->getContainer()->get('router')
            ->generate('magecore_testtask_comment_delete', array('id'=> $comment->getId()));

        var_dump($url);
            //test bad request edit;
        $crawler = $client->request('POST',$url);

        $this->assertEquals(400,$client->getResponse()->getStatusCode());

        $crawler = $client->request('POST', $url, array(), array(), array(
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
        ));
        $this->assertEquals(200, $client->getResponse()->getStatusCode());


        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $comments = $em->getRepository('MagecoreTestTaskBundle:Comment')->findBy(array('issue'=>$issue,'author'=>$user),array('id'=>'DESC'));
        //var_dump($comments);
        $this->assertEquals(1,count($comments));
        $comment = $comments[0];
        $this->assertNotEquals('Ababa Galamaga', $comment->getBody());

        $crawler = $client->request('POST', $url, $right_post_data, array(), array(
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
        ));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $comments = $em->getRepository('MagecoreTestTaskBundle:Comment')->findBy(array('issue'=>$issue,'author'=>$user),array('id'=>'DESC'));
        //var_dump($comments);
        $this->assertEquals(0,count($comments));

    }



}
