<?php

namespace Magecore\Bundle\TestTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;

/**
 * @ORM\Entity
 * @ORM\Table(name="magecore_testtask_user")
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
     * @ORM\Column(type="string", nullable=true)
     *
     */
    protected $avapath;

    /**
     *
     * @ORM\Column(type="string", nullable=true)
     *
     */
    protected $full_name;

    /**
     *
     * @ORM\Column(type="string" )
     *
     */
    protected $timezone = 'Europe/Kiev';



    public function isOwner(User $user){
        return (bool)$this->getId() ==$user->getId();
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
}
