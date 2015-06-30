<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 30.06.15
 * Time: 11:57
 */
namespace Magecore\Bundle\TestTaskBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;

class ActivityListener{
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

}