<?php

namespace App\Command;

use App\Service\TripService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UpdateStatusCommand extends Command
{
    protected static $defaultName = 'update:status';
    private $tripService;

    /**
     * UpdateStatusCommand constructor.
     * @param TripService $tripService
     */
    public function __construct(TripService $tripService)
    {
        $this->tripService = $tripService;
        parent::__construct();
    }


    protected function configure()
    {
        $this
            ->setDescription('Update the status of trips according to the current time')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->tripService->updateStatus();
        $this->tripService->deleteOldTrips();
        $io->success('Done');
    }
}
