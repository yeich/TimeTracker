<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_index")
     */
    public function index(): RedirectResponse
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_index');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
