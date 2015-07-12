<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
        ];
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
        return [
            'user' => $user,
            'issues'=>$issues,
        ];
    }

    /**
     * @param User $user
     * @return bool
     */
    protected function PermissionAcess(User $user)
    {
        $current_user = $this->getUser();

        //user may access its own profile
        if ( $user->isOwner($current_user)){
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
     * @param User $user
     * @return bool
     */
    protected function GetEditRolePermission(User $user)
    {
        $current_user = $this->getUser();

        //only admin may edit
        if(! $current_user->hasRole('ROLE_ADMIN') ){
            return false;
        }

        //admin can not edit itself.
        if( $user->isOwner($current_user) ){
            return false;
        }

        return true;

    }

    /**
     * @Route("/update/{id}", name="magecore_test_task_user_update", requirements={"id"="\d+"})
     * @Template
     */
    public function updateAction( User $entity,Request $request)
    {

        $this->CheckPermissions($entity);

        $form = $this->createForm(new UserType(),$entity,array(
            'is_admin'=>$this->GetEditRolePermission($entity)
        ));
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

}
