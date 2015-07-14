<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\Project;
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
     * Check user permission: add comment may only a member
     * @param Comment $comment
     * @return bool
     */
    protected function isPermissoinAllowed(Comment $comment)
    {

        $current_user =  $this->getUser();
        //user can edit its own comment
        if ($comment->isOwner($current_user)) {
            return true;
        }

        //admin may edit every comment
        if ($current_user->hasRole('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    /**
    * This must by in secure lair, and it is bad, that use it here.
    */
    protected function isProjectAccessAllowed(Project $project)
    {
        $currentUser = $this->getUser();

        // member must have access
        if ($project->isMember($currentUser)) {
            return true;
        }

        // any admin or manager must have access too
        if ($currentUser->hasRole('ROLE_ADMIN') || $currentUser->hasRole('ROLE_MANAGER')) {
            return true;
        }

        return false;
    }


    /**
     * Lists all Comment entities.
     * @param Issue $issue
     * @return JsonResponse
     */
    protected function listAction(Issue $issue)
    {
        $entity = new Comment();
        $entity->setIssue($issue);
        $entity->setAuthor($this->getUser());
        $form = $this->createCreateForm($entity);

        return new JsonResponse(array('message'=>
            $this->renderView(
                'MagecoreTestTaskBundle:Comment:index.html.twig',
                array(
                    #entities: entity.comments, addComment: addComment
                    'entities'=>$issue->getComments(),
                    'addComment'=>$form->createView(),
                    'issue_id'=>$issue->getId(),
                )
            ),
        ), 200);
    }

    /**
     * Creates a new Comment entity.
     *
     * @Route("/create/issue/{id}", name="magecore_testtask_comment_create", requirements={"id"="\d+"})
     * @Method("POST")
     */
    public function createAction(Request $request, Issue $issue)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message'=>'You can access this only using Ajax!'), 400);
        }

        if (!$this->isProjectAccessAllowed($issue->getProject())) {
            return new JsonResponse(array('message'=>'You have no permissons to create a comment!'), 403);
        };

        $entity = new Comment();
        $entity->setIssue($issue);
        $entity->setAuthor($this->getUser());
        $form = $this->createCreateForm($entity);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $issue->addCollaborator($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->listAction($issue);
        }
        $responce = new JsonResponse(
            array(
                'message'=>'Error',
                'form'=>$this->renderView(
                    'MagecoreTestTaskBundle:Comment:show.html.twig',
                    array(
                        'entity'=>$entity,
                        'form'=>$form->createView(),
                    )
                ),

            ),
            400
        );
        return $responce;
    }


    /**
     * Creates a form to create a Comment entity.
     * @param Comment $entity The entity
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Comment $entity)
    {
        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl(
                'magecore_testtask_comment_create',
                array('id' => $entity->getIssue()->getId())
            ),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * @param Comment $entity
     * @return \Symfony\Component\Form\Form
     */
    private function createEditForm(Comment $entity)
    {

        $form = $this->createForm(new CommentType(), $entity, array(
            'action' => $this->generateUrl('magecore_testtask_comment_edit', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        return $form;
    }


    /**
     * Displays a form to edit an existing Comment entity.
     * This method MUST reload page to made Post form like an Edit form of a special comment
     * @Route("/{id}/edit", name="magecore_testtask_comment_edit", requirements={"id"="\d+"})
     * @Method("POST")
     * @Template()
     */
    public function editAction(Request $request, Comment $comment)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message'=>'You can access this only using Ajax!'), 400);
        }

        if (!$this->isPermissoinAllowed($comment)) {
            return new JsonResponse(array('message'=>'You can not edit this comment !'), 403);
        }

        $issue = $comment->getIssue();

        $entity = $comment;
        $entity->setIssue($issue);
        $entity->setAuthor($this->getUser());
        $form = $this->createEditForm($entity);

        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            return new JsonResponse(array('message'=>
                $this->renderView(
                    'MagecoreTestTaskBundle:Comment:index.html.twig',
                    array(
                        'entities'=>$issue->getComments(),
                        'addComment'=>$form->createView(),
                        'issue_id'=>$issue->getId(),
                        'edit_id'=>$comment->getId(),
                    )
                ),
            ), 200);
        }

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->listAction($issue);
        }

        return new JsonResponse(array('message'=>
            $this->renderView(
                'MagecoreTestTaskBundle:Comment:index.html.twig',
                array(
                    #entities: entity.comments, addComment: addComment
                    'entities'=>$issue->getComments(),
                    'addComment'=>$form->createView(),
                    'issue_id'=>$issue->getId(),
                    'edit_id'=>$comment->getId(),
                )
            ),
        ), 200);
    }


    /**
     * Deletes a Comment entity.
     * @Route("/remove/{id}", name="magecore_testtask_comment_delete", requirements={"id"="\d+"})
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message'=>'You can access this only using Ajax!'), 400);
        }
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('MagecoreTestTaskBundle:Comment')->find($id);
        if (!empty($entity)) {
            if (!$this->isPermissoinAllowed($entity)) {
                return new JsonResponse(array('message'=>'You can not remove this comment !'), 403);
            }
            $issue = $entity->getIssue();
            $em->remove($entity);
            $em->flush();
        } else {
            //TODO error message for not found with 400.
        }

        return $this->listAction($issue);
    }
}
