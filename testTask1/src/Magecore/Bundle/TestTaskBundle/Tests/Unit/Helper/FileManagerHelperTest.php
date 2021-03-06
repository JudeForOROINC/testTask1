<?php

namespace Magecore\Bundle\TestTaskBundle\Tests\Unit\Helper;

use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\DicPriority;
use Magecore\Bundle\TestTaskBundle\Entity\DicResolution;
use Magecore\Bundle\TestTaskBundle\Entity\DicStatus;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\Helper\FileManagerHelper;
use Magecore\Bundle\TestTaskBundle\Helper\RouterHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\DataFixtures\ORM as ORM;


class FileManagerHelperTest extends \PHPUnit_Framework_TestCase
{

    public function testSet()
    {
        $helper = new FileManagerHelper();
        $dir = $helper->getUploadDir();
        $this->assertEquals('uploads'.DIRECTORY_SEPARATOR.'documents', $dir);
    }
    public function testSetFile()
    {
        $helper = new FileManagerHelper();
        $dir = $helper->getUploadRootDir();
        $this->assertTrue(!empty($dir));
    }

}
