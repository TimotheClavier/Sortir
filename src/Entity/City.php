<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $libelle;

    /**
     * @ORM\Column(type="integer")
     */
    private $PostalCode;

    /**
     * @var User[]
     * @ORM\OneToMany(targetEntity="User", mappedBy="city")
     */
    private $users;


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

    public function __toString(){
        // Or change the property that you want to show in the select.
        return $this->libelle;
    }


}
