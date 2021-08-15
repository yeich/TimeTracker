<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\TimestampProject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/app")
 */
class AppController extends AbstractController
{

    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $projects = $em->getRepository('App:Project')->findAllUserProjects($user);
        $projects_arr = [];

        foreach ($projects as $project) {

            $temp = $project;

            $project_timestamps = $project->getTimestampProjects();

            $now = new \DateTime('now');
            $cloned = clone $now;

            foreach ($project_timestamps as $project_timestamp) {
                $cloned->add($project_timestamp->getStartStamp()->diff($project_timestamp->getEndStamp()));
            }

            $temp->{'total_time'} = ($now->diff($cloned))->format('%H:%I');

            $projects_arr[] = $temp;
        }

        $open_timestamp = $em->getRepository('App:TimestampProject')->findOneBy(['user' => $user, 'end_stamp' => null]);
        $active_project_id = null;

        if($open_timestamp) {
            $active_project_id = $open_timestamp->getProject()->getId();
        }

        return $this->render('internal/index.html.twig', [
            'projects' => $projects_arr,
            'active_project' => $active_project_id
        ]);
    }
}
