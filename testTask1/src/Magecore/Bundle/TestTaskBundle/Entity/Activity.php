<?php

namespace Magecore\Bundle\TestTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table(name="magecore_testtask_activity")
 * @ORM\Entity
 */
class Activity
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
     * @ORM\ManyToOne(targetEntity="User", )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="DicType", )
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Issue", )
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id")
     */
    private $issue;

    /**
     * @ORM\ManyToOne(targetEntity="DicStatus", )
     * @ORM\JoinColumn(name="from_issue_ststus_id", referencedColumnName="id")
     */
    private $fromIssueStstus;

    /**
     * @ORM\ManyToOne(targetEntity="DicStatus", )
     * @ORM\JoinColumn(name="to_issue_ststus_id", referencedColumnName="id")
     */
    private $toIssueStatus;

    /**
     * @ORM\ManyToOne(targetEntity="Comment", )
     * @ORM\JoinColumn(name="comment_id", referencedColumnName="id")
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;


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
     * Set userId
     *
     * @param integer $userId
     * @return Activity
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set typeId
     *
     * @param integer $typeId
     * @return Activity
     */
    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;

        return $this;
    }

    /**
     * Get typeId
     *
     * @return integer 
     */
    public function getTypeId()
    {
        return $this->typeId;
    }

    /**
     * Set issueId
     *
     * @param integer $issueId
     * @return Activity
     */
    public function setIssueId($issueId)
    {
        $this->issueId = $issueId;

        return $this;
    }

    /**
     * Get issueId
     *
     * @return integer 
     */
    public function getIssueId()
    {
        return $this->issueId;
    }

    /**
     * Set fromIssueStstusId
     *
     * @param integer $fromIssueStstusId
     * @return Activity
     */
    public function setFromIssueStstusId($fromIssueStstusId)
    {
        $this->fromIssueStstusId = $fromIssueStstusId;

        return $this;
    }

    /**
     * Get fromIssueStstusId
     *
     * @return integer 
     */
    public function getFromIssueStstusId()
    {
        return $this->fromIssueStstusId;
    }

    /**
     * Set toIssueStatusId
     *
     * @param integer $toIssueStatusId
     * @return Activity
     */
    public function setToIssueStatusId($toIssueStatusId)
    {
        $this->toIssueStatusId = $toIssueStatusId;

        return $this;
    }

    /**
     * Get toIssueStatusId
     *
     * @return integer 
     */
    public function getToIssueStatusId()
    {
        return $this->toIssueStatusId;
    }

    /**
     * Set commentId
     *
     * @param integer $commentId
     * @return Activity
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;

        return $this;
    }

    /**
     * Get commentId
     *
     * @return integer 
     */
    public function getCommentId()
    {
        return $this->commentId;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Activity
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set user
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\User $user
     * @return Activity
     */
    public function setUser(\Magecore\Bundle\TestTaskBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set type
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\DicType $type
     * @return Activity
     */
    public function setType(\Magecore\Bundle\TestTaskBundle\Entity\DicType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\DicType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set issue
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Issue $issue
     * @return Activity
     */
    public function setIssue(\Magecore\Bundle\TestTaskBundle\Entity\Issue $issue = null)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\Issue 
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Set fromIssueStstus
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\DicStatus $fromIssueStstus
     * @return Activity
     */
    public function setFromIssueStstus(\Magecore\Bundle\TestTaskBundle\Entity\DicStatus $fromIssueStstus = null)
    {
        $this->fromIssueStstus = $fromIssueStstus;

        return $this;
    }

    /**
     * Get fromIssueStstus
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\DicStatus 
     */
    public function getFromIssueStstus()
    {
        return $this->fromIssueStstus;
    }

    /**
     * Set toIssueStatus
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\DicStatus $toIssueStatus
     * @return Activity
     */
    public function setToIssueStatus(\Magecore\Bundle\TestTaskBundle\Entity\DicStatus $toIssueStatus = null)
    {
        $this->toIssueStatus = $toIssueStatus;

        return $this;
    }

    /**
     * Get toIssueStatus
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\DicStatus 
     */
    public function getToIssueStatus()
    {
        return $this->toIssueStatus;
    }

    /**
     * Set comment
     *
     * @param \Magecore\Bundle\TestTaskBundle\Entity\Comment $comment
     * @return Activity
     */
    public function setComment(\Magecore\Bundle\TestTaskBundle\Entity\Comment $comment = null)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\Comment 
     */
    public function getComment()
    {
        return $this->comment;
    }
}
