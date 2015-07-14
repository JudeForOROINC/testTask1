<?php

namespace Magecore\Bundle\TestTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\Collection ;

/**
 * Issue
 *
 * @ORM\Table(name="magecore_testtask_issue")
 * @ORM\Entity(repositoryClass="Magecore\Bundle\TestTaskBundle\Entity\IssueRepository")
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


    //const ISSUE_PARENT_TYPES = [ self::ISSUE_TYPE_STORY, self::ISSUE_TYPE_BUG, self::ISSUE_TYPE_TASK];


    /**
     * @ORM\Column(name="issue_type", type="string", length=30)
     */
    private $type;//Это заведомо не корректная архитектура с т.з. производительности. делаю для удобства дебага - запросы в базу смотреть.




    /**
     * @ORM\ManyToOne(targetEntity="DicPriority")
     * @ORM\JoinColumn(name="priority_id", referencedColumnName="id", nullable=false)
     */
    private $priority;

    /**
     * @ORM\ManyToOne(targetEntity="DicStatus")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id", nullable=false)
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
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="children", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="parent_issue_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parentIssue;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="issues")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $project;

    const ISSUE_TYPE_STORY = 'Story';
    const ISSUE_TYPE_BUG = 'Bug';
    const ISSUE_TYPE_TASK = 'Task';
    const ISSUE_TYPE_SUBTASK = 'Subtask';

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="issue")
     */
    protected $comments;


    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="issues")
     * @ORM\JoinTable(name="magecore_testtask_issue_to_users"),
     */
    protected $collaborators;

    /**
     * @ORM\OneToMany(targetEntity="Activity", mappedBy="issue")
     */
    protected $activities;


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
    protected function isValidType($type){
        return in_array( $type ,[self::ISSUE_TYPE_STORY,self::ISSUE_TYPE_BUG, self::ISSUE_TYPE_SUBTASK, self::ISSUE_TYPE_TASK]);
    }

    /**
     * @param $type
     * @return $this
     */
    public function setType($type)
    {
        if ($this->isValidType($type)) {
            $this->type = $type;
        }

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
     * @return bool
     */
    public function isStory()
    {
        return (bool)($this->getType()==self::ISSUE_TYPE_STORY);
    }

    /**
     * @return bool
     */
    public function isSubtask()
    {
        return (bool)($this->getType()==self::ISSUE_TYPE_SUBTASK);
    }

    /**
     * @return array
     */
    public function getParentTypes()
    {
        return array(
            self::ISSUE_TYPE_BUG,
            self::ISSUE_TYPE_TASK,
            self::ISSUE_TYPE_STORY,
        );
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
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->comments = new \Doctrine\Common\Collections\ArrayCollection();
        $this->collaborators = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activities = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->created = new
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
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

    public function __toString()
    {
        return (string)$this->getCode();
    }

    /**
     * Set project
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Project $project
     * @return Issue
     */
    public function setProject(\Magecore\Bundle\TestTaskBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add comments
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Comment $comments
     * @return Issue
     */
    public function addComment(\Magecore\Bundle\TestTaskBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Comment $comments
     */
    public function removeComment(\Magecore\Bundle\TestTaskBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add collaborators
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\User $collaborators
     * @return Issue
     */
    public function addCollaborator(\Magecore\Bundle\TestTaskBundle\Entity\User $collaborator)
    {
        if (!$this->getCollaborators()->contains($collaborator)) {
            $this->collaborators[] = $collaborator;
            $collaborator->addIssue($this);
        }


        return $this;
    }

    /**
     * Remove collaborators
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\User $collaborators
     */
    public function removeCollaborator(\Magecore\Bundle\TestTaskBundle\Entity\User $collaborators)
    {
        $this->collaborators->removeElement($collaborators);
    }

    /**
     * Get collaborators
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCollaborators()
    {
        return $this->collaborators;
    }

    /**
     * Add activities
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Activity $activities
     * @return Issue
     */
    public function addActivity(\Magecore\Bundle\TestTaskBundle\Entity\Activity $activities)
    {
        $this->activities[] = $activities;

        return $this;
    }

    /**
     * Remove activities
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Activity $activities
     */
    public function removeActivity(\Magecore\Bundle\TestTaskBundle\Entity\Activity $activities)
    {
        $this->activities->removeElement($activities);
    }

    /**
     * Get activities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getActivities()
    {
        return $this->activities;
    }
}
