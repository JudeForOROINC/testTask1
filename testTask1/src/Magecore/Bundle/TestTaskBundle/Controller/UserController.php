<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Form\Type\ProjectType;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
//use Symfony\Component\BrowserKit\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserController extends Controller
{
    /**
     * @Route("/list", name="magecore_test_task_user_index")
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($name)
    {
        return $this->render('User/index.html.twig');
    }

    /**
     * Must view an a user page. with a user profile like an part of page;
     * @Route("/view/{id}", name="magecore_test_task_user_view", requirements={"id"="\d+"})
     * @Template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(User $user){
        return [
            'user' => $user,
            'name'=>$user->getUsername(),
        ];
    }
    /**
     * Must view an a user page. with a user profile like an part of page;
     * @Route("/create", name="magecore_test_task_user_create", requirements={"id"="\d+"})
     * @Template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        #must be forbiden; we create users from console only;
        // ...
        return new Response('ok');
    }

    /**
     * @Route("/update/{id}", name="magecore_test_task_user_update", requirements={"id"="\d+"})
     * @Template
     */
    public function updateAction( User $entity,Request $request)
    {
        // ...
        //return new Response('ok');
        //$user = new User();
        $form = $this->createForm(new UserType(),$entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('magecore_test_task_user_view',['id'=>$entity->getId()]));
        }

        return $this->render('MagecoreTestTaskBundle:User:update.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/delete/{id}", name="magecore_test_task_user_delete", requirements={"id"="\d+"})
     */
    public function deleteAction(User $user)
    {
        // ...
    }


}
