<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SituationRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false, hardDelete=false)
 */
class Situation
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
    private $libelle;

    public function getId()
    {
        return $this->id;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function getIcon()
    {
        $icon = "";
        switch ($this->getId()) {
            case 1:
                $icon = "far fa-plus-square";
                break;
            case 2:
                $icon = "fas fa-check";
                break;
            case 3:
                $icon = "fas fa-lock";
                break;
            case 4:
                $icon = "fas fa-spinner";
                break;
            case 5:
                $icon = "fas fa-times";
                break;
            case 6:
                $icon = "fas fa-window-close";
                break;
        }
        return $icon;
    }
}
