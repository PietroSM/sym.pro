<?php

namespace App\BLL;

use App\Entity\Habitacio;
use App\Repository\HabitacioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseBLL
{
    protected EntityManagerInterface $em;
    protected HabitacioRepository $habitacioRepository;
    protected ValidatorInterface $validator;
    protected RequestStack $requestStack;
    protected Security $security;
    public function __construct(
        EntityManagerInterface $em,
        HabitacioRepository $habitacioRepository,
        ValidatorInterface $validator,
        RequestStack $requestStack,
        Security $security
    ) {
        $this->em = $em;
        $this->habitacioRepository = $habitacioRepository;
        $this->validator = $validator;
        $this->requestStack = $requestStack;
        $this->security = $security;
    }


    protected function guardaValidando($entity): array
    {
        $this->validate($entity); // Validación de los datos
        $this->em->persist($entity); // Se guardan los datos
        $this->em->flush();
        return $this->toArray($entity); // Devolvemos la entidad en forma de array
    }


    public function entitiesToArray(array $entities)
    {
        if (is_null($entities))
            return null;
        $arr = [];
        foreach ($entities as $entity)
            $arr[] = $this->toArray($entity);
        return $arr;
    }


    private function validate($entity)
    {
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            $strError = '';
            foreach ($errors as $error) {
                if (!empty($strError))
                    $strError .= '\n';
                $strError .= $error->getMessage();
            }
            throw new BadRequestHttpException($strError);
        }
    }


    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}
