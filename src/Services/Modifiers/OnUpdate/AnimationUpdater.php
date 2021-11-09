<?php

declare(strict_types=1);

namespace App\Services\Modifiers\OnUpdate;

use App\Entity\Animation;
use App\Services\Modifiers\ModifierInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/*
 * Lorsqu'un utilisateur modifie une animation in est ajouter automatiquement aux 'contributeurs' de cet dernierre.
 * Cela ce traduit a l'ajout d'un ligne dans la table `animation_user` reliant l'utilisateur connecter et l'animation modifier.
 *
 * De plus nous verifions que l'utilisateur ne depasse pas la limite de deux programmes par animation.
 *
 * Enfin nous actulisons le status de l'animation si celle-ci possede une date de publication elle recois le status : en attente de publication.
 * Erratum ceci ne s'applique pas aux animations possedant deja les status publier et depublier, le satatus de l'nimation ne changera pas.
 */

class AnimationUpdater implements ModifierInterface
{
    private ?UserInterface $user;
    private EntityManagerInterface $manager;

    public function __construct(Security $security, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->user = $security->getUser();
    }

    public function update(object $object): void
    {
        if ($object instanceof Animation) {
            $animation = $object;
            $this->addUserToTheAnimation($animation);
        }
    }


    private function addUserToTheAnimation(Animation $animation): void
    {
        if (null !== $this->user) {
            $animation->addContributor($this->user);
            $this->user->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
