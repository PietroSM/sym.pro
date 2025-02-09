<?php
namespace App\Controller\API;

use App\BLL\HabitacioBLL;
use App\Entity\Habitacio;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class HabitacioApiController extends BaseApiController{

    #[Route('/habitacionesapinueva', name: 'api_post_habitaciones', methods: ['POST'])]
    public function post(Request $request, HabitacioBLL $habitacioBLL){
        $data = $this->getContent($request);
        $habtiacio =$habitacioBLL->nueva($data);
        
        return $this->getResponse($habtiacio, Response::HTTP_CREATED);
    }


    #[Route('/habitacionesapi/{id}', name:'api_get_habitacio', requirements: ['id' => '\d'], methods: ['GET'])]
    public function getOne(Habitacio $habitacio, HabitacioBLL $habitacioBLL){
        return $this->getResponse($habitacioBLL->toArray($habitacio));
    }


    #[Route('/habitacionesapi', name: 'api_get_habitaciones', methods:['GET'])]
    #[Route('/habitacionesapi/ordenadas/{order}', name: 'api_get_habitaciones_ordenadas', methods: ['GET'])]
    public function getAll(Request $request, HabitacioBLL $habitacioBLL, $order='id'){
        $habitaciones = $habitacioBLL->getHabitaciones($order);

        return $this->getResponse($habitaciones);
    }


    #[Route('/habitacionesapi/{id}', name: 'api_delete_habitacion', requirements: ['id'=>'\d'], methods:['DELETE'])]
    public function delete(Habitacio $habitacio, HabitacioBLL $habitacioBLL)
    {
        $habitacioBLL->delete($habitacio);
        return $this->getResponse(null, Response:: HTTP_NO_CONTENT );
    }


    #[Route('/habitacionesapi/{id}', name: 'api_update_habitacion', requirements: ['id'=>'\d'], methods: ['PUT'])]
    public function update(Request $request, Habitacio $habitacio, HabitacioBLL $habitacioBLL)
    {
        $data = $this->getContent($request);
        $habitacio = $habitacioBLL->actualizaHabitacion( $habitacio, $data);
        return $this->getResponse($habitacio, Response:: HTTP_OK );
    }
}