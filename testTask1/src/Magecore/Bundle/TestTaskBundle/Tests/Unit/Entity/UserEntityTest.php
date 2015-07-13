<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Entity;

use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\DicPriority;
use Magecore\Bundle\TestTaskBundle\Entity\DicResolution;
use Magecore\Bundle\TestTaskBundle\Entity\DicStatus;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;
use Magecore\Bundle\TestTaskBundle\Tests\SymfonyEntityTest;


class UserEntityTest extends SymfonyEntityTest
{

    public function dataProviderTest()
    {
        $entity = new User();
        return array(
            array(
                'timezone', 'Europe/Paris', $entity,
            ),
            array(
                'full_name', 'MyFullName', $entity,
            ),
            array(
                'avapath','path/getAvapath', $entity,
            ),
            array(
                'id', 5, $entity,
            ),
//            array(
//                'file', 'getFileName', $entity,
//            ),
            array(
                'remove_ava', 'getRemoveAva', $entity,
            ),
        );
    }

    /**
     * @dataProvider dataProviderTest()
     */
    public function testSettersTest($field, $value, $entity)
    {
        $this->GetSetTest($field, $value, $entity);
    }

    /**
     * @param $element
     * @param $entity
     * @param $get_collection_method_name
     * @param $add_remove_collection_element_name
     * @dataProvider dataProviderCollections
     */
    public function testCollections($element, $entity, $get_collection_method_name, $add_remove_collection_element_name)
    {
        $this->CollectionTest($element, $entity, $get_collection_method_name, $add_remove_collection_element_name);

    }

    public function dataProviderCollections()
    {
        $entity = new User();
        return array(
            array(
                new Activity(),$entity,'getActivities','activity'
            ),
            array(
                new Issue(),$entity,'getIssues','issue'
            ),
        );
    }

    public function testSet()
    {
        $entity = new User();

        $file = null;

        $this->setVal['setFile'] = $file;

         //owner
        $this->assertTrue($entity->isOwner($entity));

        $upl_dir = $entity->getUploadDir();
        $this->assertTrue(!empty($upl_dir));
        $upl_web_dir = $entity->getUploadRootDir();
        $this->assertTrue(!empty($upl_web_dir));

        $entity->setAvapath('pum');

        $this->assertEquals($upl_dir.'/pum',$entity->getWebPath() );

        $this->assertEquals($upl_web_dir.'/pum',$entity->getAbsolutePath() );

    }
}
