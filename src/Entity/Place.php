<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Place
{
    /**
     * Hook SoftDeleteable behavior
     * updates deletedAt field
     */
    use SoftDeleteableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="le libelle ne peut être vide")
     * @Assert\Length(
     *     min="5", minMessage="le libelle doit faire plus de 5 caractère",
     *     max="50", maxMessage="le libelle ne doit pas faire plus de 50 caractères"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @Assert\NotBlank(message="la rue ne peut être vide")
     * @Assert\Length(
     *     min="5", minMessage="la rue doit faire plus de 5 caractère",
     *     max="50", maxMessage="la rue ne doit pas faire plus de 35 caractères"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @Assert\NotBlank(message="la latitude ne peut être vide")
     * @Assert\Length(
     *     min="-90", minMessage="la latitude doit faire plus de -90 degree",
     *     max="90", maxMessage="la latitude ne doit pas faire plus de 90 degree"
     * )
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @Assert\NotBlank(message="la longitude ne peut être vide")
     * @Assert\Length(
     *     min="-180", minMessage="la longitude doit faire plus de -180 degree",
     *     max="180", maxMessage="la longitude ne doit pas faire plus de 180 degree"
     * )
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;


    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="places")
     * @JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

    /**
     * @var Trip[]
     * @ORM\OneToMany(targetEntity="Trip", mappedBy="place", cascade={"remove"})
     */
    private $trips;


    public function getId()
    {
        return $this->id;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet(string $street)
    {
        $this->street = $street;

        return $this;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }
    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return Trip[]
     */
    public function getTrips(): array
    {
        return $this->trips;
    }

    /**
     * @param Trip[] $trips
     */
    public function setTrips(array $trips)
    {
        $this->trips = $trips;
    }

}
