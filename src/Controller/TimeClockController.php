<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\TimeEntry;
use App\Repository\TimeEntryRepository;

final class TimeClockController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->redirectToRoute('app_time_entry');
    }

    #[Route('/time/clock', name: 'app_time_entry')]
    public function index(): Response
    {
        return $this->render('time_clock/index.html.twig', [
            'controller_name' => 'TimeClockController',
        ]);
    }

#[Route('/api/clock-in', methods: ['POST'])]
    public function clockIn(EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }

        $timeEntry = new TimeEntry();
        $timeEntry->setUserId($user);
        $timeEntry->setClockIn(new \DateTime());
        $em->persist($timeEntry);
        $em->flush();
        return new JsonResponse(['success' => true]);
    }

    #[Route('/api/clock-out', methods: ['POST'])]
    public function clockOut(TimeEntryRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }
        $timeEntry = $repo->findOneBy(['userId' => $user, 'clockOut' => null]);
        if (!$timeEntry) {
            return new JsonResponse(['error' => 'Time entry not found'], Response::HTTP_NOT_FOUND);
        }
        $timeEntry->setClockOut(new \DateTime());
        $em->persist($timeEntry);
        $em->flush();
        return new JsonResponse(['success' => true]);
    }

    #[Route('/api/time-entries', methods: ['GET'])]
    public function getTimeEntries(TimeEntryRepository $repo): JsonResponse
    {
        $timeEntries = $repo->findBy(['userId' => $this->getUser()]);
        return $this->json($timeEntries);
    }

    #[Route('/api/time-entries-by-date', methods: ['GET'])]
    public function getTimeEntriesByDate(Request $request, TimeEntryRepository $repo): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
        }

        $startDateQuery = $request->query->get('startDate');
        $endDateQuery = $request->query->get('endDate');

        if (!$startDateQuery || !$endDateQuery) {
            return new JsonResponse(['error' => 'Missing startDate or endDate'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $startDate = new \DateTime($startDateQuery);
            $endDate = new \DateTime($endDateQuery);
            $endDate->setTime(23, 59, 59); // Ensure endDate covers the full day

            // Fetch total time (in seconds) within the range
            $totalSeconds = $repo->findByUserAndDateRange($user, $startDate, $endDate);

            // Convert seconds into a more readable format (e.g., hours with floating point)
            $hours = round($totalSeconds / 3600, 2);

            return $this->json([
                'total_time' => $totalSeconds,
                'readable_time' => $hours
            ]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid date format'], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/api/time/entry/last', name: 'app_time_entry_last')]
    public function getLastTimeEntry(TimeEntryRepository $repo): JsonResponse
    {
        $lastTimeEntry = $repo->findOneBy(['userId' => $this->getUser()], ['clockIn' => 'DESC']);

        if (!$lastTimeEntry) {
            return $this->json(['hasClockIn' => false, 'hasClockOut' => false]);
        }

        return $this->json([
            'hasClockIn' => $lastTimeEntry->getClockIn() !== null,
            'hasClockOut' => $lastTimeEntry->getClockOut() !== null
        ]);
    }
}
