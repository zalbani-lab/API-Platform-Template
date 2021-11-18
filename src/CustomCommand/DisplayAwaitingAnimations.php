<?php

declare(strict_types=1);

namespace App\CustomCommand;

use App\Repository\AnimationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Just try this out, just have to run this command in the terminal :
 * $ bin/console app:display-awaiting-animations
 */
class DisplayAwaitingAnimations extends Command
{
    protected static $defaultName = 'app:display-awaiting-animations';
    private AnimationRepository $animationRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(AnimationRepository $animationRepository, EntityManagerInterface $entityManager)
    {
        $this->animationRepository = $animationRepository;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Set display true for awaiting animations.')
            ->setHelp('This command set animation display to true for animation who display date is inferior or equal to present date');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $allWaitingAnimations = $this->animationRepository->findAwaitingToDisplayUntilToday();

        $this->displayAnimations($allWaitingAnimations, $output);

        $output->write('Operation successfully completed');

        return Command::SUCCESS;
    }


    private function displayAnimations(array $animations, OutputInterface $output)
    {

        $output->writeln([
            ''.count($animations).' animation(s) publish today :',
            '============================================',
            '',
        ]);

        foreach ($animations as $animation) {
            $output->writeln([
                'Id : '.$animation->getId().' Title : '.$animation->getTitle(),
                '----------------------------------------',
            ]);
            $animation->setDisplay(true);
            $this->entityManager->persist($animation);
        }

        $this->entityManager->flush();
    }
}
