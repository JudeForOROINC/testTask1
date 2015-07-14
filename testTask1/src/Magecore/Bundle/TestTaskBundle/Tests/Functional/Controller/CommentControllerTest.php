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

        $url_issue = $client->getContainer()->get('router')->generate('magecore_testtask_issue_show',array('id'=>$issue->getId()));

        $url = $client->getContainer()->get('router')->generate('magecore_testtask_comment_create',array('id'=>$issue->getId()));
        //magecore_testtask_comment_create - url to new comment

//        //Test bad request
//        $crawler = $client->request('POST', $url);
//        $this->assertEquals(400, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for corrupted request Post $url");

//        $this->assertJson( $client->getResponse()->getContent());

        $crawler = $client->request('GET', $url_issue);

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for  $url_issue");

        $form = $crawler->selectButton('Submit')->form(array(
            'magecore_bundle_testtaskbundle_comment[body]'  => 'Mama mila ramu',)
        );


        $frm = $form->getValues();

        var_dump($frm);

        $data = array(
            'body'=>'Mama mila ramu',
            '_token'=>$frm['magecore_bundle_testtaskbundle_comment[_token]'],
        );

        $json = json_encode($frm);
        #$json = json_encode($data);
        #$json = json_encode(serialize($data));
        #$json = json_encode(serialize($frm));

        var_dump($json);
        //$client->setHeader('X_REQUESTED_WITH');
   //     var_dump($url);
 //       var_dump($form);
        $crawler = $client->request('POST', $url, array('magecore_bundle_testtaskbundle_comment'=>$data),array(),array(
//            'Content-Type'=>'application/json',
//            'XMLHttpRequest'=>'X-Requested-With',
//            #
//#            'X-Requested-With' => 'XMLHttpRequest',
//            'x-requested-with'=>'XMLHttpRequest',
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
            'CONTENT_TYPE' => 'application/json',
            ),
#        '{"message":"You can access this only using Ajax!"}'
            #$json
            #serialize($data)
            //serialize($frm)
            $json
        );


        $resp = $client->getResponse()->getContent();

        var_dump($resp);

    }

    /**
     *@depends testCompleteScenario
     */
    public function testEdit(){
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Admin',//'JustUser',
            'PHP_AUTH_PW'   => '123',
        ));

        // Create a new comment in issue
        $em = $client->getContainer()->get('doctrine')->getManager();

        $issue = $em->getRepository('MagecoreTestTaskBundle:Issue')->findOneBy(['summary' => 'TestTask1: made timeing']);

        $user = $em->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username' => 'Admin']);

        $url = $client->getContainer()->get('router')->generate('magecore_testtask_comment_edit',array('id'=>$issue->getId()));

    }

}
