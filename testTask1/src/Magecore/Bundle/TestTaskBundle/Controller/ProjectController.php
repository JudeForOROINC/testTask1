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
     * @Route("/list", name="magecore_test_task_project_index")
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        //return $this->render('Project/index.html.twig');
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
        return [
            'project' => $project,
            'name'=>$project->getLabel(),
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

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

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
     * Creates a new Post entity.
     *
     * @Route("/new", name="magecore_test_task_project_new")
      *
     * NOTE: the Method annotation is optional, but it's a recommended practice
     * to constraint the HTTP methods each controller responds to (by default
     * it responds to all methods).
     */
    public function newAction(Request $request)
    {
        $project = new Project();
        //$post->setAuthorEmail($this->getUser()->getEmail());
//        $form = $this->createForm(new PostType(), $post);

        $form = $this->createForm(new ProjectType(), $project);
       /* $form = $this->createFormBuilder($project)
            ->add('label','text')

            ->add('code','text')
            ->add('summary','text')
            ->add('save','submit',array('label'=>'Create task'))
            ->getForm();
*/
        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See http://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            //$post->setSlug($this->get('slugger')->slugify($post->getTitle()));

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirect($this->generateUrl('magecore_test_task_project_view',['id'=>$project->getId()]));
        }

        return $this->render('MagecoreTestTaskBundle:Project:edit.html.twig', array(
            'name' => $project->getLabel(),
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/update/{id}", name="magecore_test_task_project_update", requirements={"id"="\d+"})
     * @Template
     */
    public function updateAction( Project $project, Request $request)
    {
        //TODO : Jude - remove constraints from Form = use form class + validatorclass;
        $form = $this->createFormBuilder($project)
            ->add('label','text', array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max'=>100,'maxMessage'=>'Project Label cannot be longer than {{ limit }} characters!')),
                ),

            ))

            ->add('code','text',array(
                'constraints' => array(
                    new NotBlank(),
                    new Length(array('max'=>3,'maxMessage'=>'Project Label cannot be longer than {{ limit }} characters!')),
                ),
            ))
            ->add('summary','textarea')
            ->add('save','submit',array('label'=>'Create task'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            //return $this->redirect($this->generateUrl('magecore_test_task_project_view',['id'=>$project->getId()]));
            return $this->redirect($this->generateUrl('magecore_test_task_project_view',['id'=>$project->getId()]));
        }


/*        $em = $this->getDoctrine()->getManager();

        $project->setLabel('New project name!Done!');
        $em->flush();*/

//        return $this->redirect($this->generateUrl('magecore_test_task_project_view',['id'=>$project->getId()]));
        //return $this->render('MagecoreTestTaskBundle:Project:edit.html.twig', array(
        return array(
            'name' => $project->getLabel(),
            'form' => $form->createView(),
        );

    }

    /**
     * @Route("/delete/{id}", name="magecore_test_task_project_delete", requirements={"id"="\d+"})
     */
    public function deleteAction(Project $project)
    {
        // ...
    }


}
