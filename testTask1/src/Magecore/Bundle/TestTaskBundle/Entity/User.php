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

    protected $role;

    const OPERATOR='ROLE_OPERATOR';
    const MANAGER='ROLE_MANAGER';
    const ADMINISTRATOR='ROLE_ADMIN';


    /**
     * @param $role
     */
    public function setRole($role)
    {
        $this->addRole($role);
    }

    /**
     * @return null|string
     */
    public function getRole()
    {
        //this done for protection logic of fos user bundle/
        $roles = array_intersect(
            $this->getRoles(),
            [self::OPERATOR,self::ADMINISTRATOR,self::MANAGER,]
        );
        if (empty($roles)) {
            return null;
        }
        $this->role= $roles[0];
        return $this->role;
    }

    /**
     * @return mixed
     */
    public function getRemoveAva()
    {
        return $this->remove_ava;
    }

    /**
     * @param $remove_ava
     */
    public function setRemoveAva($remove_ava)
    {
        $this->remove_ava = $remove_ava;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * @return UploadedFile|null
     */
    public function getFile()
    {
        return $this->file;
    }

    public function isOwner(User $user){
        return (bool)($this->getId() == $user->getId());
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
    public function setTimezone($timezone)
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

    public function addRole($role)
    {
        parent::addRole($role);
        // by the task we may set only one role.
        //so check is role in list of roles that must be only one/
        if (!in_array(
            $this->role,
            array(self::ADMINISTRATOR, self::MANAGER, self::OPERATOR)
        )) {
            return $this;
        };
        $this->removeRole($this::OPERATOR);
        $this->removeRole($this::ADMINISTRATOR);
        $this->removeRole($this::MANAGER);
        $this->addRole($role);
        return $this;
    }

}
