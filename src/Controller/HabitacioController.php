<?php

namespace App\Controller;

use App\Entity\Habitacio;
use App\Form\HabitacioType;
use App\Repository\HabitacioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/habitacio')]
final class HabitacioController extends AbstractController
{
    #[Route(name: 'app_habitacio_index', methods: ['GET'])]
    public function index(HabitacioRepository $habitacioRepository): Response
    {
        return $this->render('habitacio/index.html.twig', [
            'habitacios' => $habitacioRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_habitacio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $habitacio = new Habitacio();
        $form = $this->createForm(HabitacioType::class, $habitacio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file almacena el archivo subido
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form['nombreImatge']->getData();

            //Generar un nom unic
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move( $this->getParameter('habitacio_directory_gallery'),$fileName );

            // Actualizamos el nombre del archivo en el objeto imagen al nuevo generado
            $habitacio->setNombreImatge($fileName);

            $entityManager->persist($habitacio);
            $entityManager->flush();

            return $this->redirectToRoute('app_habitacio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('habitacio/new.html.twig', [
            'habitacio' => $habitacio,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name:'app_habitacio_delete_json', methods:['DELETE'])]
    public function deleteJson(Habitacio $habitacio, HabitacioRepository $habitacioRepository): Response {
        $habitacioRepository->remove($habitacio, true);
        return new JsonResponse(['eliminado' => true]);
    }


    #[Route('/{id}', name: 'app_habitacio_show', methods: ['GET'])]
    public function show(Habitacio $habitacio): Response
    {
        return $this->render('habitacio/show.html.twig', [
            'habitacio' => $habitacio,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_habitacio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Habitacio $habitacio, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HabitacioType::class, $habitacio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_habitacio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('habitacio/edit.html.twig', [
            'habitacio' => $habitacio,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_habitacio_delete', methods: ['POST'])]
    public function delete(Request $request, Habitacio $habitacio, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$habitacio->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($habitacio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_habitacio_index', [], Response::HTTP_SEE_OTHER);
    }




}
