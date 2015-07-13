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
use Symfony\Component\Form\Test\TypeTestCase;

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
            'full_name' => 'test',
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
        $this->assertEquals($object, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testSubmitAdmin()
    {
        $formData = array(
            'full_name' => 'test',
            'timezone' => 'Europe/Paris',
        );

        $type = new UserType();
        $user = new User();

        $form = $this->factory->create($type, $user,array('is_admin'=>true));

        //$object = TestObject::fromArray($formData);
        $object = new User();
        $object->setFullName('test');
        $object->setTimezone('Europe/Paris');


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
}