<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 10.07.15
 * Time: 11:04
 */
namespace Magecore\Bundle\TestTaskBundle\EventListener;

use Magecore\Bundle\TestTaskBundle\Entity\Activity;

class MailerListener {
    protected $container;
    //
    public function FormMailAction(\Magecore\Bundle\TestTaskBundle\Entity\Activity $activity)
    {
        $users = $activity->getIssue()->getCollaborators();
        $letters=array();
        foreach ($users as $user){
            $mail = $user->getEmail();
            $name = $user->getFullName();
            $title = 'Event for '.$name;
            $pattern = 'MagecoreTestTaskBundle:Mailer:view.html.twig';
            if ($activity->isNewIssueType() ){
                $pattern = 'MagecoreTestTaskBundle:Mailer:cnia.html.twig';
                $title =  $this->container->get('translator')->trans('CIAT');
            }
            if ($activity->isChangeStatusType() ){
                $pattern = 'MagecoreTestTaskBundle:Mailer:chia.html.twig';
                $title =  $this->container->get('translator')->trans('CSAT');
            }
            if ($activity->isCommentType() ){
                $pattern = 'MagecoreTestTaskBundle:Mailer:coia.html.twig';
                $title =  $this->container->get('translator')->trans('COAT');
            }

            $body = $this->container->get('templating')->render($pattern, array('entity'=>$activity, 'name'=>$name));
            $letter = array(
                'mail'=>$mail,
                'name'=>$name,
                'title'=>$title,
                'letter'=>$body,
            );

            $letters[]=$letter;
        }

        return $letters;
    }

    public function PushMail(Activity $entity){

        $mailer = $this->container->get('mailer');

        $arr = $this->FormMailAction($entity);

        if (!empty($arr) && count($arr )){

            foreach($arr as $letter){
                $message = \Swift_Message::newInstance()
                    ->setSubject($letter['title'])
                    ->setFrom('send@example.com')
                    ->setTo($letter['mail'])
                    ->setBody(
                        $letter['letter'],'text/html');

                $result = $mailer->send($message);

            }
        }
        return $result;
    }

    public function __construct(\Symfony\Component\DependencyInjection\ContainerInterface $container)
    {
        $this->container = $container;
    }
}