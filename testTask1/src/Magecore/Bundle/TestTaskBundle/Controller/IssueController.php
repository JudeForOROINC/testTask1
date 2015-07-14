<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;

use Magecore\Bundle\TestTaskBundle\Entity\Comment;
use Magecore\Bundle\TestTaskBundle\Entity\Project;
use Magecore\Bundle\TestTaskBundle\Entity\User;
use Magecore\Bundle\TestTaskBundle\Form\CommentType;
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
use Magecore\Bundle\TestTaskBundle\Controller\CommentController;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
        $currentUser = $this->getUser();
        if ($currentUser->hasRole('ROLE_ADMIN')) {
            $entities = $em->getRepository('MagecoreTestTaskBundle:Issue')->findAll();
        } else {
            $entities = $em->getRepository('MagecoreTestTaskBundle:Issue')
                ->findOpenByCollaboratorId($this->getUser()->getId());
        }

        $projects = $this->getAllowedProjects($currentUser);

        return array(
            'entities' => $entities,
            'projects' => $projects,
        );
    }

    /**
     * @param Project $project
     * @throws AccessDeniedException
     */
    protected function checkProjectAccess(Project $project)
    {
        if (!$this->isProjectAccessAllowed($project)) {
            throw new AccessDeniedException($this->get('translator')->trans('message.exception.no.member'));
            // hard core Secure to protect a not member intrude;
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
        if ($currentUser->hasRole('ROLE_ADMIN') || $currentUser->hasRole('ROLE_MANAGER')) {
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
        $entity->setCode('none');

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //set time
            $entity->setCreated(new \DateTime('now'));
            $entity->setUpdated($entity->getCreated());

            $this->setCollaborators($entity);

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
     * @param User $user
     * @return Project[]
     */
    protected function getAllowedProjects(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        if ($user->hasRole('ROLE_ADMIN') ||$user->hasRole('ROLE_MANAGER')) {
            $projects = $em->getRepository('MagecoreTestTaskBundle:Project')->findAll();
        } else {
            $projects = $em->getRepository('MagecoreTestTaskBundle:Project')->findByMemberId($user->getId());
        }
        return $projects;
    }

    /**
     * Creates a new Issue entity.
     *
     * @Route("/noproject/create", name="magecore_testtask_issue_noproject_create" )
     * @Template("MagecoreTestTaskBundle:Issue:new.html.twig")
     */
    public function createNoProjectAction(Request $request)
    {
        //Check is in a members;
        $current_user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $projects = $this->getAllowedProjects($current_user);

        if (empty($projects)) {
            #throw new AccessDeniedException( $this->get('translator')->trans('message.exception.no.issue.create'));
            throw new AccessDeniedException('You have no rights to create an issue!');

        }

        $entity = new Issue();
        $entity->setReporter($this->getUser());
        $entity->setCode('dd');

        $form = $this->createCreateForm($entity, $projects);

        $form->handleRequest($request);

        if ($form->isValid()) {
            //set time
            $entity->setCreated(new \DateTime('now'));
            $entity->setUpdated($entity->getCreated());

            $this->setCollaborators($entity);

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('magecore_testtask_issue_show', array('id' => $entity->getId())));
        }
        return ['form'=>$form->createView(),
                'entity'=>$entity];

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

        if (!$story->isStory()) {
            throw new BadRequestHttpException('Story type expected , but " '.( (string)$story->getType() ).' " given!');
        }

        $entity = new Issue();

        $entity->setParentIssue($story);
        $entity->setCode('none');
        $entity->setReporter($this->getUser());
        $entity->setProject($project);
        $entity->setType($entity::ISSUE_TYPE_SUBTASK);

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            //set time
            $entity->setCreated(new \DateTime('now'));
            $entity->setUpdated($entity->getCreated());

            $this->setCollaborators($entity);

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
    private function createCreateForm(Issue $entity, $projects = array())
    {
        if (count($projects)) {
            $url =  $this->generateUrl('magecore_testtask_issue_noproject_create');
        } else {
            if (empty($entity->getParentIssue())) {
                $url = $this->generateUrl(
                    'magecore_testtask_issue_create',
                    array('id'=>$entity->getProject()->getId())
                );
            } else {
                $url = $this->generateUrl(
                    'magecore_testtask_issue_subtask_create',
                    array('id'=>$entity->getParentIssue()->getId())
                );
            }
        }

        $form = $this->createForm(new IssueType(), $entity, array(
            'method' => 'POST',

            'projects' => $projects,
            'action' => $url,
        ));

        $form->add('submit', 'submit', array('label' => 'button.create'));

        return $form;
    }

    /**
     * Creates a form to create a Issue entity.
     *
     * @param Issue $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateNoProjectForm(Issue $entity)
    {
        $form = $this->createCreateForm($entity);
        $form->add('project', 'entity', array(
            'class' => 'Magecore\Bundle\TestTaskBundle\Entity\Project',

        ));

        return $form;
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
        //TODO check access
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagecoreTestTaskBundle:Issue')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Issue entity.');
        }

        $this->checkProjectAccess($entity->getProject());

        $comment = new Comment();
        $comment->setIssue($entity);
        $comment->setAuthor($this->getUser());
        $addCommentForm = $this->createForm(
            new CommentType(),
            $comment,
            array(
                'action' => $this->generateUrl('magecore_testtask_comment_create', array('id'=>$entity->getId()))
            )
        );

        return array(
            'entity'      => $entity,
            'addComment'  => $addCommentForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Issue entity.
     *
     * @Route("/{id}/edit", name="magecore_testtask_issue_edit")
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

        $this->checkProjectAccess($entity->getProject());

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
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

        $form->add('submit', 'submit', array('label' => 'button.update'));

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

        $this->checkProjectAccess($entity->getProject());

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setUpdated(new \DateTime('now'));
            $this->setCollaborators($entity);

            $em->flush();
            return $this->redirect($this->generateUrl('magecore_testtask_issue_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        );
    }

    /**
     * @param Issue $issue
     */
    protected function setCollaborators(Issue &$issue)
    {
        if ($issue->getReporter()) {
            $issue->addCollaborator($issue->getReporter());
        }
        if ($issue->getAssignee()) {
            $issue->addCollaborator($issue->getAssignee());
        }
    }

}
