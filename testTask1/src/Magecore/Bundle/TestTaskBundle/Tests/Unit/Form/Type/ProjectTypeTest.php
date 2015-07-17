<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 13.07.15
 * Time: 10:28
 */

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Form\Type;

use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Form\Type\ProjectType;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
//use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Magecore\Bundle\TestTaskBundle\Tests\Unit\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ValidatorInterface;

class ProjectTypeTest extends TypeTestCase
{

    public function testGetName()
    {
        $type = new ProjectType();
        $this->assertEquals('project', $type->getName());
    }

    protected function getExtensions()
    {
        $childType = new EntityType(array(
            0=>'user',
            1=>'oper',
            2=>'Admin'
        ));
        return array(new PreloadedExtension(array(
            $childType->getName() => $childType,
        ), array()));
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'label' => 'test',
            'code' => 'rrr',
            'summary' => 'test2',
            'members' => 2,
        );

        $type = new ProjectType();
        $form = $this->factory->create($type);

        $object = new Project();
        $object->setLabel('test');
        $object->setCode('rrr');
        $object->setSummary('test2');
       // $object->setMembers()


        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $data = $form->getData();

        $this->assertEquals($object->getLabel(), $data['label']);
        $this->assertEquals($object->getCode(), $data['code']);
        $this->assertEquals($object->getSummary(), $data['summary']);


        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
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