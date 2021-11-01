<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Cron\CronBundle\Entity\CronJob;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CronFixtures extends Fixture
{
    private ObjectManager $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->setUpCRONs();

        $manager->flush();
    }

    private function setUpCRONs(): void
    {
        $tempCRONJob = new CronJob();
        $tempCRONJob->setName('Auto publishing')
            ->setCommand('app:publish-awaiting-animations')
            ->setDescription('This jobs has for aim to automatically publish all animations who have the status : "EN ATTENTE DE PUBLICATION" and having a publication date anterior or equal to today\'s date')
            ->setEnabled(true)
            ->setSchedule('30 2 * * *');
        // Tous les jours a 2:30 du matin

        $this->manager->persist($tempCRONJob);
        $this->manager->flush();
    }
}
