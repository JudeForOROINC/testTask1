<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\Activity;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Proxies\__CG__\Magecore\Bundle\TestTaskBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Form\CommentType;
use Symfony\Component\HttpFoundation\Response;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;

/**
 * Comment controller.
 *
 * @Route("/comment")
 */
class MaillerController extends Controller
{




    /**
     * Lists all Comment entities.
     * @Temlplate
     */
    public function FormMailAction(Activity $activity)
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
                $title =  $this->get('translator')->trans('CIAT');
            }
            if ($activity->isChangeStatusType() ){
                $pattern = 'MagecoreTestTaskBundle:Mailer:chia.html.twig';
                $title =  $this->get('translator')->trans('CSAT');
            }
            if ($activity->isCommentType() ){
                $pattern = 'MagecoreTestTaskBundle:Mailer:coia.html.twig';
                $title =  $this->get('translator')->trans('COAT');
            }

            $body = $this->renderView($pattern,array('entity'=>$activity, 'name'=>$name));
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

    /**
     * @Route("/mail/send/", name="magecore_testtask_mail_send")


     */
    public function mailAction($mail = 'ad@to'){
//                $message = \Swift_Message::newInstance()
//                ->setSubject('Hello Email')
//                ->setFrom('noreplay@magecore.com')
//                ->setTo($mail)
//                ->setBody(
////             $this->renderView(
////                    // app/Resources/views/Emails/registration.html.twig
////                        'Emails/registration.html.twig',
////                        array('name' => $name)
////                    ),
//                //$this->
//
//                    '<body>test</body>',
//                    'text/html'
//                )
//                /*
//                 * If you also want to include a plaintext version of the message
//                ->addPart(
//                    $this->renderView(
//                        'Emails/registration.txt.twig',
//                        array('name' => $name)
//                    ),
//                    'text/plain'
//                )
//                */
//            ;
//            $this->get('mailer')->send($message);
//         return $this->render(new Response('ok'));

//        $trans = \Swift_SmtpTransport::newInstance(
//            '127.0.0.1',1025
//        );
//            $maler = \Swift_Mailer::newInstance($trans);


        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('send@example.com')
            ->setTo('recipient@example.com')
            ->setBody(
//                $this->renderView(
//                // app/Resources/views/Emails/registration.html.twig
//                    'Emails/registration.html.twig',
//                    array('name' => $name)
//                ),
                '<body>mama mila ramu</body>',
                'text/html'
            )
            /*
             * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'Emails/registration.txt.twig',
                    array('name' => $name)
                ),
                'text/plain'
            )
            */
        ;
//        $result = $maler->send($message);

        //var_dump($result);
    //    var_dump($this->get('swiftmailer.mailer'));

      //  var_dump($this->get('mailer'));
        $this->get('mailer')
//
//
            ->send($message);
//        //var_dump($this->get('mailer'));

        return new Response('ok'); //$this->render(...);
    }


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
