<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    /**
     * @Route("/reset-password/{hash}", name="default_reset_password", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function reset_password(Request $request, UserPasswordEncoderInterface $passwordEncoder, $hash)
    {

        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_index');
        }

        $em = $this->getDoctrine()->getManager();

        if($request->isMethod('POST')) {

            $user = $em->getRepository('App:User')->findOneBy(['password_reset_hash' => $hash]);

            if(!$user) {
                return new JsonResponse([
                    'error' => true,
                    'msg' => 'Internal server error. Please contact the system administrator.'
                ]);
            }

            $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));
            $user->setPasswordResetHash(null);
            $user->setPasswordResetTimestamp(null);

            $em->persist($user);
            $em->flush();
            return new JsonResponse([
                'error' => false
            ]);
        }

        $user = $em->getRepository('App:User')->findOneBy(['password_reset_hash' => $hash]);

        if(!$user || ($user && $user->getPasswordResetTimestamp()->diff(new \DateTime('now'))->h > 48)) {
            return $this->render('default/reset_password.html.twig', [
                'error' => true
            ]);
        }

        return $this->render('default/reset_password.html.twig', [
            'error' => false,
            'hash' => $hash
        ]);
    }
}
