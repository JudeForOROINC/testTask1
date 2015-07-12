<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Entity;

use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\DicPriority;
use Magecore\Bundle\TestTaskBundle\Entity\DicResolution;
use Magecore\Bundle\TestTaskBundle\Entity\DicStatus;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\Tests\SymfonyEntityTest;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;


class CommentEntityTest extends SymfonyEntityTest
{

    public function dataProviderTest(){
        $entity = new Comment();
        return array(
            array(
                'body', 'Mama Mila Ramu', $entity,
            ),
            array(
                'issue',new Issue(), $entity,
            ),
            array(
                'author', new User(), $entity,
            ),
            array(
                'id', 5, $entity,
            ),
            array(
                'created', new \DateTime(), $entity,
            ),
            array(
                'updated', new \DateTime(), $entity,
            ),
        );
    }

    /**
     * @dataProvider dataProviderTest()
     */
    public function testSettersTest($field,$value, $entity){
        $this->GetSetTest($field,$value,$entity);
    }

    public function testTostring()
    {
        $entity = new Comment();

        //tostring
        $this->assertEquals((string)$entity->getId(), (string)$entity);

    }

    public function testIsAuthor()
    {
        $entity = new Comment();
        $author = new User();

        $entity->setAuthor($author);

        //owner
        $this->assertTrue($entity->isOwner($author));

        // null author
        $entity->setAuthor(null);
        $this->assertTrue(!$entity->isOwner($author));

        //not owner test
        //
        $user = new User();

        $entity->setAuthor(null);
        $this->assertTrue(!$entity->isOwner($user));

    }

}