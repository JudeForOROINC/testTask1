<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Functional\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IssueControllerTest extends WebTestCase
{

    public function testCompleteScenario()
    {
        // Create a new client to browse the application
        //var_dump('issue start');
        //$client = static::createClient();
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'Admin',//'JustUser',
            'PHP_AUTH_PW'   => '123',
        ));

        // Create a new entry in the database
        $crawler = $client->request('GET', '/issue/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(),
            "Unexpected HTTP status code for GET /issue/");
        $lnk2 =$crawler->selectLink('Show');

        $this->assertGreaterThan(0,$lnk2->count(), 'Missing Show Button');

        $lnk = $crawler->selectLink('Edit');
        $this->assertGreaterThan(0,$lnk->count(), 'Missing Edit Button');

        $this->assertTrue($lnk->count() > 0);

        $crawler = $client->click($lnk->first()->link());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET /issue/edit/");


        $this->assertGreaterThan(0, $crawler->filter('html:contains("Issue edit")')->count(), 'Missing Issue edit form title' );



        $form = $crawler->selectButton('Update')->form(array(
            'magecore_bundle_testtaskbundle_issue[summary]'  => 'Mama mila ramu',
            'magecore_bundle_testtaskbundle_issue[description]'  => 'Discription:Mama mila ramu',
            'magecore_bundle_testtaskbundle_issue[priority]' => $crawler->filter('#magecore_bundle_testtaskbundle_issue_priority option:contains("Major")')->attr('value'),
            'magecore_bundle_testtaskbundle_issue[status]' => $crawler->filter('#magecore_bundle_testtaskbundle_issue_status option:contains("Open")')->attr('value'),
        ));

        $client->submit($form);



            $this->assertEquals(302, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code. must be a redirect to show page");

        if ($client->getResponse()->isRedirect()){
            $crawler = $client->followRedirect();
        }

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code. must be the show page");

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Issue")')->count(), 'Missing Issue show title' );

        $en = $client->getContainer()->get('doctrine')->getManager();

        $project = $en->getRepository('MagecoreTestTaskBundle:Project')->findOneBy(['code' => 'T3T']);

        if(!$project){
            $project = new Project();
            $project->setCode('T3T');
            $project->setLabel('Project Label');
            $project->setSummary('Very big discription of a Project of a T3T');
            $en->persist($project);
            $en->flush();
        };
        //create story Story
        $url = $client->getContainer()->get('router')->generate('magecore_testtask_issue_create',array('id'=>$project->getId()));

        $crawler = $client->request('GET',$url);


        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code. must be the create issue page");

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Issue creation")')->count(), 'Missing Issue create title' );

        $form = $crawler->selectButton('Create')->form(array(
            'magecore_bundle_testtaskbundle_issue[summary]'  => 'Mama mila ramu',
            'magecore_bundle_testtaskbundle_issue[description]'  => 'Discription:Mama mila ramu',
            'magecore_bundle_testtaskbundle_issue[priority]' => $crawler->filter('#magecore_bundle_testtaskbundle_issue_priority option:contains("Major")')->attr('value'),
            'magecore_bundle_testtaskbundle_issue[type]' => $crawler->filter('#magecore_bundle_testtaskbundle_issue_type option:contains("Story")')->attr('value'),
            'magecore_bundle_testtaskbundle_issue[status]' => $crawler->filter('#magecore_bundle_testtaskbundle_issue_status option:contains("Open")')->attr('value'),
            'magecore_bundle_testtaskbundle_issue[assignee]' => $crawler->filter('#magecore_bundle_testtaskbundle_issue_assignee option:contains("JustUser")')->attr('value'),
        ));

        $client->submit($form);



        $this->assertEquals(302, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code. must be a redirect to show page");

        if ($client->getResponse()->isRedirect()){
            $crawler = $client->followRedirect();
        }

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code. must be the show page");

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Issue")')->count(), 'Missing Issue show title' );
        //create subtusk
        $lnk2 =$crawler->selectLink('Add child issue');

        //$button = $crawler->selectButton('Add child issue');

        //var_dump($button);

        $this->assertGreaterThan(0, $lnk2->count(), 'Missing Issue child create button' );

        $crawler = $client->click($lnk2->link());

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code. must be the create child issue page");

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Issue creation")')->count(), 'Missing Issue create title' );

        $form = $crawler->selectButton('Create')->form(array(
            'magecore_bundle_testtaskbundle_issue[summary]'  => 'Mama mila ramu child',
            'magecore_bundle_testtaskbundle_issue[description]'  => 'Discription:Mama mila ramu. child',
            'magecore_bundle_testtaskbundle_issue[priority]' => $crawler->filter('#magecore_bundle_testtaskbundle_issue_priority option:contains("Major")')->attr('value'),
            'magecore_bundle_testtaskbundle_issue[status]' => $crawler->filter('#magecore_bundle_testtaskbundle_issue_status option:contains("Open")')->attr('value'),
            'magecore_bundle_testtaskbundle_issue[assignee]' => $crawler->filter('#magecore_bundle_testtaskbundle_issue_assignee option:contains("JustUser")')->attr('value'),
        ));

        $client->submit($form);

        $this->assertEquals(302, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code. must be a redirect to show page");

        if ($client->getResponse()->isRedirect()){
            $crawler = $client->followRedirect();
        }

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code. must be the show page");

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Issue")')->count(), 'Missing Issue show title' );



    }

    /**
     * @depends testCompleteScenario
     */
    public function testView(){
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'JustUser',
            'PHP_AUTH_PW'   => '123',
        ));

        // Create a new entry in the database
        //check all errors & miss
        $em = $client->getContainer()->get('doctrine')->getManager();
        $last_issue = $em->createQueryBuilder()
            ->select('i')
            ->from('MagecoreTestTaskBundle:Issue', 'i')
            ->orderBy('i.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        //$project = $en->getRepository('MagecoreTestTaskBundle:Issue')->findOneBy(['code' => 'T3T']);

        $id = $last_issue->getId();

        $url_last = $client->getContainer()->get('router')->generate('magecore_testtask_issue_show',array('id'=>$id++) );
        $url = $client->getContainer()->get('router')->generate('magecore_testtask_issue_show',array('id'=>$id) );

        //test error on absent issue link
        $crawler = $client->request('GET', $url);
        $this->assertEquals(404, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for GET ".$url." !");

        $url=$client->getContainer()->get('router')->generate('magecore_testtask_issue_edit',array('id'=>$id) );

        //test error on absent issue link when edit
        $crawler = $client->request('GET', $url);
        $this->assertEquals(404, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for Edit GET ".$url." !");



        $url=$client->getContainer()->get('router')->generate('magecore_testtask_issue' );
        //test issue list
        $crawler = $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for list GET ".$url." !");
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Issue list")')->count(), 'Missing Issue list title' );

        $crawler = $client->request('GET', $url_last);
        $this->assertEquals(403, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for unaccess issue GET ".$url." !");


        $project = $last_issue->getProject();
        $user = $em->getRepository('MagecoreTestTaskBundle:User')->findOneBy(['username'=>'JustUser']);

        //test create issue by non member user
        $url = $client->getContainer()->get('router')->generate('magecore_testtask_issue_create',array('id'=>$project->getId()));

        $crawler = $client->request('GET', $url);
        $this->assertEquals(403, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for Create issue with non member user GET ".$url." !");

        $project->addMember($user);
        $em->persist($project);
        $em->flush();

        $crawler = $client->request('GET', $url_last);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for child issue GET ".$url." !");


        $lnk = $crawler->selectLink('Edit');
        $this->assertGreaterThan(0,$lnk->count(), 'Missing Edit Button');

        //update put fake
        $url = $client->getContainer()->get('router')->generate('issue_update',array('id'=>$id));

        $crawler = $client->request('PUT', $url);
        $this->assertEquals(404, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for update missing issue with member user GET ".$url." !");

        $url = $client->getContainer()->get('router')->generate('issue_update',array('id'=>$last_issue->getid()));

        $crawler = $client->request('PUT', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for Create issue with non member user GET ".$url." !");


        $url = $client->getContainer()->get('router')->generate('magecore_testtask_issue_subtask_create',array('id'=>$last_issue->getid()));

        $crawler = $client->request('Get', $url);
        $this->assertEquals(400, $client->getResponse()->getStatusCode(), "Unexpected HTTP status code for Create child issue with member user on wrong issue type GET ".$url." !");

//        $this->assertGreaterThan(0, $crawler->filter('html:contains("Issue list")')->count(), 'Missing Issue list title' );
        $issues = $project->getIssues();
        if ($issues) {
            foreach($issues as $issue)
                $em->remove($issue);
            }
        $em->remove($project);
        $em->flush();

    }

}
