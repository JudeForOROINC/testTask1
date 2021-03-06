<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 30.06.15
 * Time: 11:57
 */
namespace Magecore\Bundle\TestTaskBundle\EventListener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Magecore\Bundle\TestTaskBundle\Controller\MaillerController;
use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use DateTime;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ActivityListener
{

    protected $notifer=array();

    protected $container=null;

    //flag. Remove if ve load fixtures;
    protected $maySandEmail = true;

    /**
     * @param Activity $activity
     */
    protected function pushMail(Activity $activity)
    {
        $mailer = $this->container->get('my.mailer');
        $mailer->pushMail($activity);
    }

    /**
     * @return bool
     */
    public function getMaySendEmail()
    {
        return $this->maySandEmail;
    }

    /**
     * @param $flag
     */
    public function setMaySendEmail($flag)
    {
        $this->maySandEmail= (bool)$flag;
    }

    /**
     * @param LifecycleEventArgs $args
     * we add activity when new issue was created;
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // new entity inserted in database. may be a Issue.
        if ($entity instanceof Issue) {
            $entity->setCode($entity->getProject()->getCode().'-'.$entity->getId());
            $active = new Activity();
            $active->setType($active::ACTIVITY_TYPE_CREATE_ISSUE);
            $active->setIssue($entity);
            $active->setUser($entity->getReporter());
            //Here is dangerous: Reporter is not User - if we change logic. but until we controlling code we hope it is.
            $entityManager->persist($active);
            $entityManager->flush();

        }

        // new entity inserted in database. may be a Comment.
        if ($entity instanceof Comment) {
            $active = new Activity();
            $active->setType($active::ACTIVITY_TYPE_COMMENT_IN_ISSUE);
            $active->setIssue($entity->getIssue());
            $active->setUser($entity->getAuthor());
            //Here is dangerous: Author is not User - if we change logic. but until we controlling code we hope it is.
            $active->setComment($entity);
            $entityManager->persist($active);
            $entityManager->flush();
        }
        if ($entity instanceof Activity) {
            if ($this->maySandEmail) {
                $this->pushMail($entity);
            }
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
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

                $currentUser = $this->container->get('security.context')->getToken()->getUser();

                $active->setUser($currentUser);
                $active->setFromIssueStatus($args->getOldValue('status'));
                $active->setToIssueStatus($args->getNewValue('status'));
                $this->notifer[]=$active;
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        //TODO use postflush
        $entity = $args->getEntity();
        $entityManager = $args->getEntityManager();

        // if event was enabled - try to insert log record;
        if ($entity instanceof Issue) {
            // event list is not empty
            if (count($this->notifer)) {
                $arr = $this->notifer;
                $this->notifer = array();
                foreach ($arr as $line) {
                    $entityManager->persist($line);
                }
                $entityManager->flush();
            }
        }
    }


    /**
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $zone = new \DateTimeZone('UTC');
        //Get hold of the unit of work.
        $entityManager = $args->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();
        //Get hold of the entities that are scheduled for insertion or update
        $entities = array_merge($unitOfWork->getScheduledEntityInsertions(), $unitOfWork->getScheduledEntityUpdates());
        foreach ($entities as $entity) {
            $reflect = new \ReflectionObject($entity);
            foreach ($reflect->getProperties(\ReflectionProperty::IS_PRIVATE |
                \ReflectionProperty::IS_PROTECTED) as $prop) {
                $prop->setAccessible(true);
                $value = $prop->getValue($entity);
                if (! $value instanceof \DateTime || $value->getTimezone()->getName() === 'UTC') {
                    $prop->setAccessible(false);
                    continue;
                }
                $value->setTimezone($zone);
                $prop->setValue($entity, $value);
                $prop->setAccessible(false);
                $unitOfWork->recomputeSingleEntityChangeSet(
                    $entityManager->getClassMetadata(get_class($entity)),
                    $entity
                );
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $Basezone = new \DateTimeZone('UTC');
        $Zone = new \DateTimeZone(date_default_timezone_get());
        //Get hold of the unit of work.
//        $entityManager = $args->getEntityManager();
//        $unitOfWork = $entityManager->getUnitOfWork();
        $entity = $args->getEntity();
        if ($entity instanceof User) {
            return;
        }
        //Get hold of the entities that are scheduled for insertion or update
        $reflect = new \ReflectionObject($entity);
        foreach ($reflect->getProperties(\ReflectionProperty::IS_PRIVATE |
            \ReflectionProperty::IS_PROTECTED) as $prop) {
            $prop->setAccessible(true);
            $value = $prop->getValue($entity);
            if (! $value instanceof \DateTime || $value->getTimezone()->getName() === 'UTC') {
                $prop->setAccessible(false);
                continue;
            }
            //we have wrong time. we must have time in local.
            $time_from_db = $value->format('Y-m-d H:i:s');
            $realTime = new \DateTime($time_from_db, $Basezone);
            $prop->setValue($entity, $realTime);
            $prop->setAccessible(false);
        }

    }

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}