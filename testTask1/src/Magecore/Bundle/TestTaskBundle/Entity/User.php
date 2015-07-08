<?php

namespace Magecore\Bundle\TestTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="magecore_testtask_user")
 * @ORM\Entity()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    protected $avapath;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     *
     *
     */
    protected $full_name;

    /**
     *
     * @ORM\Column(type="string" )
     *
     */
    protected $timezone = 'Europe/Kiev';

    /**
     * @Assert\File(maxSize="6000000")
     * @Assert\Image(
     *     minWidth = 72,
     *     maxWidth = 400,
     *     minHeight = 72,
     *     maxHeight = 400
     * )
     */
    protected $file;

    protected $remove_ava;


    /**
     * @ORM\ManyToMany(targetEntity="Issue", mappedBy="collaborators")
     */

    protected $issues;

    /**
    * @ORM\OneToMany(targetEntity="Activity", mappedBy="user" )
    */
    protected $activity;

    public function getRemoveAva()
    {
        return $this->remove_ava;
    }
    public function setRemoveAva($remove_ava)
    {
        $this->remove_ava = $remove_ava;
    }

    public function setFile(UploadedFile $file = null){
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function isOwner(User $user){
        return (bool)($this->getId() == $user->getId());
    }

    public function getAbsolutePath()
    {
        return null === $this->avapath
            ? null
            : $this->getUploadRootDir().DIRECTORY_SEPARATOR.$this->avapath;
    }

    public function getWebPath()
    {
        return null === $this->avapath
            ? null
            : $this->getUploadDir().DIRECTORY_SEPARATOR.$this->avapath;
    }

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

    public  function upload(){
        if($this->getRemoveAva()){
            if(!empty($this->avapath)){
                //kick old ave:
                if (\file_exists($this->getAbsolutePath())){
                    unlink($this->getAbsolutePath());
                }
                $this->avapath = null;
            }
        }

        if (null === $this->getFile()) {
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

        $extension = $this->getFile()->guessExtension();
        if (!$extension) {
            // extension cannot be guessed
            $extension = 'bin';
        }

        //kick old ave:
        if (\file_exists($this->getAbsolutePath())){
            unlink($this->getAbsolutePath());
        }

        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->getId().'.'.$extension
        );
        // set the path property to the filename where you've saved the file
        //$this->avapath = $this->getFile()->getClientOriginalName();
        $this->avapath = $this->getId().'.'.$extension;

        // clean up the file property as you won't need it anymore
        $this->file = null;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set avapath
     *
     * @param string $avapath
     * @return User
     */
    public function setAvapath($avapath)
    {
        $this->avapath = $avapath;

        return $this;
    }

    /**
     * Get avapath
     *
     * @return string 
     */
    public function getAvapath()
    {
        return $this->avapath;
    }

    /**
     * Set full_name
     *
     * @param string $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->full_name = $fullName;

        return $this;
    }

    /**
     * Get full_name
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set timezone
     *
     * @param string $timezone
     * @return User
     */
    public function setTimezone( $timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * Get timezone
     *
     * @return string 
     */
    public function getTimezone()
    {
        return $this->timezone;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->issues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activity = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add issues
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Issue $issues
     * @return User
     */
    public function addIssue(\Magecore\Bundle\TestTaskBundle\Entity\Issue $issues)
    {
        if (!$this->getIssues()->contains($issues)) {
            $this->issues[] = $issues;
            $issues->addCollaborator($this);
        }

        return $this;
    }

    /**
     * Remove issues
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Issue $issues
     */
    public function removeIssue(\Magecore\Bundle\TestTaskBundle\Entity\Issue $issues)
    {
        $this->issues->removeElement($issues);
    }

    /**
     * Get issues
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIssues()
    {
        return $this->issues;
    }


    /**
     * Add activities
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Activity $activities
     * @return Issue
     */
    public function addActivity(\Magecore\Bundle\TestTaskBundle\Entity\Activity $activity)
    {
        $this->activity[] = $activity;

        return $this;
    }

    /**
     * Remove activities
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Activity $activities
     */
    public function removeActivity(\Magecore\Bundle\TestTaskBundle\Entity\Activity $activities)
    {
        $this->activity->removeElement($activities);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivity()
    {
        return $this->activity;
    }
}
