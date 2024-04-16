<?php
namespace App\Service\Decorator\Component;

use App\Entity\Person;
use App\Service\Interface\NamedInterface;

/* concrete component */
class PersonItroductionComponent implements IntroductionInterface
{
    public function introduceMyself(NamedInterface $entity): string
    {
        return "Je m'appelle " . $entity->getName() . ".";
    }
}