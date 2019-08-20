<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TripRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Trip
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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $tripDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $inscriptionDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $seat;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="organisedTrips")
     */
    private $organizer;

    /**
     * @var Situation
     * @ManyToOne(targetEntity="Situation")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cause;

    /**
     * One Product has One Shipment.
     * @ManyToOne(targetEntity="Place")
     */
    private $place;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $coverImage;

    /**
     * User[]
     * Many Groups have Many Users.
     * @ManyToMany(targetEntity="User", mappedBy="trips")
     */
    private $users;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getTripDate()
    {
        return $this->tripDate;
    }

    public function setTripDate(\DateTimeInterface $tripDate)
    {
        $this->tripDate = $tripDate;

        return $this;
    }

    public function getInscriptionDate()
    {
        return $this->inscriptionDate;
    }

    public function setInscriptionDate(\DateTimeInterface $inscriptionDate)
    {
        $this->inscriptionDate = $inscriptionDate;

        return $this;
    }

    public function getSeat()
    {
        return $this->seat;
    }

    public function setSeat(int $seat)
    {
        $this->seat = $seat;

        return $this;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration(int $duration)
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }


    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(Situation $status)
    {
        $this->status = $status;

        return $this;
    }

    public function getCause()
    {
        return $this->cause;
    }

    public function setCause(string $cause)
    {
        $this->cause = $cause;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * @param mixed $organizer
     * @return Trip
     */
    public function setOrganizer($organizer)
    {
        $this->organizer = $organizer;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @param mixed $place
     * @return Trip
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $users
     * @return Trip
     */
    public function setUsers($users)
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return string
     */
    public function getCoverImage()
    {
        return $this->coverImage;
    }

    /**
     * @param string $coverImage
     */
    public function setCoverImage($coverImage)
    {
        $this->coverImage = $coverImage;
    }
}
