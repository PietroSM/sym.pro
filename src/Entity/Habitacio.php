<?php

namespace App\Entity;

use App\Repository\HabitacioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: HabitacioRepository::class)]
class Habitacio
{
    const RUTA_IMAGEN_HABITACION_SUBIDAS = '/images/habitaciones/';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column]
    private ?int $tamany = null;

    #[ORM\Column]
    private ?int $capacitat = null;

    #[ORM\Column(length: 255)]
    private ?string $localitzacio = null;

    #[ORM\Column]
    private ?int $preu = null;

    #[ORM\Column(length: 255)]
    /**
    * @Assert\File(
    * mimeTypes={"image/jpeg","image/png"},
    * mimeTypesMessage = "Solamente se permiten archivos jpeg o png.")
    */
    private ?string $nombreImatge = null;

    #[ORM\Column]
    private ?int $idClient = null;

    #[ORM\ManyToOne(inversedBy: 'habitacios')]
    private ?User $id_client = null;

    public function __construct(
        $nombre = "",
        $tamany = 0,
        $capacitat = 0,
        $localitzacio = "",
        $preu = 0,
        $nombreImatge = ""
    )
    {
        $this->id = null;
        $this->nombre = $nombre;
        $this->tamany = $tamany;
        $this->capacitat = $capacitat;
        $this->localitzacio = $localitzacio;
        $this->preu = $preu;
        $this->nombreImatge = $nombreImatge;
        $this->idClient = 0;
    }


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

    public function getTamany(): ?int
    {
        return $this->tamany;
    }

    public function setTamany(int $tamany): static
    {
        $this->tamany = $tamany;

        return $this;
    }

    public function getCapacitat(): ?int
    {
        return $this->capacitat;
    }

    public function setCapacitat(int $capacitat): static
    {
        $this->capacitat = $capacitat;

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

    public function getPreu(): ?int
    {
        return $this->preu;
    }

    public function setPreu(int $preu): static
    {
        $this->preu = $preu;

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

    public function getIdClient(): ?int
    {
        return $this->idClient;
    }

    public function setIdClient(int $idClient): static
    {
        $this->idClient = $idClient;

        return $this;
    }


    public function getId_Client(): ?User
    {
        return $this->id_client;
    }

    public function setId_Client(?User $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getUrlSubidas(){
        return self::RUTA_IMAGEN_HABITACION_SUBIDAS . $this->getNombreImatge();
    }

}
