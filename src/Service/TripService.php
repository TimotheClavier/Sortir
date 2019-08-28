<?php


namespace App\Service;


use App\Entity\Situation;
use App\Entity\Trip;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TripService
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * UpdateService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getHappyMessage()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }

    public function updateStatus()
    {
        $entityManager = $this->container->get('doctrine')->getManager();
        $now = new \DateTime('', new \DateTimeZone('Europe/Paris'));
        /** @var Trip[] $trips */
        $trips = $this->container->get('doctrine')->getRepository(Trip::class)->findAll();
        /** @var Situation[] $situations */
        $situations = $this->container->get('doctrine')->getRepository(Situation::class)->findAll();
        $situationsByID = array();

        foreach ($situations as $situation) {
            $situationsByID[$situation->getId()] = $situation;
        }

        foreach ($trips as $trip) {
            $endDate = new \DateTime($trip->getTripDate()->format('c'));
            $endDate->modify('+' . $trip->getDuration() . ' minutes');
            if ($trip->getStatus()->getId() == 1 || $trip->getStatus()->getId() == 6) {
                continue;
            }
            if ($now > $trip->getInscriptionDate() || $trip->getSeat() == count($trip->getUsers())) {
                $trip->setStatus($situationsByID[3]);
            }
            if ($now > $trip->getTripDate()) {
                $trip->setStatus($situationsByID[4]);
            }
            if ($now > $endDate) {
                $trip->setStatus($situationsByID[5]);
            }
        }
        $entityManager->flush();
    }

    public function deleteOldTrips()
    {
        $entityManager = $this->container->get('doctrine')->getManager();
        $now = new \DateTime('', new \DateTimeZone('Europe/Paris'));
        $now->modify('-1 Months');

        /** @var Trip[] $trips */
        $trips = $this->container->get('doctrine')->getRepository(Trip::class)->findAll();

        foreach ($trips as $trip) {
            $endDate = new \DateTime($trip->getTripDate()->format('Y-m-d'));
            $endDate->modify('+' . $trip->getDuration() . ' minutes');

            if ($now > $endDate) {
                $entityManager->remove($trip);
            }
        }
        $entityManager->flush();
    }
}