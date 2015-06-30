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

class ActivityListener{

    protected $notifer=array();

    /**
     * @param LifecycleEventArgs $args
     * we add activity when new issue was created;
     */
    public function postPersist(LifecycleEventArgs $args){
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof Issue) {
            // ... do something with the Product
            $active = new Activity();
            $active->setType($active::ACTIVITY_TYPE_CREATE_ISSUE);
            $active->setIssue($entity);
            $active->setUser($entity->getReporter());//Here is dangerous: Reporter is not User - if we change logic. but until we controlling code we hope it is.
            $entityManager->persist( $active);
            $entityManager->flush();
        }
    }

    public function preUpdate(PreUpdateEventArgs $args){
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();
        $ch = $args->getEntityChangeSet();

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof Issue) {
            // ... do something with the Product
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

        // perhaps you only want to act on some "Product" entity
        if ($entity instanceof Issue) {
            // ... do something with the Product
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