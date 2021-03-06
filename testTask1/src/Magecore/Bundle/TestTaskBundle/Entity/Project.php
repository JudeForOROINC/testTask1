<?php

namespace Magecore\Bundle\TestTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="Magecore\Bundle\TestTaskBundle\Entity\ProjectRepository")
 * @ORM\Table(name="magecore_testtask_project")
 * @UniqueEntity("code")
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $label;

    /**
     * @ORM\Column(type="text")
     */
    protected $summary;


    /**
     * @ORM\Column(type="string", length=3, unique=true)
     */
    protected $code;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     * @ORM\JoinTable(name="magecore_testtask_products_to_users",
     *     joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    protected $members;

    /**
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="project")
     */
    protected $issues;


    public function __construct(){
        $this->members = new ArrayCollection();
        $this->issues = new ArrayCollection();
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
     * Get label
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param string $label
     * @return Project
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Project
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string 
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * Set code
     *
     * @param integer $code
     * @return Project
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return integer 
     */
    public function getCode()
    {
        return $this->code;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function setMembers(Collection $members)
    {
        $this->members= $members;
    }

    public function addMember(User $user){
        if (!$this->getMembers()->contains($user)){
            $this->getMembers()->add($user);
            //???
        }
        return $this;
    }
    public function removeMember(User $user){
        if ($this->getMembers()->contains($user)){
            $this->getMembers()->removeElement($user);
            //???
        }
        return $this;
    }

    public function isMember(User $user){
        return (bool)$this->getMembers()->contains($user);
    }


    /**
     * Add issue
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Issue $issue
     * @return Project
     */
    public function addIssue(\Magecore\Bundle\TestTaskBundle\Entity\Issue $issue)
    {
        $this->issues[] = $issue;

        return $this;
    }

    /**
     * Remove issue
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Issue $issue
     */
    public function removeIssue(\Magecore\Bundle\TestTaskBundle\Entity\Issue $issue)
    {
        $this->issues->removeElement($issue);
    }

    /**
     * Get issue
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIssues()
    {
        return $this->issues;
    }
    public function __toString(){
        return (string)$this->getCode();
    }

    
}
