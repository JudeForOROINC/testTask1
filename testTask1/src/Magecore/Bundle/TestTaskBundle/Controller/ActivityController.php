<?php

namespace Magecore\Bundle\TestTaskBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Magecore\Bundle\TestTaskBundle\Entity\Activity;

/**
 * Activity controller.
 *
 * @Route("/activity")
 */
class ActivityController extends Controller
{

    /**
     * Lists all Activity entities.
     *
     * @Route("/", name="activity")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MagecoreTestTaskBundle:Activity')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Activity entity.
     *
     * @Route("/{id}", name="activity_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MagecoreTestTaskBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
}
