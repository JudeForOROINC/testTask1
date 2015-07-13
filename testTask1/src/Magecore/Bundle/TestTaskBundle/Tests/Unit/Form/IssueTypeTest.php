<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 13.07.15
 * Time: 10:28
 */

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Form\Type;

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
            'test' => 'test',
            'test2' => 'test2',
        );

        $type = new IssueType();
        $form = $this->factory->create($type);

        $object = TestObject::fromArray($formData);

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