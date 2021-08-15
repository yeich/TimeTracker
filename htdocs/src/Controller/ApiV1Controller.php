<?php

namespace App\Controller;

use App\Entity\TimestampProject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1")
 */
class ApiV1Controller extends AbstractController
{
    /**
     * @Route("/project/tracking/{projectId}/{type}", name="api_v1_project_tracking", methods={"GET"}, options={"expose"=true})
     */
    public function projectTracking($projectId, $type): JsonResponse
    {
        
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $timestamp = $em->getRepository('App:TimestampProject')->findOneBy(['user' => $user, 'end_stamp' => null]);

        if($timestamp) {

            $timestamp_project = $timestamp->getProject();

            $project = $em->getRepository('App:Project')->findOneById($projectId);

            if(!$project) {

                $timestamp->setEndStamp(new \DateTime('now'));
                $em->persist($timestamp);
                $em->flush();

                return new JsonResponse([
                    'error' => true,
                    'msg' => 'No Project found!'
                ]);
            }

            if($type == 'checkout') {

                $timestamp->setEndStamp(new \DateTime('now'));
                $em->persist($timestamp);
                $em->flush();

                return new JsonResponse([
                    'error' => false,
                    'msg' => 'success',
                    'type' => 'checkout'
                ]);
            }

            if($project === $timestamp_project) {
                return new JsonResponse([
                    'error' => false,
                    'msg' => 'success',
                    'type' => 'no changes'
                ]);
            }

            $timestamp->setEndStamp(new \DateTime('now'));
            $em->persist($timestamp);
            $em->flush();
        } else {

            $project = $em->getRepository('App:Project')->findOneById($projectId);

            if(!$project) {
                return new JsonResponse([
                    'error' => true,
                    'msg' => 'No Project found!'
                ]);
            }
        }

        if(!$project->getWorkers()->contains($user) || $project->getManagement() != $user) {
            return new JsonResponse([
                'error' => true,
                'msg' => 'Not your Project!'
            ]);
        }

        $timestamp = new TimestampProject();
        $timestamp->setProject($project);
        $timestamp->setStartStamp(new \DateTime('now'));
        $timestamp->setUser($user);

        $em->persist($timestamp);
        $em->flush();

        return new JsonResponse(['error' => false,
            'msg' => 'success',
            'type' => 'checkin'
        ]);
    }
}
