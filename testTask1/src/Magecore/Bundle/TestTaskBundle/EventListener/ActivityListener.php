<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 30.06.15
 * Time: 11:57
 */
namespace Magecore\Bundle\TestTaskBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;

class ActivityListener{

    protected $notifer=array();

    /**
     * @param LifecycleEventArgs $args
     * we add activity when new issue was created;
     */
    public function postPersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // new entity inserted in database. may be a Issue.
        if ($entity instanceof Issue) {

            $active = new Activity();
            $active->setType($active::ACTIVITY_TYPE_CREATE_ISSUE);
            $active->setIssue($entity);
            $active->setUser($entity->getReporter());//Here is dangerous: Reporter is not User - if we change logic. but until we controlling code we hope it is.
            $entityManager->persist( $active);
            $entityManager->flush();
        }

        // new entity inserted in database. may be a Comment.
        if ($entity instanceof Comment) {
            $active = new Activity();
            $active->setType($active::ACTIVITY_TYPE_COMMENT_IN_ISSUE);
            $active->setIssue($entity->getIssue());
            $active->setUser($entity->getAuthor());//Here is dangerous: Reporter is not User - if we change logic. but until we controlling code we hope it is.
            $active->setComment($entity);
            $entityManager->persist( $active);
            $entityManager->flush();

        }
    }

    public function preUpdate(PreUpdateEventArgs $args){
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $ch = $args->getEntityChangeSet();

        // updates some data in Issue entity
        if ($entity instanceof Issue) {
            // if status was changed log event;
            if ($args->hasChangedField('status')) {
                $active = new Activity();
                $active->setType($active::ACTIVITY_TYPE_CHANGE_STATUS_ISSUE);
                $active->setIssue($entity);
                $active->setUser($entity->getReporter());//Here is dangerous: Reporter is not User - if we change logic. but until we controlling code we hope it is.
                $active->setFromIssueStatus($args->getOldValue('status'));
                $active->setToIssueStatus($args->getNewValue('status'));
                $this->notifer[]=$active;
            }
        }
    }

    public function postUpdate(LifecycleEventArgs $args){//TODO use postflush
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // if event was enabled - try to insert log record;
        if ($entity instanceof Issue) {
            // event list is not empty
            if ( count ($this->notifer) ) {
                foreach($this->notifer as $line){
                    $entityManager->persist($line);
                }
                $this->notifer = array();
                $entityManager->flush();
            }
        }
    }


}