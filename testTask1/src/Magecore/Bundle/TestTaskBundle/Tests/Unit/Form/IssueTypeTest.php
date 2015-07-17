<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 13.07.15
 * Time: 10:28
 */

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Form\Type;

use Magecore\Bundle\TestTaskBundle\Entity\DicPriority;
use Magecore\Bundle\TestTaskBundle\Entity\DicResolution;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\Form\IssueType;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\Tests\Unit\EntityType;

use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\ConstraintViolationList;

class IssueTypeTest extends TypeTestCase
{
    protected $project=null;

    public function testGetName()
    {
        $type = new IssueType();
        $this->assertEquals('magecore_bundle_testtaskbundle_issue', $type->getName());
    }
    protected function getExtensions()
    {
        $this->project = new Project();
        $childType = new EntityType(array(
            0=>'user',
            1=>'oper',
            2=>'Admin',
            4=>$this->project
        ));
        return array(new PreloadedExtension(array(
            $childType->getName() => $childType,
        ), array()));
    }

    public function testSubmitValidData()
    {
        $user2 = new User();
        $resolution = new DicResolution();
        $formData = array(
            'resolution' => $resolution,
            'status' => 'test2',
            'priority' => new DicPriority(),
            'type' => 'test2',
            'assignee' => $user2,
            'description' => 'test2',
            'summary' => 'test2',
        );
        $issue = new Issue();
        $str = $issue->getUpdated()->getTimestamp();
        $user = new User();

        $issue->setReporter($user);

        $type = new IssueType();

        $form = $this->factory->create($type, $issue);

        $object = new Issue();
        $object->setStatus('test2');
        $object->setReporter($user);
        $object->setAssignee($user2);
        $object->setSummary('test2');
        $object->setDescription('test2');
        $object->setResolution($resolution);
        $object->setUpdated(new \DateTime($issue->getCreated()->format('Y-m-d H:i:s')));
        $object->setCreated(new \DateTime($issue->getCreated()->format('Y-m-d H:i:s')));

        //TestObject::fromArray($formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
    public function testSubmitProject()
    {
        $user2 = new User();
        $resolution = new DicResolution();
        $formData = array(
            'resolution' => $resolution,
            'status' => 'test2',
            'priority' => new DicPriority(),
            'type' => 'test2',
            'assignee' => $user2,
            'description' => 'test2',
            'summary' => 'test2',
        );
        $issue = new Issue();
        $issue->setType($issue::ISSUE_TYPE_STORY);
        $user = new User();

        $issue->setReporter($user);

        $type = new IssueType();
        $project = new Project();

        $form = $this->factory->create($type, $issue, array('projects'=>array('0'=>$project),
        ));

        $object = new Issue();
        $object->setStatus('test2');
        $object->setReporter($user);
        $object->setAssignee($user2);
        $object->setType($issue::ISSUE_TYPE_STORY);
        $object->setSummary('test2');
        $object->setDescription('test2');
        $object->setResolution($resolution);
        $object->setProject($project);
        $object->setUpdated(new \DateTime($issue->getCreated()->format('Y-m-d H:i:s')));
        $object->setCreated(new \DateTime($issue->getCreated()->format('Y-m-d H:i:s')));

        $form->submit($formData);


        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

    }
    public function testSubmitProjects()
    {
        $user2 = new User();
        $pro2 = new Project();
        $resolution = new DicResolution();
        $formData = array(
            'resolution' => $resolution,
            'status' => 'test2',
            'priority' => new DicPriority(),
            'type' => 'test2',
            'assignee' => $user2,
            'description' => 'test2',
            'summary' => 'test2',
            'project' => $pro2
        );
        $issue = new Issue();
        $issue->setType($issue::ISSUE_TYPE_STORY);
        $user = new User();

        $issue->setReporter($user);

        $type = new IssueType();
        $pro = new Project();


        $form = $this->factory->create($type, $issue, array('projects'=>array('2'=>$pro,'0'=>$pro2),
        ));

        $object = new Issue();
        $object->setStatus('test2');
        $object->setReporter($user);
        $object->setAssignee($user2);
        $object->setType($issue::ISSUE_TYPE_STORY);
        $object->setSummary('test2');
        $object->setDescription('test2');
        $object->setResolution($resolution);
        //$object->setProject($pro2);
        $object->setUpdated(new \DateTime($issue->getCreated()->format('Y-m-d H:i:s')));
        $object->setCreated(new \DateTime($issue->getCreated()->format('Y-m-d H:i:s')));

        $form->submit($formData);


        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

    }

    public function testSubmitSubtask()
    {
        $user2 = new User();
        $resolution = new DicResolution();
        $formData = array(
            'resolution' => $resolution,
            'status' => 'test2',
            'priority' => new DicPriority(),

            'assignee' => $user2,
            'description' => 'test2',
            'summary' => 'test2',
        );
        $issue = new Issue();
        $Parent = new Issue();
        $issue->setParentIssue($Parent);
        $issue->setType($issue::ISSUE_TYPE_SUBTASK);

        $user = new User();

        $issue->setReporter($user);

        $type = new IssueType();

        $form = $this->factory->create($type, $issue);

        $object = new Issue();
        $object->setStatus('test2');
        $object->setReporter($user);
        $object->setAssignee($user2);
        $object->setSummary('test2');
        $object->setDescription('test2');
        $object->setParentIssue($Parent);
        $object->setResolution($resolution);
        $object->setType($issue::ISSUE_TYPE_SUBTASK);
        $object->setUpdated(new \DateTime($issue->getCreated()->format('Y-m-d H:i:s')));
        $object->setCreated(new \DateTime($issue->getCreated()->format('Y-m-d H:i:s')));

        //TestObject::fromArray($formData);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }


//        $issue = new Issue();
//        $Parent = new Issue();
//        $issue->setParentIssue($Parent);
//        $issue->setType($issue::ISSUE_TYPE_SUBTASK);
//        $form = $this->factory->create($type,$issue);
//
//        $form->submit($formData);
//
//        $this->assertTrue($form->isSynchronized());
    }
    protected function setUp()
    {
        parent::setUp();

        $validator = $this->getMockBuilder('Symfony\Component\Validator\ValidatorInterface')
            ->disableOriginalConstructor()->getMock();
        $validator->expects($this->any())->method('validate')->will($this->returnValue(new ConstraintViolationList()));

        $this->factory = Forms::createFormFactoryBuilder()
            ->addExtensions($this->getExtensions())
            ->addTypeExtension(
                new FormTypeValidatorExtension(
                    $validator
                )
            )
            ->addTypeGuesser(
                $this->getMockBuilder(
                    'Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser'
                )
                    ->disableOriginalConstructor()
                    ->getMock()
            )
            ->getFormFactory();

        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
    }
}