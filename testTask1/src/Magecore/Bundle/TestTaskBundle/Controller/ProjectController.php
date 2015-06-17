<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/list", name="magecore_test_task_project_index")
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($name)
    {
        return $this->render('Project/index.html.twig');
    }

    /**
     * Must view an a user page. with a user profile like an part of page;
     * @Route("/view/{id}", name="magecore_test_task_project_view", requirements={"id"="\d+"})
     * @Template
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction(Project $project){
        $form = $this->createFormBuilder($project)
        ->add('label','text')
        ->add('id','integer')
        ->add('code','text')
        ->add('summary','text')
        ->add('save','submit',array('label'=>'Create task'))
        ->getForm();



        //return ['name'=>$project->getLabel()];
//        return $this->render('Project/view.html.twig',array(
//            'form' => $form->createView(),
//        ));
        return [
            'form' => $form->createView(),
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
        #must be forbiden TO create by users. SuperAdmin&Manager only;
        #try to create an
        // ...

        # three types of request.
        // 1) empty; (for post)
        // 2) Errors (from validator)
        // 3) Save valid data & redirect to new page.
        $project = new Project();

        $form = $this->createFormBuilder($project)
            ->add('label','text')
            ->add('code','text')
            ->add('summary','text')
            ->add('save','submit',array('label'=>'Create task'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()){
            return new Response('Created project id '.$project->getId());
        }
/*
        if ($request->query->getMethod() == 'GET'){
            //must draw blank edit form;
            $project = new Project();

            return [
                'form' => $form->createView(),
                'name'=>$project->getLabel(),
            ];
        }

*/
        /*
        $project = new Project();
        $project->setLabel('New Project Label');
        $project->setSummary('discription of a project');

        $project->setCode(0 );

        $em = $this->getDoctrine()->getManager();

        $em->persist($project);
        $em->flush();
*/
        return new Response('Created project id '.$project->getId());
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
        $form = $this->createFormBuilder($project)
            ->add('label','text')

            ->add('code','text')
            ->add('summary','text')
            ->add('save','submit',array('label'=>'Create task'))
            ->getForm();

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
    public function updateAction( Project $project)
    {
        // ...
//       $em = $this->getDoctrine()->getManager();
//        $project = $em->getRepository('MagecoreTestTaskBundle:Project')->find($id);
//
//        if (!$project) {
//            throw $this->createNotFoundException(
//                'No product found for id '.$id
//            );
//        }
        $em = $this->getDoctrine()->getManager();
        $project->setLabel('New project name!Done!');
        $em->flush();

        return $this->redirect($this->generateUrl('magecore_test_task_project_view',['id'=>$project->getId()]));


    }

    /**
     * @Route("/delete/{id}", name="magecore_test_task_project_delete", requirements={"id"="\d+"})
     */
    public function deleteAction(Project $project)
    {
        // ...
    }


}
