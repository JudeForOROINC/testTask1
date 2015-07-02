<?php

namespace Magecore\Bundle\TestTaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Magecore\Bundle\TestTaskBundle\Helper\DoctrineHelper;

/**
 * Activity
 *
 * @ORM\Table(name="magecore_testtask_activity")
 * @ORM\Entity(repositoryClass="Magecore\Bundle\TestTaskBundle\Entity\ActivityRepository")
 */
class Activity
{
    private $helper=null;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="activity")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(name="activity_type", type="string", length = 8, nullable=false)
     */
    private $type;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="activities")
     * @ORM\JoinColumn(name="issue_id", referencedColumnName="id")
     */
    private $issue;

    /**
     * @ORM\ManyToOne(targetEntity="DicStatus", )
     * @ORM\JoinColumn(name="from_issue_ststus_id", referencedColumnName="id")
     */
    private $fromIssueStatus;

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

    const ACTIVITY_TYPE_CREATE_ISSUE='CIAT';
    const ACTIVITY_TYPE_CHANGE_STATUS_ISSUE='CSAT';
    const ACTIVITY_TYPE_COMMENT_IN_ISSUE='COAT';

    public function __construct(){
        $this->helper = new DoctrineHelper();
        $this->time = $this->helper->setDatetime(new \DateTime());
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
     * Set time
     *
     * @param \DateTime $time
     * @return Activity
     */
    public function setTime($time)
    {
        $this->time = $this->helper->setDatetime($time);

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->helper->setDatetime($this->time);
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
     * @param string $type
     * @return Activity
     */
    public function setType( $type = null)
    {
        if (in_array($type,[self::ACTIVITY_TYPE_CHANGE_STATUS_ISSUE,self::ACTIVITY_TYPE_COMMENT_IN_ISSUE,self::ACTIVITY_TYPE_CREATE_ISSUE] )) {
            $this->type = $type;
        }

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
    public function setFromIssueStatus(\Magecore\Bundle\TestTaskBundle\Entity\DicStatus $fromIssueStatus = null)
    {
        $this->fromIssueStatus = $fromIssueStatus;

        return $this;
    }

    /**
     * Get fromIssueStstus
     *
     * @return \Magecore\Bundle\TestTaskBundle\Entity\DicStatus 
     */
    public function getFromIssueStatus()
    {
        return $this->fromIssueStatus;
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

    public function isNewIssueType(){
        return $this->getType() == self::ACTIVITY_TYPE_CREATE_ISSUE;
    }
    public function isChangeStatusType(){
        return $this->getType() == self::ACTIVITY_TYPE_CHANGE_STATUS_ISSUE;
    }
    public function isCommentType(){
        return $this->getType() == self::ACTIVITY_TYPE_COMMENT_IN_ISSUE;
    }
}
