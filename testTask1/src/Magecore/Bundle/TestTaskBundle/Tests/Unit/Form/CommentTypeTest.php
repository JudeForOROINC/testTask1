<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 13.07.15
 * Time: 10:28
 */

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Form\Type;

use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Form\CommentType;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Symfony\Component\Form\Test\TypeTestCase;

class CommentTypeTest extends TypeTestCase
{

    public function testGetName()
    {
        $type = new CommentType();
        $this->assertEquals('magecore_bundle_testtaskbundle_comment', $type->getName());
    }

    public function testSubmitValidData()
    {
        $formData = array(
            'body' => 'test',
        );

        $type = new CommentType();
        $form = $this->factory->create($type);

        $object = new Comment();
        $object->setBody('test');

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