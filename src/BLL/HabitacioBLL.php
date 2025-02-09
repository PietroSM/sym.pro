<?php

namespace App\BLL;

use App\Entity\Habitacio;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class HabitacioBLL extends BaseBLL{

    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    public function setSecurity(Security $security)
    {
        $this->security = $security;
    }


    public function nueva(array $data){
        $habitacio = new Habitacio();

        $habitacio->setNombre($data['nombre']);
        $habitacio->setTamany($data['tamany']);
        $habitacio->setCapacitat($data['capacitat']);
        $habitacio->setLocalitzacio($data['localitzacio']);
        $habitacio->setPreu($data['preu']);

        return $this->guardaValidando($habitacio);
    }



    public function toArray(Habitacio $habitacio){
        if(is_null($habitacio)){
            return null;
        }
        
        return [
            'id' => $habitacio->getId(),
            'nombre' => $habitacio->getNombre(),
            'tamany' => $habitacio->getTamany(),
            'capacitat' => $habitacio->getCapacitat(),
            'localitzacio' => $habitacio->getLocalitzacio(),
            'preu' => $habitacio->getPreu()
        ];

    }



}