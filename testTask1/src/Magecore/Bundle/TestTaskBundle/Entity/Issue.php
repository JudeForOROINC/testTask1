<?php

namespace Magecore\Bundle\TestTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Issue
 *
 * @ORM\Table(name="magecore_testtask_issue")
 * @ORM\Entity
 */
class Issue
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="reporter_id", referencedColumnName="id")
     */
    private $reporter;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="assignee_id", referencedColumnName="id")
     */
    private $assignee;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=15)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="string", length=255)
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="DicType")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="DicPriority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id")
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="DicStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="DicResolution")
     * @ORM\JoinColumn(name="resolution_id", referencedColumnName="id")
     */
    private $resolution;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @ORM\OneToMany(targetEntity="Issue", mappedBy="parentIssue")
     */
    protected $children;


    /**
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children")
     * @ORM\JoinColumn(name="parent_issue_id", referencedColumnName="id")
     */
    private $parentIssue;


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
     * Set reporterId
     *
     * @param integer $reporterId
     * @return Issue
     */
    public function setReporterId($reporterId)
    {
        $this->reporterId = $reporterId;

        return $this;
    }

    /**
     * Get reporterId
     *
     * @return integer 
     */
    public function getReporterId()
    {
        return $this->reporterId;
    }

    /**
     * Set assigneeId
     *
     * @param integer $assigneeId
     * @return Issue
     */
    public function setAssigneeId($assigneeId)
    {
        $this->assigneeId = $assigneeId;

        return $this;
    }

    /**
     * Get assigneeId
     *
     * @return integer 
     */
    public function getAssigneeId()
    {
        return $this->assigneeId;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Issue
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set summary
     *
     * @param string $summary
     * @return Issue
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
     * Set description
     *
     * @param string $description
     * @return Issue
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Issue
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return Issue
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Issue
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set resolutionId
     *
     * @param integer $resolutionId
     * @return Issue
     */
    public function setResolutionId($resolutionId)
    {
        $this->resolutionId = $resolutionId;

        return $this;
    }

    /**
     * Get resolutionId
     *
     * @return integer 
     */
    public function getResolutionId()
    {
        return $this->resolutionId;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Issue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Issue
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set parentIssueId
     *
     * @param integer $parentIssueId
     * @return Issue
     */
    public function setParentIssueId($parentIssueId)
    {
        $this->parentIssueId = $parentIssueId;

        return $this;
    }

    /**
     * Get parentIssueId
     *
     * @return integer 
     */
    public function getParentIssueId()
    {
        return $this->parentIssueId;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->created = new
    }

    /**
     * Set reporter
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\User $reporter
     * @return Issue
     */
    public function setReporter(\Magecore\Bundle\TestTaskBundle\Entity\User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\User 
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set assignee
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\User $assignee
     * @return Issue
     */
    public function setAssignee(\Magecore\Bundle\TestTaskBundle\Entity\User $assignee = null)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Get assignee
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\User 
     */
    public function getAssignee()
    {
        return $this->assignee;
    }

    /**
     * Set resolution
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\DicResolution $resolution
     * @return Issue
     */
    public function setResolution(\Magecore\Bundle\TestTaskBundle\Entity\DicResolution $resolution = null)
    {
        $this->resolution = $resolution;

        return $this;
    }

    /**
     * Get resolution
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\DicResolution 
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Add children
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Issue $children
     * @return Issue
     */
    public function addChild(\Magecore\Bundle\TestTaskBundle\Entity\Issue $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Issue $children
     */
    public function removeChild(\Magecore\Bundle\TestTaskBundle\Entity\Issue $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parentIssue
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Issue $parentIssue
     * @return Issue
     */
    public function setParentIssue(\Magecore\Bundle\TestTaskBundle\Entity\Issue $parentIssue = null)
    {
        $this->parentIssue = $parentIssue;

        return $this;
    }

    /**
     * Get parentIssue
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\Issue 
     */
    public function getParentIssue()
    {
        return $this->parentIssue;
    }

    public function __toString(){
        return (string)$this->getCode();
    }
}
