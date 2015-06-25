<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magecore\Bundle\TestTaskBundle\Entity\Issue;
use Magecore\Bundle\TestTaskBundle\Form\IssueType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Issue controller.
 *
 * @Route("/issue")
 */
class IssueController extends Controller
{

    /**
     * Lists all Issue entities.
     *
     * @Route("/", name="magecore_testtask_issue")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagecoreTestTaskBundle:Issue')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * @param Project $project
     * @throws AccessDeniedException
     */
    protected function checkProjectAccess(Project $project){
        if (!$this->isProjectAccessAllowed($project)) {
            throw new AccessDeniedException('You are not a member!'); // hard core Secure to protect a not member intrude;
        }
    }

    /**
     * Check if current logged in user has an access to input project
     *
     * @param Project $project
     * @return bool
     */
    protected function isProjectAccessAllowed(Project $project)
    {
        $currentUser = $this->getUser();

        // member must have access
        if ($project->isMember($currentUser)) {
            return true;
        }

        // any admin or manager must have access too
        if ($currentUser->hasRole('ROLE_ADMIN') || $currentUser->hasRole('ROLE_MANAGER'))  {
            return true;
        }

        return false;
    }

    /**
     * Creates a new Issue entity.
     *
     * @Route("/project/{id}/create", name="magecore_testtask_issue_create", requirements={"id"="\d+"} )
     * @Template("MagecoreTestTaskBundle:Issue:new.html.twig")
     */
    public function createAction(Request $request, Project $project)
    {
        $this->checkProjectAccess($project);


        $entity = new Issue();
        $entity->setReporter($this->getUser());
        $entity->setProject($project);


        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //set time
            $entity->setCreated(new \DateTime('now'));
            $entity->setUpdated($entity->getCreated());

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('magecore_testtask_issue_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Issue entity. type SubTask.
     *
     * @Route("/story/{id}/create", name="magecore_testtask_issue_subtask_create", requirements={"id"="\d+"})
     * @Template("MagecoreTestTaskBundle:Issue:new.html.twig")
     * @throws BadRequestHttpException
     */
    public function createSubtaskAction(Request $request, Issue $story)
    {
        $project =  $story->getProject();
        $this->checkProjectAccess($project);

        if (!$story->isStory()){
            throw new BadRequestHttpException('Story type expected , but " '.( (string)$story->getType() ).' " given!');
        }

        $entity = new Issue();

        $entity->setParentIssue($story);
        $entity->setProject($project);
        $entity->setType($entity::ISSUE_TYPE_SUBTASK);

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //set time
            $entity->setCreated(new \DateTime('now'));
            $entity->setUpdated($entity->getCreated());

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('magecore_testtask_issue_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }



    /**
     * Creates a form to create a Issue entity.
     *
     * @param Issue $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Issue $entity)
    {
        $form = $this->createForm(new IssueType(), $entity, array(
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Issue entity.
     *
     * @Route("/new/{id}", name="magecore_testtask_issue_new", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     * //TODO Remove this action.
     */
    public function newAction(Project $project)
    {
        $entity = new Issue();
        //var_dump($this->getUser());
        $entity->setReporter( $this->getUser());
        $entity->setSummary('mama mila ramu');
        $entity->setCreated(new \DateTime('now'));
        var_dump($entity->getCreated());
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Issue entity.
     *
     * @Route("/{id}", name="magecore_testtask_issue_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagecoreTestTaskBundle:Issue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Issue entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Issue entity.
     *
     * @Route("/{id}/edit", name="issue_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagecoreTestTaskBundle:Issue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Issue entity.');
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
    * Creates a form to edit a Issue entity.
    *
    * @param Issue $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Issue $entity)
    {
        $form = $this->createForm(new IssueType(), $entity, array(
            'action' => $this->generateUrl('issue_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Issue entity.
     *
     * @Route("/{id}", name="issue_update")
     * @Method("PUT")
     * @Template("MagecoreTestTaskBundle:Issue:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagecoreTestTaskBundle:Issue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Issue entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setUpdated(new \DateTime('now'));
            $em->flush();

            //return $this->redirect($this->generateUrl('issue_edit', array('id' => $id)));
            return $this->redirect($this->generateUrl('magecore_testtask_issue_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Issue entity.
     *
     * @Route("/{id}", name="issue_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MagecoreTestTaskBundle:Issue')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Issue entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('issue'));
    }

    /**
     * Creates a form to delete a Issue entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('issue_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    //protected function CreateIssue

}
