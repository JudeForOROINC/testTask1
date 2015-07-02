<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 30.06.15
 * Time: 15:44
 */
namespace Magecore\Bundle\TestTaskBundle\Helper;

use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;

class DoctrineHelper{
    protected $DatabaseTimezone = 'UTC';

    public function getDateTime(\DateTime $date){
        //$date - in UTC Time zone - must return in a system TimeZone
            $date->setTimezone(new \DateTimeZone( \date_default_timezone_get() ));
        return $date;
    }
    public function setDatetime(\DateTime $date){
        //$date - in system Time zone - must return in a  UTC TimeZone
        return $date->setTimezone(new \DateTimeZone( $this->DatabaseTimezone ));
    }

    public function getName()
    {
        return 'doctrine_helper';
    }
}