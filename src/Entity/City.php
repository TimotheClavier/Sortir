<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City implements \JsonSerializable
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Le nom de la ville ne peut être vide")
     * @Assert\Length(
     *     min="3", minMessage="Le nom de la ville doit faire plus de 3 caractère",
     *     max="25", maxMessage="Le nom de la ville ne doit pas faire plus de 25 caractères"
     * )
     * @ORM\Column(type="string", length=25)
     */
    private $libelle;

    /**
     * @Assert\NotBlank(message="l'e-mail ne peut être vide")
     * @Assert\Length(
     *     min="5",
     *     max="5", exactMessage="le code postal doit faire exactement 5 caractères"
     * )
     * @ORM\Column(type="integer")
     */
    private $PostalCode;

    /**
     * @var User[]
     * @ORM\OneToMany(targetEntity="User", mappedBy="city")
     */
    private $users;

    /**
     * @var Place[]
     * @ORM\OneToMany(targetEntity="Place", mappedBy="city", cascade={"remove"})
     */
    private $places;



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

    public function getPostalCode()
    {
        return $this->PostalCode;
    }

    public function setPostalCode(int $PostalCode)
    {
        $this->PostalCode = $PostalCode;

        return $this;
    }

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @return Place[]
     */
    public function getPlaces(): array
    {
        return $this->places;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        // Or change the property that you want to show in the select.
        return $this->libelle;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'id'        =>  $this->id,
            'libelle'   =>  $this->libelle,
            'postalCode'    =>  $this->PostalCode
        ];
    }
}
