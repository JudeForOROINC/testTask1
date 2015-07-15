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
        $childType = new EntityType();
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
            'members' => null,
        );

        $type = new ProjectType();
        $form = $this->factory->create($type);

        $object = new Project();
        $object->setLabel('test');
        $object->setCode('rrr');
        $object->setSummary('summary');


        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

//    protected function setUp()
//    {
//        parent::setUp();
//
//        $validator = $this->getMock('\Symfony\Component\Validator\ValidatorInterface');
//        $validator->expects($this->any())->method('validate')->will($this->returnValue(new ConstraintViolationList()));
//
//        $mockEntityType = $this->getMockBuilder('Magecore\Bundle\TestTaskBundle\Tests\Unit\EntityType')
//            //->setConstructorArgs(array($mockEntityManager))
//            //->disableOriginalConstructor()
//            ->getMock();
//
//        $mockEntityType->expects($this->any())->method('getName')
//            ->will($this->returnValue('entity'));
//
//        $mockEntityManager = $this->getMockBuilder('\Doctrine\Common\Persistence\ManagerRegistry')
//            ->disableOriginalConstructor()
//            ->getMock();
//
//        $this->factory = Forms::createFormFactoryBuilder()
//            ->addExtensions($this->getExtensions())
//            ->addTypeExtension(
//                new FormTypeValidatorExtension(
//                    $validator
//                )
//            )
//            ->addTypeGuesser(
//                $this->getMockBuilder(
//                    'Symfony\Component\Form\Extension\Validator\ValidatorTypeGuesser'
//                )
//                    ->disableOriginalConstructor()
//                    ->getMock()
//            )
//            ->addExtension(
//                new PreloadedExtension(
//                    array('entity'=>$mockEntityType),
//                    array()
//                )
//            )
//            ->getFormFactory();
//
//        $this->dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
//        $this->builder = new FormBuilder(null, null, $this->dispatcher, $this->factory);
//    }

}