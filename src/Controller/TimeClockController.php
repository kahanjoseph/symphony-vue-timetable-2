<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TimeClockController extends AbstractController
{
    #[Route('/time/clock', name: 'app_time_clock')]
    public function index(): Response
    {
        return $this->render('time_clock/index.html.twig', [
            'controller_name' => 'TimeClockController',
        ]);
    }
}
