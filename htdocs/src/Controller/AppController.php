<?php

namespace App\Controller;

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

        return $this->render('internal/index.html.twig', [
            'projects' => $projects
        ]);
    }
}
