<?php

namespace App\Controller;

use App\Entity\TimestampProject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 */
class ApiV1Controller extends AbstractController
{
    /**
     * @Route("/project/tracking/{projectId}", name="api_v1_project_tracking", methods={"GET"})
     */
    public function projectTracking($projectId): JsonResponse
    {
        
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $timestamp = $em->getRepository('App:TimestampProject')->findOneBy(['user' => $user, 'end_stamp' => null]);

        if($timestamp) {
            $timestamp->setEndStamp(new \DateTime('now'));
            $em->persist($timestamp);
        }

        $project = $em->getRepository('App:Project')->findOneById($projectId);

        return new JsonResponse($project->getUsers());

        if(!$project) {
            return new JsonResponse([
                'error' => true,
                'msg' => 'No Project found!'
            ]);
        }

        $timestamp = new TimestampProject();
        $timestamp->setProject($project);

        return new JsonResponse(['error' => false, 'msg' => 'success']);
    }
}
