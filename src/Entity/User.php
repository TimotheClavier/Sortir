<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephone;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="users")
     */
    private $city;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * Trip[]
     * @ManyToMany(targetEntity="Trip", inversedBy="users")
     * @JoinTable(name="users_trips")
     */
    private $trips;

    /**
     * @var Trip[]
     * @ORM\OneToMany(targetEntity="Trip", mappedBy="organizer")
     */
    private $organisedTrips;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername()
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return (string) $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom(string $nom)
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelephone()
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar(string $avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity(City $city)
    {
        $this->city = $city;

        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Trip[]
     */
    public function getTrips()
    {
        return $this->trips;
    }

    /**
     * @param mixed $trips
     * @return User
     */
    public function setTrips($trips)
    {
        $this->trips = $trips;

        return $this;
    }

    /**
     * @return Trip[]
     */
    public function getOrganisedTrips()
    {
        return $this->organisedTrips;
    }

    /**
     * @param Trip[] $organisedTrips
     * @return User
     */
    public function setOrganisedTrips(array $organisedTrips)
    {
        $this->organisedTrips = $organisedTrips;

        return $this;
    }

    public function __toString()
    {
      return $this->nom.' '.$this->prenom;
    }

}
