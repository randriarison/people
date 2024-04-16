<?php
namespace App\Service\Decorator\Decorator;

use App\Entity\Person;
use App\Service\Decorator\Component\IntroductionInterface;

class IntroductionDecorator implements IntroductionInterface
{

    protected IntroductionInterface $component;
    
    
    public function __construct(IntroductionInterface $component) 
    {
        $this->component = $component;
        
    }

    public function introduceMyself($entity): string
    {
        return $this->component->introduceMyself($entity);
    }
    

}
