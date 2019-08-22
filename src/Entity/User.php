<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class User implements UserInterface
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
     * @Assert\NotBlank(message="l'e-mail ne peut être vide")
     * @Assert\Length(
     *     min="5", minMessage="l'e-mail doit faire plus de 5 caractère",
     *     max="50", maxMessage="l'e-mail ne doit pas faire plus de 50 caractères"
     * )
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\NotBlank(message="le mot de passe ne peut être vide")
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @Assert\NotBlank(message="le prénom ne peut être vide")
     * @Assert\Length(
     *     min="3", minMessage="le prénom doit faire plus de 3 caractère",
     *     max="25", maxMessage="le prénom ne doit pas faire plus de 25 caractères"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @Assert\NotBlank(message="le nom ne peut être vide")
     * @Assert\Length(
     *     min="3", minMessage="le nom doit faire plus de 3 caractère",
     *     max="25", maxMessage="le nom ne doit pas faire plus de 25 caractères"
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @Assert\NotBlank(message="le numéro ne peut être vide")
     * @Assert\Length(
     *     min="10", max="10", exactMessage="le numéro doit faire exactement 10 caractères"
     * )
     * @ORM\Column(type="string", nullable=true)
     */
    private $telephone;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="users")
     * @JoinColumn(nullable=true, name="city_id", referencedColumnName="id", onDelete="SET NULL")
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
     * @return string?
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param $avatar
     * @return User
     */
    public function setAvatar($avatar)
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
     * @param $trip
     * @return User
     */
    public function addTrip($trip)
    {
        $this->trips[] = $trip;

        return $this;
    }

    /**
     * @param $trip
     */
    public function removeTrip($trip)
    {
        foreach ($this->trips as $tr){
            if($tr == $trip){
                $tr = null;
            }
        }
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
