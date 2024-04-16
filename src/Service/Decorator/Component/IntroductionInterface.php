<?php
namespace App\Service\Decorator\Component;

use App\Service\Interface\NamedInterface;

interface IntroductionInterface {

    public function introduceMyself(NamedInterface $entity): string;
}