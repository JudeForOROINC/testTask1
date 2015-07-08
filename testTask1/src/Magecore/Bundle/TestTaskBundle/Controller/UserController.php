<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

//use Magecore\Bundle\TestTaskBundle\Entity\Project;
//use Magecore\Bundle\TestTaskBundle\Form\Type\ProjectType;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
//use Symfony\Component\BrowserKit\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UserController extends Controller
{





    /**
     * @Route("/list", name="magecore_test_task_user_index")
     * @Template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $en = $this->getDoctrine()->getManager();
        $users = $en->getRepository('MagecoreTestTaskBundle:User')->findAll();
        return [
            'users'=>$users,
        ]//$this->render('User/index.html.twig');
        ;
    }

    /**
     * Must view an a user page. with a user profile like an part of page;
     * @Route("/view/{id}", name="magecore_test_task_user_view", requirements={"id"="\d+"})
     * @Template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(User $user){
        $em = $this->getDoctrine()->getEntityManager();
        $rep = $em->getRepository('MagecoreTestTaskBundle:Issue');
        $issues = $rep->findOpenByUserId($user->getId());
//        return new Response(var_dump($activity),200);
        return [
            'user' => $user,
            'issues'=>$issues,
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
     * @param User $user
     * @return bool
     */
    protected function PermissionAcess(User $user)
    {
        $current_user = $this->getUser();

        //user may access its own profile
        if ($user.isOwner($current_user)){
            return true;
        }

        //admin must have permissions to edit comments
        if ($current_user->hasRole('ROLE_ADMIN')){
            return true;
        }

        return false;
    }


    /**
     * @param User $user
     * @throws AccessDeniedException
     */
    protected  function CheckPermissions(User $user)
    {
        if (!$this->PermissionAcess($user)){
            throw new AccessDeniedException('You have no permissions to edit this profile!!!');
        }
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
        $this->CheckPermissions($entity);

        $form = $this->createForm(new UserType(),$entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $entity->upload();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('magecore_test_task_user_view',['id'=>$entity->getId()]));
        }

        return $this->render('MagecoreTestTaskBundle:User:update.html.twig', array(
            'form' => $form->createView(),
        ));
    }

//    /**
//     * @Route("/delete/{id}", name="magecore_test_task_user_delete", requirements={"id"="\d+"})
//     */
//    public function deleteAction(User $user)
//    {
//        // ...
//    }
//


}
