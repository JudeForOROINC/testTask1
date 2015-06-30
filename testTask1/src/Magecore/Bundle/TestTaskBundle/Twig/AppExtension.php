<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 30.06.15
 * Time: 12:58
 */

namespace Magecore\Bundle\TestTaskBundle\Twig;

use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
            new \Twig_SimpleFilter('datertz', array($this, 'datertzFilter')),
            new \Twig_SimpleFilter('url', array($this, 'urlFilter')),
            new \Twig_SimpleFilter('id', array($this, 'idFilter')),
            new \Twig_SimpleFilter('anchor', array($this, 'anchorFilter')),
        );
    }

    public function priceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public function datertzFilter(\DateTime $date, User $user){
        //code
        $tz = $user->getTimezone();
        $date->setTimezone(new \DateTimeZone($tz));
        return $date->format('Y.m.d H:i:s');
    }

    public function urlFilter( $entity ){
        //code
        $url='';
        if ($entity instanceof User) {

            $url = 'magecore_test_task_user_view';
        }

        if ($entity instanceof Project) {

            $url = 'magecore_test_task_project_view';
        }

        if ($entity instanceof Issue) {

            $url = 'magecore_testtask_issue_show';
        }

        return $url;
    }

    public function anchorFilter( $entity ){
        //code
        $anchor='';
        if ($entity instanceof Comment)

            $anchor = '#comment-'.$entity->getId();

        return $anchor;
    }

    public function idFilter( $entity ){
        //code
        $id='';
        if ($entity instanceof Comment) {

            $id = $entity->getIssue()->getId();
        }
        if ($entity instanceof User){
            $id = $entity->getId();
        }
        if ($entity instanceof Project){
            $id = $entity->getId();
        }
        if ($entity instanceof Issue) {

            $id = $entity->getId();
        }

        return $id;
    }


    public function getName()
    {
        return 'app_extension';
    }
}