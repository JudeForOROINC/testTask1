<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/{name}", name="magecore_test_task_issue_index")
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($name)
    {
        return $this->render('User/index.html.twig');
    }

    /**
     * Must view an a user page. with a user profile like an part of page;
     * @Route("/view/{id}", name="magecore_test_task_issue_view", requirements={"id"="\d+"})
     * @Template
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(User $user){
        return ['name'=>$user->getUsername()];
    }
    /**
     * Must view an a user page. with a user profile like an part of page;
     * @Route("/create", name="magecore_test_task_issue_create", requirements={"id"="\d+"})
     * @Template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        #must be forbiden; we create users from console only;
        // ...

    }

    /**
     * @Route("/update/{id}", name="magecore_test_task_issue_update", requirements={"id"="\d+"})
     * @Template
     */
    public function updateAction(User $entity)
    {
        // ...
    }

    /**
     * @Route("/delete/{id}", name="magecore_test_task_issue_delete", requirements={"id"="\d+"})
     */
    public function deleteAction(User $user)
    {
        // ...
    }


}
