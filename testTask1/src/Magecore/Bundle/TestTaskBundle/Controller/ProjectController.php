<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Form\Type\ProjectType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
//use MagecoreTestTaskBundle\Form\Type\ProjectType;

class ProjectController extends Controller
{
    /**
     * @Route("/{page}/{limit}", name="magecore_test_task_project_index", requirements={"page"="\d+","limit"="\d+"}, defaults={"page"=1,"limit"=20})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        //TODO: add grid to use page + limit;
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('MagecoreTestTaskBundle:Project')->findAll();

        return $this->render('@MagecoreTestTask/Project/list.html.twig',array('projects'=>$projects));
    }

    /**
     * Must view an a user page. with a user profile like an part of page;
     * @Route("/view/{id}", name="magecore_test_task_project_view", requirements={"id"="\d+"})
     * @Template
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Project $project){
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('MagecoreTestTaskBundle:Activity');
        $activity = $rep->findAllByProjectId($project->getId());

        return [
            'project' => $project,
            'name'=>$project->getLabel(),
            'activities' => $activity,
        ];
    }
    /**
     * Must view an a user page. with a user profile like an part of page;
     * @Route("/create", name="magecore_test_task_project_create")
     * @Template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction(Request $request)
    {
        $project = new Project();

        $form = $this->createForm(new ProjectType(),$project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('magecore_test_task_project_view',['id'=>$project->getId()]));
        }

        return $this->render('MagecoreTestTaskBundle:Project:edit.html.twig', array(
            'name' => $project->getLabel(),
            'form' => $form->createView(),
        ));

        #must be forbiden TO create by users. SuperAdmin&Manager only;
    }

    /**
     * @Route("/update/{id}", name="magecore_test_task_project_update", requirements={"id"="\d+"})
     * @Template
     */
    public function updateAction( Project $project, Request $request)
    {
        //TODO : Jude - remove constraints from Form = use form class + validatorclass;
        $form = $this->createForm(new ProjectType(),$project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            //return $this->redirect($this->generateUrl('magecore_test_task_project_view',['id'=>$project->getId()]));
            return $this->redirect($this->generateUrl('magecore_test_task_project_view',['id'=>$project->getId()]));
        }

        return array(
            'name' => $project->getLabel(),
            'form' => $form->createView(),
        );

    }


}
