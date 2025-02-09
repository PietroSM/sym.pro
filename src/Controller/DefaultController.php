<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\HabitacioRepository;
use App\Utils\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    #[Route('/habDisponibles', name: 'app_habitacio_disponibles', methods: ['POST'])]
    public function consultarDisponibilidad(Request $request, HabitacioRepository $habitacioRepository): Response
    {
        $localizacion = $request->request->get('localizacion');
        $personas = (int) $request->request->get('persona');
    
        $habitacions = $habitacioRepository->findByLocationAndCapacity($localizacion, $personas);
    
        return $this->render('habitacio/index.html.twig', [
            'habitacios' => $habitacions,
        ]);
    }



    #[Route('/misReservas', 'app_reservas', methods: ['GET'])]
    public function misReservas(HabitacioRepository $habitacioRepository){
        $usuario = $this->getUser();

        $habitacions = $habitacioRepository->findByUser($usuario);

        return $this->render('misReservas.html.twig', [
            'habitacions' => $habitacions,
        ]);
    }
    
    #[Route('/Cancelar/{id}', name: 'app_habitacio_cancelar', methods: ['GET'])]
    public function update(HabitacioRepository $habitacioRepository, EntityManagerInterface $entityManager, int $id): Response {
        $habitacio = $habitacioRepository->find($id);
        $habitacio->setId_Client(null);
        $entityManager->flush();

        $habitacio = $habitacioRepository->find($id);
        return $this->render('habitacio/show.html.twig',[
            'habitacio' => $habitacio,
        ]);
    }



}