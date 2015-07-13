<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 13.07.15
 * Time: 10:28
 */

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Form\Type;

use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Form\IssueType;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Symfony\Component\Form\Test\TypeTestCase;

class IssueTypeTest extends TypeTestCase
{

    public function testGetName()
    {
        $type = new IssueType();
        $this->assertEquals('magecore_bundle_testtaskbundle_issue', $type->getName());
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'resolution' => 'test',
            'status' => 'test2',
            'priority' => 'test2',
            'type' => 'test2',
            'assignee' => 'test2',
            'description' => 'test2',
            'summary' => 'test2',
        );

        $type = new IssueType();
        $form = $this->factory->create($type);

        //$object = TestObject::fromArray($formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
//        $this->assertEquals($object, $form->getData());
//
//        $view = $form->createView();
//        $children = $view->children;
//
//        foreach (array_keys($formData) as $key) {
//            $this->assertArrayHasKey($key, $children);
//        }
    }
    public function testSubmitProject()
    {
        $formData = array(
            'resolution' => 'test',
            'status' => 'test2',
            'priority' => 'test2',
            'type' => 'test2',
            'assignee' => 'test2',
            'description' => 'test2',
            'summary' => 'test2',
        );

        $type = new IssueType();

        $issue = new Issue();
        $issue->setType($issue::ISSUE_TYPE_STORY);
        $form = $this->factory->create($type,$issue,array('projects'=>array('1'=>'Project'),
        ));

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
    }
    public function testSubmitProjects()
    {
        $formData = array(
            'resolution' => 'test',
            'status' => 'test2',
            'priority' => 'test2',
            'type' => 'test2',
            'assignee' => 'test2',
            'description' => 'test2',
            'summary' => 'test2',
        );

        $type = new IssueType();

        $pro = new Project();
        $pro2 = new Project();

        $issue = new Issue();
        $form = $this->factory->create($type,$issue,array('projects'=>array('2'=>$pro,'1'=>$pro2),
        ));

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
    }

    public function testSubmitSubtask()
    {
        $formData = array(
            'resolution' => 'test',
            'status' => 'test2',
            'priority' => 'test2',
            'type' => 'test2',
            'assignee' => 'test2',
            'description' => 'test2',
            'summary' => 'test2',
        );

        $type = new IssueType();


        $issue = new Issue();
        $Parent = new Issue();
        $issue->setParentIssue($Parent);
        $issue->setType($issue::ISSUE_TYPE_SUBTASK);
        $form = $this->factory->create($type,$issue);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
    }

}