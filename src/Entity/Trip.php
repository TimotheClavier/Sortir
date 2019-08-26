<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TripRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Trip implements \Serializable
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
     * @Assert\NotBlank(message="le nom ne peut être vide")
     * @Assert\Length(
     *     min="5", minMessage="le nom doit faire plus de 5 caractère",
     *     max="20", maxMessage="le nom ne doit pas faire plus de 55 caractères"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\GreaterThan("today", message="La date de la sortie doit être suppérieur à aujourd'hui")
     * @ORM\Column(type="datetime")
     */
    private $tripDate;

    /**
     * @Assert\GreaterThan("today", message="La date d'inscription doit être suppérieur à aujourd'hui")
     * @Assert\LessThan(propertyPath="tripDate", message="La date d'inscription doit être suppérieur au {{ compared_value }}")
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
     * @Assert\NotBlank(message="la déscription ne peut être vide")
     * @Assert\Length(
     *     min="5", minMessage="la déscription doit faire plus de 5 caractère",
     * )
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @MaxDepth(2)
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
     * @ManyToOne(targetEntity="Place", inversedBy="trips")
     * @MaxDepth(2)
     */
    private $place;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $coverImage;

    /**
     * user[]
     * Many Groups have Many Users.
     * @MaxDepth(2)
     * @ManyToMany(targetEntity="User", mappedBy="trips")
     */
    private $users;

    /**
     * Trip constructor.
     */
    public function __construct()
    {
        $this->tripDate = new \DateTime();
        $this->inscriptionDate = new \DateTime();
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
     * @param $user
     * @return Trip
     */
    public function addUser($user)
    {
        $this->users[] = $user;

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

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return null;
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        return null;
    }
}
