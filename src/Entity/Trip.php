<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TripRepository")
 */
class Trip
{
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
     * @ORM\Column(type="integer")
     */
    private $organizer;

    /**
     *
     * @ORM\Column(type="integer")
     * @OneToOne(targetEntity="Situation")
     * @JoinColumn(name="situtation_id", referencedColumnName="id")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cause;

    /**
     * One Product has One Shipment.
     * @OneToOne(targetEntity="Place")
     * @JoinColumn(name="place_id", referencedColumnName="id")
     */
    private $place;

    /**
     * Many Groups have Many Users.
     * @ManyToMany(targetEntity="User", mappedBy="trips")
     */
    private $users;

    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }


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

    public function setStatus(int $status)
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
}
