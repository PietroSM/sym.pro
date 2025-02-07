<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\HabitacioRepository;
use App\Utils\Utils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends AbstractController {

    #[Route('/', name: 'sym_index')]
    public function index(HabitacioRepository $habitacioRepository, EventRepository $eventRepository): Response{

        $habitaciones = $habitacioRepository->findAll();
        $habitaciones = Utils::extraeElementosAleatorios($habitaciones,4);

        $eventos = $eventRepository->findAll();
        $eventos = Utils::extraeElementosAleatorios($eventos,3);


        return $this->render('index.view.html.twig', [
            'habitacions' => $habitaciones,
            'events' => $eventos
        ]);
    }



}