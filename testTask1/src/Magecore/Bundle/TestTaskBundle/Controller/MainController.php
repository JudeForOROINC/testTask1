<?php
/**
 * Created by PhpStorm.
 * User: jude
 * Date: 02.07.15
 * Time: 17:15
 */
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

class MainController extends Controller
{

    public function viewAction()
    {
        $em = $this->getDoctrine()->getManager();
        //$projects = $em->getRepository('MagecoreTestTaskBundle:Project')->findAll();

        $rep = $em->getRepository('MagecoreTestTaskBundle:Activity');
        $activity = $rep->findAllByProjectId($project->getId());
        return [
            'project' => $project,
            'name' => $project->getLabel(),
            'activities' => $activity,
        ];
    }
}