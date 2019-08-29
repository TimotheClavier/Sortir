<?php

namespace App\EventListener;

use App\Entity\City;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CityListener
{
    public function PreRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof City) {
            $entityManager = $args->getObjectManager();
            foreach ($entity->getUsers() as $user) {
                $user->setCity(null);
                $entityManager->persist($user);
            }
            $entityManager->flush();
        }
    }
}