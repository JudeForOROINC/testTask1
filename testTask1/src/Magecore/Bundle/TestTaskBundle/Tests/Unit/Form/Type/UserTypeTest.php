<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 13.07.15
 * Time: 10:28
 */

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Form\Type;

use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\Form\Type\UserType;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\ConstraintViolationList;

class UserTypeTest extends TypeTestCase
{

    public function testGetName()
    {
        $type = new UserType();
        $this->assertEquals('user', $type->getName());
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'fullname' => 'test',
            'timezone' => 'Europe/Paris',
        );

        $type = new UserType();
        $form = $this->factory->create($type);

        //$object = TestObject::fromArray($formData);
        $object = new User();
        $object->setFullName('test');
        $object->setTimezone('Europe/Paris');


        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
//        $this->assertEquals($object, $form->getData());
        $data = $form->getData();
        $this->assertEquals($object->getFullName(),$data->getFullName());
        $this->assertEquals($object->getTimezone(),$data->getTimezone());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testSubmitAdmin()
    {
        $formData = array(
            'fullname' => 'test',
            'timezone' => 'Europe/Paris',
        );

        $type = new UserType();
        $user = new User();

        $form = $this->factory->create($type, $user,array('is_admin'=>true));

        //$object = TestObject::fromArray($formData);
        $object = new User();
        $object->setFullName('test');
        $object->setTimezone('Europe/Paris');

       // $object->getSalt()


        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        //$this->assertEquals($object, $form->getData());
        $data = $form->getData();
        $this->assertEquals($object->getFullName(),$data->getFullName());
        $this->assertEquals($object->getTimezone(),$data->getTimezone());
        //$this->assertEquals($object->getFullName(),$data['full_name']);

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