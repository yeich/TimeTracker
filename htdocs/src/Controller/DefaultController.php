<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/forgot-password", name="default_forgot_password", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function forgot_password(Request $request)
    {

        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_index');
        }

        if($request->isMethod('POST')) {

            $email = $request->request->get('email');
            $em = $this->getDoctrine()->getManager();

            $user = $em->getRepository('App:User')->findOneBy(['email' => $email]);

            if(!$user) {
                return new JsonResponse([
                    'error' => true,
                    'msg' => 'No account registered to this email.'
                ]);
            }

            $hash = $user->generateHash();

            while($em->getRepository('App:User')->findOneBy(['password_reset_hash' => $hash])) {
                $hash = $user->generateHash();
            }

            $user->setPasswordResetHash($hash);

            $em->persist($user);
            $em->flush();

            //TODO: SendMail to User with Hash

            return new JsonResponse([
                'error' => false
            ]);
        }

        return $this->render('default/forgot_password.html.twig');
    }
}
