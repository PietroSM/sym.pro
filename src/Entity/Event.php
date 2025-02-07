<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    const RUTA_IMAGEN_EVENTS_SUBIDAS = '/images/events/';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $fecha = null;

    #[ORM\Column(length: 255)]
    private ?string $descripcion = null;

    #[ORM\Column(length: 255)]
    /**
    * @Assert\File(
    * mimeTypes={"image/jpeg","image/png"},
    * mimeTypesMessage = "Solamente se permiten archivos jpeg o png.")
    */
    private ?string $nombreImatge = null;

    #[ORM\Column(length: 255)]
    private ?string $localitzacio = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getFecha(): ?string
    {
        return $this->fecha;
    }

    public function setFecha(string $fecha): static
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getNombreImatge(): ?string
    {
        return $this->nombreImatge;
    }

    public function setNombreImatge(string $nombreImatge): static
    {
        $this->nombreImatge = $nombreImatge;

        return $this;
    }

    public function getLocalitzacio(): ?string
    {
        return $this->localitzacio;
    }

    public function setLocalitzacio(string $localitzacio): static
    {
        $this->localitzacio = $localitzacio;

        return $this;
    }


    public function getUrlSubidas(){
        return self::RUTA_IMAGEN_EVENTS_SUBIDAS . $this->getNombreImatge();
    }

}
