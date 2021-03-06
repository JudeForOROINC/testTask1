<?php

namespace Magecore\Bundle\TestTaskBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends EntityRepository
{
    public function findByMemberId($id)
    {

        $em = $this->getEntityManager();



        $rep = $em->createQueryBuilder()
            ->select('p')
            ->from('MagecoreTestTaskBundle:Project','p')
            ->innerJoin('p.members','m','WITH','m.id = :id');
        $rep->setParameter('id',$id);



        return $rep->getQuery()
            ->getResult();
    }

//    public function findOpenByCollaboratorId($id)
//    {
//        $em = $this->getEntityManager();
//
//
//
//        $rep = $em->createQueryBuilder()
//            ->select('i')
//            ->from('MagecoreTestTaskBundle:Issue','i')
////            ->from('MagecoreTestTaskBundle:DicStatus','ds')
//            ->innerJoin('i.collaborators','c','WITH','c.id = :id' )
//            ->innerJoin('i.status','ds','WITH','ds.value = :val')
//        ;
//
////        $rep->add('where', $rep->expr()->andX(
//////            $rep->expr()->eq('i.assignee',(string)$id ),
//////            $rep->expr()->andX($rep->expr()->eq('i.status','ds.id'),
//////                $rep->expr()->eq('ds.value','"Open"')
//////            )
////            $rep->expr()->eq('i.assignee',(string)$id ),
////            $rep->expr()->eq('ds.value',':val')
////
////        ))
////;
//        $rep->setParameter('val','value.open');
//        $rep->setParameter('id',$id);
//
//
//
//        return $rep->getQuery()
//            ->getResult();
//    }
}
