<?php

declare(strict_types=1);

namespace App\Tests\Func;

use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

trait SymfonyComponent
{
    protected static ?KernelBrowser $kernelBrowser = null;

    public function getEntityManager(): ObjectManager
    {
        return self::getKernel()->getContainer()
            ->get('doctrine')
            ->resetManager();
    }

    public function getSecurityEncoder(): UserPasswordEncoder
    {
        return self::getKernel()->getContainer()
            ->get('security.password_encoder');
    }

    private function getKernel(): KernelBrowser
    {
        if (null === self::$kernelBrowser) {
            self::$kernelBrowser = static::createClient();
        }

        return self::$kernelBrowser;
    }
}
