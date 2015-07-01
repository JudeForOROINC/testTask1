<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

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
class CommentController extends Controller
{

    /**
     * Lists all Comment entities.
     *
     * @Route("/", name="comment")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagecoreTestTaskBundle:Comment')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Comment entity.
     *
     * @Route("/create/issue/{id}", name="magecore_testtask_comment_create", requirements={"id"="\d+"})
     * @Method("POST")

     */
    public function createAction(Request $request, Issue $issue)
    {
       // return new Response('kill pigs!!!',200);
        //return new Response(var_dump($request->isXmlHttpRequest()),200);
        //return new Response(var_dump($request),200);
         //TODO check secure;


        if (!$request->isXmlHttpRequest()){
            return new JsonResponse(array('message'=>'You can access this only using Ajax!'), 400);
        }

        $entity = new Comment();
        $entity->setIssue($issue);
        $entity->setAuthor($this->getUser());
        $form = $this->createCreateForm($entity);


        $form->handleRequest($request);
        //return new Response(var_dump($form->isValid()),200);
        #return new Response(var_dump($entity),200);
        //return new JsonResponse(array('message'=>'Success!'),200);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            #return $this->redirect($this->generateUrl('comment_show', array('id' => $entity->getId())));
            $entity = new Comment();
            $entity->setIssue($issue);
            $entity->setAuthor($this->getUser());
            $form = $this->createCreateForm($entity);

            return new JsonResponse(array('message'=>
                $this->renderView('MagecoreTestTaskBundle:Comment:index.html.twig',
                array(
                    #entities: entity.comments, addComment: addComment
                    'entities'=>$issue->getComments(),
                    'addComment'=>$form->createView(),
                    'issue_id'=>$issue->getId(),
                )),
            ),200);

            return new JsonResponse(array('message'=>'Success!'),200);#$this->redirect($this->generateUrl('comment_show', array('id' => $entity->getId())));
        }
        $responce = new JsonResponse(
            array(
                'message'=>'Error',
                'form'=>$this->renderView('MagecoreTestTaskBundle:Comment:show.html.twig',
                    array(
                        'entity'=>$entity,
                        'form'=>$form->createView(),
                    )),

            ), 400);
        return $responce;
//
//        return array(
//            'entity' => $entity,
//            'form'   => $form->createView(),
//        );
    }

    /**
     * Creates a form to create a Comment entity.
     *
     * @param Comment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comment $entity)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('magecore_testtask_comment_create',array('id' => $entity->getIssue()->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    private function createNewForm(Comment $entity)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('magecore_testtask_comment_create', array('id'=>$entity->getIssue())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }
    /**
     * Displays a form to create a new Comment entity.
     *
     * @Route("/new", name="comment_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Comment();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Comment entity.
     *
     * @Route("/{id}", name="comment_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {


        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagecoreTestTaskBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Comment entity.
     *
     * @Route("/{id}/edit", name="comment_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagecoreTestTaskBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Comment entity.
    *
    * @param Comment $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Comment $entity)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('comment_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Comment entity.
     *
     * @Route("/{id}", name="comment_update")
     * @Method("PUT")
     * @Template("MagecoreTestTaskBundle:Comment:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagecoreTestTaskBundle:Comment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Comment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('comment_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Comment entity.
     *
     * @Route("/{id}", name="comment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagecoreTestTaskBundle:Comment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Comment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('comment'));
    }

    /**
     * Creates a form to delete a Comment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comment_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
