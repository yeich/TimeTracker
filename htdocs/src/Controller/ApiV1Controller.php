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

                $now = new \DateTime('now');
                $cloned = clone $now;

                foreach ($project->getTimestampProjects() as $project_timestamp) {
                    $cloned->add($project_timestamp->getStartStamp()->diff(($project_timestamp->getEndStamp()) ? $project_timestamp->getEndStamp() : new \DateTime('now')));
                }

                return new JsonResponse([
                    'error' => false,
                    'msg' => 'success',
                    'type' => 'checkout',
                    'data' => [
                        'project_id' => $timestamp->getProject()->getId(),
                        'total_time' => ($now->diff($cloned))->format('%H:%I:%S')
                    ]
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

            $changed_project = true;
        } else {

            $project = $em->getRepository('App:Project')->findOneById($projectId);

            if(!$project) {
                return new JsonResponse([
                    'error' => true,
                    'msg' => 'No Project found!'
                ]);
            }

            $changed_project = true;
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

        if($changed_project) {

            $now = new \DateTime('now');
            $cloned = clone $now;

            foreach ($project->getTimestampProjects() as $project_timestamp) {
                $cloned->add($project_timestamp->getStartStamp()->diff(($project_timestamp->getEndStamp()) ? $project_timestamp->getEndStamp() : new \DateTime('now')));
            }

            return new JsonResponse([
                'error' => false,
                'msg' => 'success',
                'type' => 'checkin',
                'data' => [
                    'project_id' => $timestamp->getProject()->getId(),
                    'project_name' => $timestamp->getProject()->getName(),
                    'total_time' => ($now->diff($cloned))->format('%H:%I:%S')
                ]
            ]);
        } else {

            $now = new \DateTime('now');
            $cloned = clone $now;

            foreach ($project->getTimestampProjects() as $project_timestamp) {
                $cloned->add($project_timestamp->getStartStamp()->diff(($project_timestamp->getEndStamp()) ? $project_timestamp->getEndStamp() : new \DateTime('now')));
            }

            return new JsonResponse([
                'error' => false,
                'msg' => 'success',
                'type' => 'checkin',
                'data' => [
                    'project_id' => $timestamp->getProject()->getId(),
                    'project_name' => $timestamp->getProject()->getName(),
                    'total_time' => ($now->diff($cloned))->format('%H:%I:%S')
                ]
            ]);
        }
    }
}
