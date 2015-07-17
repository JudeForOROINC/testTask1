<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 17.07.15
 * Time: 13:40
 */

namespace Magecore\Bundle\TestTaskBundle\Helper;

use Magecore\Bundle\TestTaskBundle\Entity\User;

class FileManagerHelper
{
    //TODO manage all file information;
    public function getUploadRootDir()
    {
        //upload abs. dir;
        return dirname(dirname(dirname(dirname(dirname(__DIR__))))).DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.$this->getUploadDir();
    }

    public function getUploadDir()
    {
        //upload abs. dir;
        return 'uploads'.DIRECTORY_SEPARATOR.'documents';
    }

    public function getAbsolutePath($avapath)
    {
        return null === $avapath
            ? null
            : $this->getUploadRootDir().DIRECTORY_SEPARATOR.$avapath;
    }

    public function getWebPath($avapath)
    {
        return null === $avapath
            ? null
            : $this->getUploadDir().DIRECTORY_SEPARATOR.$avapath;
    }


    public function upload(User $entity)
    {
        if ($entity->getRemoveAva()) {
            if (!empty($entity->getAvapath($entity->getAvapath()))) {
                //kick old ave:
                if (\file_exists($this->getAbsolutePath($entity->getAvapath()))) {
                    unlink($this->getAbsolutePath($entity->getAvapath()));
                }
                $this->avapath = null;
            }
        }

        if (null === $entity->getFile()) {
            return;
        }

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to


        /*
                $this->getFile()->move(
                    $this->getUploadRootDir(),
                    $this->getFile()->getClientOriginalName()
                );*/

        $extension = $entity->getFile()->guessExtension();
        if (!$extension) {
            // extension cannot be guessed
            $extension = 'bin';
        }

        //kick old ave:
        if (\file_exists($this->getAbsolutePath($entity->getAvapath()))) {
            unlink($this->getAbsolutePath($entity->getAvapath()));
        }

        $entity->getFile()->move(
            $this->getUploadRootDir(),
            $entity->getId().'.'.$extension
        );
        // set the path property to the filename where you've saved the file
        //$this->avapath = $this->getFile()->getClientOriginalName();
        $entity->setAvapath($entity->getId().'.'.$extension);

        // clean up the file property as you won't need it anymore
        $entity->setFile(null);
    }

}