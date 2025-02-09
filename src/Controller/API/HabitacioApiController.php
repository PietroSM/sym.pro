<?php
namespace App\Controller\API;

use App\BLL\HabitacioBLL;
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

}