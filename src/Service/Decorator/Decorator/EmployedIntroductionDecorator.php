<?php
namespace App\Service\Decorator\Decorator;

use App\Entity\Occupation;
use App\Service\Interface\EmployedInterface;

/* concrete decorator */
class EmployedIntroductionDecorator extends IntroductionDecorator 
{
    public function introduceMyself($entity): string
    {
        $introduction = parent::introduceMyself($entity);
        if ($entity instanceof EmployedInterface && $entity->getOccupation() instanceof Occupation) {
            $introduction .= " Je suis " . $entity->getOccupation()->getName() . " de métier.";
        }
        return $introduction;
    }
}