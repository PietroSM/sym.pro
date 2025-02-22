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
    #[Route('/orden/{ordenacion}', name:'app_habitacio_index_ordenat', methods: ['GET'])]
    public function index(Request $requestStack ,HabitacioRepository $habitacioRepository, string $ordenacion=null): Response
    {
        if(!is_null($ordenacion)){
            $tipoOrdenacion='asc';
            $session = $requestStack->getSession();
            $habitacioOrdenacion = $session->get('habitacioOrdenacion');

            if(!is_null($habitacioOrdenacion)){
                if($habitacioOrdenacion['ordenacion'] === $ordenacion){
                    if($habitacioOrdenacion['tipoOrdenacion'] === 'asc'){
                        $tipoOrdenacion='desc';
                    }
                }
            }

            $session->set('habitacioOrdenacion', [ // Se guarda la ordenación actual
                'ordenacion'=>$ordenacion,
                'tipoOrdenacion'=>$tipoOrdenacion
                ]);
            
        } else {
            $ordenacion='id';
            $tipoOrdenacion='asc';
        }

        $habitacions = $habitacioRepository->findBy([], [$ordenacion=>$tipoOrdenacion]);
        return $this->render('habitacio/index.html.twig', [
            'habitacios' => $habitacions,
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


    #[Route('/busqueda', name:'app_habitacio_index_busqueda', methods:['POST'])]
    public function busqueda(Request $request, HabitacioRepository $habitacioRepository): Response{
        $busqueda = $request->request->get('busqueda');
        $habitacions = $habitacioRepository->findLikeDescripcion($busqueda);

        return $this->render('habitacio/index.html.twig', [
            'habitacios' => $habitacions,
        ]);

    }


    #[Route('/update/{id}', name: 'app_habitacio_update', methods: ['GET'])]
    public function update(HabitacioRepository $habitacioRepository, EntityManagerInterface $entityManager, int $id): Response {

        $habitacio = $habitacioRepository->find($id);
        $usuario = $this->getUser();
        $habitacio->setId_Client($usuario);
        $entityManager->flush();

        $habitacio = $habitacioRepository->find($id);
        return $this->render('habitacio/show.html.twig',[
            'habitacio' => $habitacio,
        ]);
    }



    #[Route('/{id}', name:'app_habitacio_delete_json', methods:['DELETE'])]
    public function deleteJson(Habitacio $habitacio, HabitacioRepository $habitacioRepository): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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

    #[Route('/edit/{id}', name: 'app_habitacio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Habitacio $habitacio, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
